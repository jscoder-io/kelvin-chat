<?php

namespace App\Livewire;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Jobs\CsrfToken;
use App\Jobs\UpdateChat;
use App\Jobs\UpdateInbox;
use App\Models\Message;
use App\Models\Shop;
use LivewireUI\Modal\ModalComponent;

class CheckTokenAllShop extends ModalComponent
{
    public $shops;

    public function mount()
    {
        $this->shops = $this->getCollection();
    }

    public function getCollection()
    {
        $shops = Shop::latest();
        if (auth()->user()->role > 1) {
            $shops->whereIn('id', auth()->user()->shop);
        }

        return $shops->get();
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

        /*foreach ($this->shops as $shop) {
            foreach ($shop->tokens as $token) {
                $token->fill([
                    'status' => $this->checkToken($shop->marketplace, $token->key) ? 'valid' : 'invalid'
                ])->save();
            }
        }

        UpdateInbox::dispatch();

        $this->shops = $this->getCollection();

        $this->shops->each(function ($shop) {
            $message = Message::where('shop_id', $shop->id)->first();
            if ($message) {
                UpdateChat::dispatch($message);
            }
            CsrfToken::dispatch($shop);
        });*/

        $this->shops = $this->getCollection();

        $this->shops->each(function ($shop) {
            $marketplace = MarketplaceFactory::create($shop);
            $marketplace->scan();
        });

        sleep(2);
    }

    public function completed()
    {
        $this->forceClose()->closeModal();

        //session()->flash('message', 'Token checking is done.');

        //$this->redirectRoute('shop');
    }

    public function render()
    {
        return view('livewire.check-token-all-shop');
    }
}
