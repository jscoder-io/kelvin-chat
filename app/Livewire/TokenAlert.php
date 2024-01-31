<?php

namespace App\Livewire;

use App\Models\Shop;
use App\Models\Token;
use Livewire\Component;

class TokenAlert extends Component
{
    public function render()
    {
        if (auth()->user()->role > 1) {
            $shops = Shop::whereIn('id', auth()->user()->shop)->get()
                ->map(function ($shop) {
                    return $shop->id;
                })->all();
        } else {
            $shops = Shop::get()->map(function ($shop) {
                    return $shop->id;
                })->all();
        }
        $shopIds = count($shops) > 0 ? $shops : [0];

        $tokens = Token::where('status', 'invalid')
            ->whereIn('shop_id', $shopIds)
            ->limit(3)->get();

        return view('livewire.token-alert')
            ->with('tokens', $tokens);
    }
}
