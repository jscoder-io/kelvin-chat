<?php

namespace App\Livewire;

use App\Jobs\AcceptOffer;
use App\Jobs\DeclineOffer;
use App\Jobs\SendText;
use App\Jobs\UpdateChat;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Template;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class ChatBox extends Component
{
    public $text;
    public $message;

    protected function getChat($oldest = true)
    {
        $chat = Chat::where('message_id', $this->message->id);

        return $oldest ? $chat->oldest()->get() : $chat->latest()->get();
    }

    protected function getTemplate()
    {
        return Template::latest()->get()->filter(function ($template) {
            if (in_array($this->message->shop_id, $template->shop)) {
                return true;
            }
            return false;
        });
    }

    protected function makeOfferActions()
    {
        foreach ($this->getChat(false) as $chat) {
            if (($this->message->data['state'] == 'O')
                && ($chat->type == 'MESG')
                && ($chat->custom_type == 'MAKE_OFFER')
            ) {
                return $chat;
            }
        }
        return false;
    }

    public function mount(Message $message)
    {
        $this->message = $message;
    }

    public function accept()
    {
        AcceptOffer::dispatch($this->message);
    }

    public function decline()
    {
        DeclineOffer::dispatch($this->message);
    }

    public function send()
    {
        $this->resetValidation('text');

        if (! isset($this->text) || $this->text == '') {
            $this->addError('text', 'Text message is required.');
            return;
        }

        SendText::dispatch($this->message->shop, $this->message, $this->text);

        $this->reset('text');
    }

    public function sendTemplate($id)
    {
        $template = Template::find($id);
        if ($template) {
            SendText::dispatch($this->message->shop, $this->message, $template->message);
        }
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
            ->with('rows', $this->getChat())
            ->with('templates', $this->getTemplate());
    }
}
