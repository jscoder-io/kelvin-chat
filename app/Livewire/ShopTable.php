<?php

namespace App\Livewire;

use App\Models\Shop;
use Livewire\Component;

class ShopTable extends Component
{
    public function render()
    {
        $shops = Shop::latest();
        if (auth()->user()->role > 1) {
            $shops->whereIn('id', auth()->user()->shop);
        }

        return view('livewire.shop-table')
            ->with('shops', $shops->get());
    }
}
