<?php

namespace App\Console\Commands;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Console\Command;

class UpdateMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:inbox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update inbox';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Shop::latest()->get()->each(function ($shop) {
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
                        'price_formatted' => $message['price_formatted'],
                        'product_url' => $message['product_url'],
                        'channel_url' => $message['channel_url'],
                        'latest_message' => $message['latest_message'],
                        'unread_count' => $message['unread_count'],
                        //'unread_count_snapshot' => $message['unread_count'],
                        'latest_created' => $message['latest_created'],
                        'data' => $message['data'],
                    ]);
                }

                Message::where('unread_count', '>', 'unread_count_snapshot')
                    ->where('is_archived', 1)
                    ->get()->each(function ($message) {
                        $message->is_archived = 0;
                        //$message->unread_count_snapshot = 0;
                        $message->save();
                    });
            }
        });
    }
}
