<?php

namespace App\Livewire;

use App\Jobs\UpdateInbox;
use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Filter;
use App\Models\Message;
use App\Models\Shop;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class MessageTable extends Component
{
    public $filters = [
        'shop' => 0,
        'type' => 0,
        'search' => '',
    ];

    public $selected = [];

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

        $isArchived = 0;
        if ($this->filters['type'] == 1) {
            $messages->where('unread_count', '>', 0);
        } elseif ($this->filters['type'] == 2) {
            $isArchived = 1;
        }

        if ($this->filters['search']) {
            $messages->where(function ($query) {
                $query->whereIn('id', function (Builder $subquery) {
                    $subquery->select('message_id')->from('chat')
                        ->whereRaw('`chat`.`message` LIKE \'%'.$this->filters['search'].'%\'');
                });
                $query->orWhereIn('id', function (Builder $subquery) {
                    $subquery->select('message_id')->from('orders')
                        ->whereRaw('`orders`.`identifier` LIKE \'%'.$this->filters['search'].'%\'')
                        ->orWhereRaw('`orders`.`customer` LIKE \'%'.$this->filters['search'].'%\'')
                        ->orWhereRaw('`orders`.`contact` LIKE \'%'.$this->filters['search'].'%\'')
                        ->orWhereRaw('`orders`.`address` LIKE \'%'.$this->filters['search'].'%\'');
                });
                $query->orWhere('username', 'LIKE', '%'.$this->filters['search'].'%');
                $query->orWhere('product_title', 'LIKE', '%'.$this->filters['search'].'%');
            });
            if ($isArchived) {
                $messages->where('is_archived', $isArchived);
            }
        } else {
            $messages->where('is_archived', $isArchived);
        }
        $messages->where('is_seller', '!=', 1);

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

    protected function saveFiltersIntoDb()
    {
        $filter = Filter::firstOrNew(['user_id' => auth()->user()->id]);

        $filter->fill($this->filters)->save();
    }

    protected function getFiltersFromDb()
    {
        $filter = Filter::where('user_id', auth()->user()->id)
            ->get(['shop', 'type', 'search'])
            ->first();

        if (! $filter) {
            Filter::create(['user_id' => auth()->user()->id]);
            return $this->filters;
        }

        return array_merge($this->filters, $filter->toArray());
    }

    public function mount()
    {
        $this->filters = $this->getFiltersFromDb();
    }

    public function updatedFilters($value, $key)
    {
        $this->reset('offset');
        $this->reset('selected');
    }

    public function prev()
    {
        $this->offset -= 30;
        $this->reset('selected');
    }

    public function next()
    {
        $this->offset += 30;
        $this->reset('selected');
    }

    public function chat($id)
    {
        $this->redirectRoute('chat', ['id' => $id]);
    }

    public function archive()
    {
        Message::whereIn('id', $this->selected)->get()->each(function ($message) {
            $message->unread_count_snapshot = $message->unread_count;
            $message->is_archived = 1;
            $message->save();
        });
        $this->reset('selected');
    }

    public function render()
    {
        //if (! $this->isFiltered()) {
        //    UpdateInbox::dispatch();
        //}

        $this->saveFiltersIntoDb();

        return view('livewire.message-table')
            ->with('shops', $this->getShops())
            ->with('messages', $this->getMessages());
    }
}
