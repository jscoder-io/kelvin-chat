<?php

namespace App\Marketplace;

use App\Models\Shop;

class Factory
{
    public static function create(Shop $shop)
    {
        switch ($shop->marketplace) {
            case 'carousell.sg':
                return new Carousell($shop);
        }
    }
}