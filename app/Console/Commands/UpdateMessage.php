<?php

namespace App\Console\Commands;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Chat;
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
    protected $signature = 'message:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update inbox and chat';

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
                        'latest_created' => $message['latest_created'],
                        'data' => $message['data'],
                    ]);
                }
            }
        });

        Message::orderBy('latest_created', 'desc')->get()->each(function ($msg) {
            if ($msg->shop) {
                $marketplace = MarketplaceFactory::create($msg->shop);
                $data = $marketplace->chat($msg);

                if ($data['success']) {
                    foreach ($data['messages'] as $message) {
                        Chat::updateOrCreate([
                            'message_id' => $msg->id,
                            'chat_id' => $message['chat_id'],
                        ], [
                            'message' => $message['message'],
                            'type' => $message['type'],
                            'custom_type' => $message['custom_type'],
                            'user' => $message['user'],
                            'data' => $message['data'],
                            'file' => $message['file'],
                            'created_at' => $message['created_at'],
                        ]);

                        if ($message['type'] == 'MESG' && $message['custom_type'] == 'DELETED') {
                            $row = Chat::where('chat_id', $message['data']['deleted_message_id'])->first();
                            if ($row) {
                                $row->delete();
                            }
                        }
                    }
                }
            }
        });
    }
}
