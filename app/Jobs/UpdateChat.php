<?php

namespace App\Jobs;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateChat implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->fetch($this->message->shop);
    }

    protected function fetch(Shop $shop)
    {
        $marketplace = MarketplaceFactory::create($shop);
        $data = $marketplace->chat($this->message);

        if ($data['success']) {
            foreach ($data['messages'] as $message) {
                Chat::updateOrCreate([
                    'message_id' => $this->message->id,
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
}
