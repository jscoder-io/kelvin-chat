<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Shop;
use Livewire\Component;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class OrderTable extends Component
{
    public $filters = [
        'shop' => 0,
        'status' => '',
        'search' => '',
    ];

    public $columns = [
        'status' => true,
        'date' => true,
        'shop' => true,
        'product' => false,
        'customer_id' => false,
        'customer_name' => false,
        'contact' => false,
        'subtotal' => false,
        'delivery_fee' => false,
        'platform_fee' => false,
        'total' => false,
    ];

    public $offset = 0;

    public function prev()
    {
        $this->offset -= 10;
    }

    public function next()
    {
        $this->offset += 10;
    }

    protected function getShops()
    {
        $shops = Shop::latest();
        if (auth()->user()->role > 1) {
            $shops->whereIn('id', auth()->user()->shop);
        }

        return $shops->get();
    }

    protected function getOrders()
    {
        $orders = Order::orderBy('updated_at', 'desc')->offset($this->offset)->limit(10);
        if ($this->filters['shop']) {
            $orders->where('shop_id', $this->filters['shop']);
        } elseif (auth()->user()->role > 1) {
            $shops = Shop::whereIn('id', auth()->user()->shop)->get()
                ->map(function ($shop) {
                    return $shop->id;
                })->all();
            $shopIds = count($shops) > 0 ? $shops : [0];
            $orders->whereIn('shop_id', $shopIds);
        } else {
            $shops = Shop::get()->map(function ($shop) {
                return $shop->id;
            })->all();
            $shopIds = count($shops) > 0 ? $shops : [0];
            $orders->whereIn('shop_id', $shopIds);
        }

        if ($this->filters['status']) {
            $orders->where('tab', $this->filters['status']);
        }

        if ($this->filters['search']) {
            $orders->where(function ($query) {
                $query->whereIn('message_id', function (Builder $subquery) {
                    $subquery->select('id')->from('messages')
                        ->whereRaw('`messages`.`username` LIKE \'%'.$this->filters['search'].'%\'')
                        ->orWhereRaw('`messages`.`product_title` LIKE \'%'.$this->filters['search'].'%\'');
                });
                $query->orWhere('identifier', 'LIKE', '%'.$this->filters['search'].'%');
                $query->orWhere('customer', 'LIKE', '%'.$this->filters['search'].'%');
                $query->orWhere('contact', 'LIKE', '%'.$this->filters['search'].'%');
                $query->orWhere('address', 'LIKE', '%'.$this->filters['search'].'%');
            });
        }

        return $orders->get()->each(function($order){
            if ($order->data['orderDetails']['orders']) {
                $order->creation_date = $order->data['orderDetails']['orders'][0]['renderedOrderTimestamp'];
            } elseif ($order->data['orderDetails']['orderDetailV1']) {
                $orderDetailV1 = json_decode($order->data['orderDetails']['orderDetailV1']['value'], true);
                $order->creation_date = $orderDetailV1['created_at_formatted']?? '';
                $currency_symbol = $orderDetailV1['currency_symbol']?? '';
            } else {
                $order->creation_date = '';
            }

            foreach ($order->total as $total) {
                if (isset($currency_symbol)) {
                    $total['amount'] = sprintf('%s%d', $currency_symbol, $total['amount']);
                }
                if (str_contains($total['label'], 'Item price') || str_contains($total['label'], 'Subtotal')) {
                    $order->subtotal = $total['amount'];
                }
                if (str_contains($total['label'], 'Delivery')) {
                    $order->delivery = $total['amount'];
                }
                if (str_contains($total['label'], 'Platform fee')) {
                    $order->platform_fee = $total['amount'];
                }
                if (str_contains($total['label'], 'Your earnings')) {
                    $order->total = $total['amount'];
                }
            }
        });
    }

    public function render()
    {
        return view('livewire.order-table')
            ->with('shops', $this->getShops())
            ->with('orders', $this->getOrders());
    }
}
