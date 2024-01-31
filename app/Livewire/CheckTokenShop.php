<?php

namespace App\Livewire;

use App\Jobs\CsrfToken;
use App\Jobs\UpdateChat;
use App\Jobs\UpdateInbox;
use App\Models\Message;
use App\Models\Shop;
use App\Models\Token;
use LivewireUI\Modal\ModalComponent;

class CheckTokenShop extends ModalComponent
{
    public $shop;
    public $close;

    public function mount(Shop $shop, $close = false)
    {
        $this->shop = $shop;
        $this->close = $close;
    }

    public function checkToken($marketplace, $key)
    {
        return true;
    }

    public function scan()
    {
        $this->stream(
            to: 'status',
            content: '<span class="text-sm italic">Checking...</span>',
            replace: true,
        );

        /*foreach ($this->shop->tokens as $token) {
            $token->fill([
                'status' => $this->checkToken($this->shop->marketplace, $token->key) ? 'valid' : 'invalid'
            ])->save();
        }*/

        UpdateInbox::dispatch();

        $message = Message::where('shop_id', $this->shop->id)->first();
        if ($message) {
            UpdateChat::dispatch($message);
        }

        CsrfToken::dispatch($this->shop);

        sleep(2);
    }

    public function completed()
    {
        if ($this->close) {
            $this->forceClose()->closeModal();
        } else {
            session()->flash('message', 'Token checking is done.');
            $this->redirectRoute('shop');
        }
    }

    public function render()
    {
        return view('livewire.check-token-shop');
    }
}
