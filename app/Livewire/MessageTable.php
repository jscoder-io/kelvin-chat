<?php

namespace App\Livewire;

use App\Jobs\UpdateInbox;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class MessageTable extends Component
{
    public $filters = [
        'shop' => 0
    ];

    protected function getShops()
    {
        $shops = Shop::latest();
        if (auth()->user()->role > 1) {
            $shops->whereIn('id', auth()->user()->shop);
        }

        return $shops->get();
    }

    protected function getMessages()
    {
        $messages = Message::orderBy('latest_created', 'desc');
        if ($this->filters['shop']) {
            $messages->where('shop_id', $this->filters['shop']);
        }

        return $messages->get();
    }

    protected function diffForHumansLatestCreatedAt($date)
    {
        return (string) Date::createFromTimeString($date)
            ->diffForHumans([ 'parts' => 3, 'short' => true ]);
    }

    public function chat($id)
    {
        $this->redirectRoute('chat', ['id' => $id]);
    }

    public function render()
    {
        UpdateInbox::dispatch();

        return view('livewire.message-table')
            ->with('shops', $this->getShops())
            ->with('messages', $this->getMessages());
    }
}
