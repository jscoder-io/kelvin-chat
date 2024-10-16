<?php

namespace App\Console\Commands;

use App\Jobs\UpdateChat;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Console\Command;

class UnarchivedMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:unarchived';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To unarchived message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Message::where('is_archived', 1)->get()->each(function($message) {
            if(! $message->shop){
                return;
            }

            UpdateChat::dispatch($message);

            $message->refresh();

            $collection = Chat::where('message_id', $message->id)->latest()->get();

            foreach ($collection as $chat) {
                if (($message->data['state'] == 'O')
                    && ($chat->type == 'MESG')
                    && ($chat->custom_type == 'MAKE_OFFER')
                ) {
                    $message->is_archived = 0;
                    $message->save();

                    break;
                }
            }
        });
    }
}
