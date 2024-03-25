<?php

namespace App\Console\Commands;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Order;
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
    protected $description = 'Update order list';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Shop::oldest('updated_at')->limit(1)->get()->each(function ($shop) {
            $shop->touchQuietly();
            $marketplace = MarketplaceFactory::create($shop);

            $orders = [];
            foreach (['to_start', 'in_progress', 'completed', 'returns', 'cancelled'] as $tab) {
                $result = $marketplace->orders($tab);
                if ($result['success'] === true) {
                    foreach ($result['data']['orderHistory'] as $order) {
                        $orders[] = [
                            ['identifier' => $order['linkedOrderId'], 'shop_id' => $shop->id],
                            ['tab' => $tab],
                        ];
                    }
                }
            }

            // Update or create
            foreach ($orders as $attributes) {
                $where = array_shift($attributes);
                $fillable = array_shift($attributes);
                Order::updateOrCreate($where, $fillable);
            }
        });
    }
}
