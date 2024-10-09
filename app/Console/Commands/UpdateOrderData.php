<?php

namespace App\Console\Commands;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

class UpdateOrderData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:order-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::oldest('updated_at')->limit(1)->get()->each(function ($order) {
            $order->touchQuietly();

            if(! $order->shop){
                return;
            }

            $marketplace = MarketplaceFactory::create($order->shop);
            $orderDetail = $marketplace->orderDetail($order->identifier);
            if ($orderDetail['success']) {
                $order->data = $orderDetail['array']; //json
                $order->total = $this->extractOrderTotal($orderDetail['array']); //array
                $order->address = $this->extractOrderAddress($orderDetail['array']); //string
                $order->contact = $this->extractOrderContact($orderDetail['array']); //string
                $order->customer = $this->extractOrderCustomer($orderDetail['array']); //string
                $order->offer_id = $order->offer_id ?? $this->extractOrderOfferId($orderDetail['array']); //string;
            }

            if ($order->offer_id) {
                $message = Message::firstWhere('chat_id', $order->offer_id);
                if ($message) {
                    $messageData = ['order_status' => $order->tab];
                    if ($order->isDirty('offer_id') && $order->tab == 'to_start') {
                        $messageData['is_archived'] = 0;
                    }
                    $message->update($messageData);
                    $order->message_id = $message->id;
                } else {
                    $offerDetail = $marketplace->offerDetail($order->offer_id);
                    if (! empty($offerDetail)) {
                        $message = Message::create([
                            'shop_id' => $order->shop->id,
                            'chat_id' => $offerDetail['chat_id'],
                            'buyer_id' => $offerDetail['buyer_id'],
                            'username' => $offerDetail['username'],
                            'profile_image' => $offerDetail['profile_image'],
                            'product_title' => $offerDetail['product_title'],
                            'product_image' => $offerDetail['product_image'],
                            'price_formatted' => $offerDetail['price_formatted'],
                            'product_url' => $offerDetail['product_url'],
                            'channel_url' => $offerDetail['channel_url'],
                            'latest_message' => $offerDetail['latest_message'],
                            'unread_count' => $offerDetail['unread_count'],
                            'order_status' => $order->tab,
                            'latest_created' => $offerDetail['latest_created'],
                            'data' => $offerDetail['data'],
                        ]);
                        $order->message_id = $message->id;
                    }
                }
            }

            $order->save();
        });
    }

    protected function extractOrderTotal(array $data = [])
    {
        $array = [];
        if (!empty($data['orderDetails']['priceBreakdown'])
            || !empty($data['orderDetails']['priceBreakdownTotal'])
        ) {
            if (!empty($data['orderDetails']['priceBreakdown'])) {
                foreach ($data['orderDetails']['priceBreakdown'] as $price) {
                    $array[] = ['label' => $price['label'], 'amount' => $price['amount']];
                }
            }
            if (!empty($data['orderDetails']['priceBreakdownTotal'])) {
                $array[] = [
                    'label' => $data['orderDetails']['priceBreakdownTotal']['label'],
                    'amount' => $data['orderDetails']['priceBreakdownTotal']['amount']
                ];
            }
        } elseif (isset($data['orderDetails']['orderDetailV1']['value'])) {
            $value = json_decode($data['orderDetails']['orderDetailV1']['value'], true);
            if (!empty($value['payment_info']['breakdown_list'])) {
                foreach ($value['payment_info']['breakdown_list'] as $price) {
                    $array[] = ['label' => $price['display_name'], 'amount' => $price['amount']];
                }
            }
            if (!empty($value['payment_info']['total_info'])) {
                $array[] = [
                    'label' => $value['payment_info']['total_info']['display_name'],
                    'amount' => $value['payment_info']['total_info']['amount']
                ];
            }
        }
        return $array;
    }

    protected function extractOrderAddress(array $data = [])
    {
        $address = null;
        if (isset($data['orderDetails']['logisticsInfo']['value'])) {
            $value = json_decode($data['orderDetails']['logisticsInfo']['value'], true);
            $parts = array_values($value['Location']['Address'] ?? []);
            $address = implode(', ', $parts);
        } elseif (isset($data['orderDetails']['orderDetailV1']['value'])) {
            $value = json_decode($data['orderDetails']['orderDetailV1']['value'], true);
            $parts = array_values($value['logistics_info']['Location']['Address'] ?? []);
            $address = implode(', ', $parts);
        }
        return $address;
    }

    protected function extractOrderContact(array $data = [])
    {
        $contact = null;
        if (isset($data['orderDetails']['logisticsInfo']['value'])) {
            $value = json_decode($data['orderDetails']['logisticsInfo']['value'], true);
            $contact = $value['receiver_phone'];
        } elseif (isset($data['orderDetails']['orderDetailV1']['value'])) {
            $value = json_decode($data['orderDetails']['orderDetailV1']['value'], true);
            $contact = $value['logistics_info']['receiver_phone'];
        }
        return $contact;
    }

    protected function extractOrderCustomer(array $data = [])
    {
        $customer = null;
        if (isset($data['orderDetails']['logisticsInfo']['value'])) {
            $value = json_decode($data['orderDetails']['logisticsInfo']['value'], true);
            $customer = $value['receiver_full_name'];
        } elseif (isset($data['orderDetails']['orderDetailV1']['value'])) {
            $value = json_decode($data['orderDetails']['orderDetailV1']['value'], true);
            $customer = $value['logistics_info']['receiver_full_name'];
        }
        return $customer;
    }

    protected function extractOrderOfferId(array $data = [])
    {
        $offerId = null;
        if (!empty($data['orderDetails']['orders'])) {
            foreach ($data['orderDetails']['orders'] as $order) {
                if (isset($order['items'])) {
                    foreach ($order['items'] as $item) {
                        $offerId = $item['offerId']['value'] ?? null;
                    }
                }
            }
        } elseif (isset($data['orderDetails']['orderDetailV1']['value'])) {
            $value = json_decode($data['orderDetails']['orderDetailV1']['value'], true);
            $offerId = $value['offer_id'];
        }
        return $offerId;
    }
}
