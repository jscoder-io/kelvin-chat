<?php

namespace App\Jobs;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateInbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $shops = Shop::latest();
        if (auth()->user()->role > 1) {
            $shops->whereIn('id', auth()->user()->shop);
        }

        $shops->get()->each(function ($shop) {
            $this->fetch($shop);
        });
    }

    protected function fetch(Shop $shop)
    {
        $marketplace = MarketplaceFactory::create($shop);
        $data = $marketplace->inbox();

        if ($data['success']) {
            foreach ($data['messages'] as $message) {
                Message::updateOrCreate([
                    'shop_id' => $shop->id,
                    'chat_id' => $message['chat_id'],
                    'buyer_id' => $message['buyer_id'],
                ], [
                    'username' => $message['username'],
                    'profile_image' => $message['profile_image'],
                    'product_title' => $message['product_title'],
                    'product_image' => $message['product_image'],
                    'channel_url' => $message['channel_url'],
                    'latest_message' => $message['latest_message'],
                    'unread_count' => $message['unread_count'],
                    'latest_created' => $message['latest_created'],
                    'data' => $message['data'],
                ]);
            }
        }
    }
}
