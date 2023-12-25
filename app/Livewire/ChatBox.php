<?php

namespace App\Livewire;

use App\Jobs\SendText;
use App\Jobs\UpdateChat;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class ChatBox extends Component
{
    public $text;
    public $message;

    public function mount(Message $message)
    {
        $this->message = $message;
    }

    public function send()
    {
        if (! isset($this->text) || $this->text == '') {
            $this->addError('text', 'Text message is required.');
        }

        SendText::dispatch($this->message->shop, $this->message, $this->text);

        unset($this->text);
    }

    public function diffForHumansLatestCreatedAt($date)
    {
        return (string) Date::createFromTimeString($date)
            ->diffForHumans([ 'parts' => 3, 'short' => true ]);
    }

    public function render()
    {
        UpdateChat::dispatch($this->message);

        return view('livewire.chat-box')
            ->with('rows', Chat::where('message_id', $this->message->id)->oldest()->get());
    }
}
