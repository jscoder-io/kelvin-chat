<?php

namespace App\Livewire;

use App\Jobs\UpdateInbox;
use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class MessageTable extends Component
{
    public $filters = [
        'shop' => 0,
        'unread' => 0,
        'search' => '',
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
        } elseif (auth()->user()->role > 1) {
            $messages->whereIn('shop_id', auth()->user()->shop);
        }
        if ($this->filters['unread']) {
            $messages->where('unread_count', '>', 0);
        }
        if ($this->filters['search']) {
            $messages->where(function ($query) {
                $query->whereIn('id', function (Builder $subquery) {
                    $subquery->select('message_id')->from('chat')
                        ->whereRaw('`chat`.`message` LIKE \'%'.$this->filters['search'].'%\'');
                });
                $query->orWhere('username', 'LIKE', '%'.$this->filters['search'].'%');
            });
        }

        return $messages->get()->filter(function ($message) {
            if ($message->shop) {
                return true;
            }
            return false;
        });
    }

    protected function isFiltered()
    {
        foreach ($this->filters as $key => $value) {
            if ($this->filters[$key]) {
                return true;
            }
        }
        return false;
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
        //if (! $this->isFiltered()) {
        //    UpdateInbox::dispatch();
        //}

        return view('livewire.message-table')
            ->with('shops', $this->getShops())
            ->with('messages', $this->getMessages());
    }
}
