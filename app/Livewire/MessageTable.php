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

    public $offset = 0;

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
        $messages = Message::orderBy('latest_created', 'desc')->offset($this->offset)->limit(30);
        if ($this->filters['shop']) {
            $messages->where('shop_id', $this->filters['shop']);
        } elseif (auth()->user()->role > 1) {
            $shops = Shop::whereIn('id', auth()->user()->shop)->get()
                ->map(function ($shop) {
                    return $shop->id;
                })->all();
            $shopIds = count($shops) > 0 ? $shops : [0];
            $messages->whereIn('shop_id', $shopIds);
        } else {
            $shops = Shop::get()->map(function ($shop) {
                return $shop->id;
            })->all();
            $shopIds = count($shops) > 0 ? $shops : [0];
            $messages->whereIn('shop_id', $shopIds);
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

        return $messages->get();
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

    public function updatedFilters($value, $key)
    {
        $this->reset('offset');
    }

    public function prev()
    {
        $this->offset -= 30;
    }

    public function next()
    {
        $this->offset += 30;
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
