<?php

namespace App\Console\Commands;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Console\Command;

class UpdateOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Message::where('data->is_product_sold', true)->whereNull('order_detail')
            ->orderBy('latest_created', 'desc')->limit(1)
            ->get()->each(function ($message) {
                $shop = Shop::findOrFail($message->shop_id);
                $marketplace = MarketplaceFactory::create($shop);
                $order = $marketplace->orderData($message, false);
                if ($orderId = $order['id']) {
                    $orderDetail = $marketplace->orderDetail($orderId);
                    if ($orderDetail['success']) {
                        $saved = Message::findOrFail($message->id);
                        $saved->order_id = $orderId; //string
                        $saved->order_detail = $orderDetail['array']; //json
                        $saved->order_total = $this->extractOrderTotal($orderDetail['array']); //json
                        $saved->order_address = $this->extractOrderAddress($orderDetail['array']); //string
                        $saved->order_contact = $this->extractOrderContact($orderDetail['array']); //string
                        $saved->order_customer = $this->extractOrderCustomer($orderDetail['array']); //string
                        $saved->save();
                    }
                }
            });
    }

    protected function extractOrderTotal(array $data = [])
    {
        $array = [];
        if (isset($data['orderDetails']['priceBreakdown'])) {
            foreach ($data['orderDetails']['priceBreakdown'] as $price) {
                $array[] = ['label' => $price['label'], 'amount' => $price['amount']];
            }
        }
        if (isset($data['orderDetails']['priceBreakdownTotal'])) {
            $array[] = [
                'label' => $data['orderDetails']['priceBreakdownTotal']['label'],
                'amount' => $data['orderDetails']['priceBreakdownTotal']['amount']
            ];
        }

        return $array;
    }

    protected function extractOrderAddress(array $data = [])
    {
        $address = '';
        if (isset($data['orderDetails']['logisticsInfo']['value'])) {
            $value = json_decode($data['orderDetails']['logisticsInfo']['value'], true);
            $parts = array_values($value['Location']['Address']);
            $address = implode(', ', $parts);
        }
        return $address;
    }

    protected function extractOrderContact(array $data = [])
    {
        $contact = '';
        if (isset($data['orderDetails']['logisticsInfo']['value'])) {
            $value = json_decode($data['orderDetails']['logisticsInfo']['value'], true);
            $contact = $value['receiver_phone'];
        }
        return $contact;
    }

    protected function extractOrderCustomer(array $data = [])
    {
        $customer = '';
        if (isset($data['orderDetails']['logisticsInfo']['value'])) {
            $value = json_decode($data['orderDetails']['logisticsInfo']['value'], true);
            $customer = $value['receiver_full_name'];
        }
        return $customer;
    }
}
