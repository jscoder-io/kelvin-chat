<?php

namespace App\Livewire;

use App\Jobs\AcceptOffer;
use App\Jobs\AcceptOrder;
use App\Jobs\CancelOrder;
use App\Jobs\DeclineOffer;
use App\Jobs\SendText;
use App\Jobs\UpdateChat;
use App\Models\Chat;
use App\Models\ChatQueue;
use App\Models\Message;
use App\Models\Template;
use App\Models\Token;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
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

    protected function orderActions()
    {
        if (! empty($this->message->order_data)
            && $this->message->order_data['state'] == 'FULFILLMENT_ORDER_STATE_INIT'
        ) {
            return true;
        }
    }

    protected function isCsrfTokenValid()
    {
        $token = Token::where('key', 'csrf-token')
            ->where('shop_id', $this->message->shop_id)
            ->first();

        $csrf_token = true;
        if ($token) {
            $csrf_token = $token->status == 'valid';
        }

        $token = Token::where('key', '_csrf')
            ->where('shop_id', $this->message->shop_id)
            ->first();

        $_csrf = true;
        if ($token) {
            $_csrf = $token->status == 'valid';
        }

        return $csrf_token && $_csrf;
    }

    protected function sendChatQueue()
    {
        $chat = ChatQueue::where('chat_id', $this->message->id)
            ->where('is_sent', 0)->orderBy('created_at', 'asc')
            ->get('message')->map(function ($model) {
                return $model->message;
            })->implode("\n");

        if ($chat) {
            DB::table('chat_queue')
                ->where('chat_id', $this->message->id)
                ->update(['is_sent' => true]);

            SendText::dispatch($this->message->shop, $this->message, $chat);
        }
    }

    public function mount(Message $message)
    {
        $this->message = $message;
    }

    public function accept()
    {
        AcceptOffer::dispatch($this->message);

        if (! $this->isCsrfTokenValid()) {
            $this->dispatch('openModal', component: 'edit-token', arguments: ['shop' => $this->message->shop_id]);
        }
    }

    public function decline()
    {
        DeclineOffer::dispatch($this->message);

        if (! $this->isCsrfTokenValid()) {
            $this->dispatch('openModal', component: 'edit-token', arguments: ['shop' => $this->message->shop_id]);
        }
    }

    public function acceptOrder()
    {
        AcceptOrder::dispatch($this->message);

        if (! $this->isCsrfTokenValid()) {
            $this->dispatch('openModal', component: 'edit-token', arguments: ['shop' => $this->message->shop_id]);
            return;
        }

        $this->message->update(['is_cancelled' => 0]);
    }

    public function cancelOrder()
    {
        CancelOrder::dispatch($this->message);

        if (! $this->isCsrfTokenValid()) {
            $this->dispatch('openModal', component: 'edit-token', arguments: ['shop' => $this->message->shop_id]);
            return;
        }

        $this->message->update(['is_cancelled' => 1]);
    }

    public function send()
    {
        //$this->resetValidation('text');

        if (! isset($this->text) || $this->text == '') {
            //$this->addError('text', 'Text message is required.');
            return;
        }

        //SendText::dispatch($this->message->shop, $this->message, $this->text);

        ChatQueue::create([
            'chat_id' => $this->message->id,
            'message' => $this->text,
        ]);

        $this->reset('text');
        $this->skipRender();
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
        $this->sendChatQueue();

        UpdateChat::dispatch($this->message);

        return view('livewire.chat-box')
            ->with('rows', $this->getChat())
            ->with('templates', $this->getTemplate());
    }
}
