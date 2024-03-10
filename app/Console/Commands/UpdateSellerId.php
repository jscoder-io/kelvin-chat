<?php

namespace App\Console\Commands;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Console\Command;

class UpdateSellerId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:seller-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update shop seller id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Shop::orderBy('id', 'asc')->get()->each(function ($shop) {
            $marketplace = MarketplaceFactory::create($shop);
            $sellerIdByToken = $marketplace->getSellerIdByToken();

            Message::where('shop_id', $shop->id)->where('is_seller', 0)->limit(1)
                ->get()->each(function ($message) use ($marketplace, $sellerIdByToken) {
                    $sellerId = $marketplace->getSellerId($message);
                    $saved = Message::find($message->id);
                    if ($sellerId == $sellerIdByToken) {
                        $saved->is_seller = 2; // Real customer
                    } else {
                        $saved->is_seller = 1; // Seller act as customer
                    }
                    $saved->save();
                });
        });
    }
}
