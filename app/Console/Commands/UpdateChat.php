<?php

namespace App\Console\Commands;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Console\Command;

class UpdateChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update chat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
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
