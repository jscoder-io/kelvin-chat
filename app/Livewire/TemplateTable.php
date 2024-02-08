<?php

namespace App\Livewire;

use App\Models\Shop;
use App\Models\Template;
use Livewire\Component;

class TemplateTable extends Component
{
    protected function getShops()
    {
        $shops = Shop::latest();
        if (auth()->user()->role > 1) {
            $shops->whereIn('id', auth()->user()->shop);
        }

        return $shops->get();
    }

    protected function getTemplates()
    {
        return Template::latest()->get();
    }

    protected function getShopList($shop)
    {
        if (is_null($shop) || $shop == '') {
            return '';
        }

        return Shop::whereIn('id', $shop)->get()
            ->map(function ($model) {
                return $model->name;
            })->implode('<br />');
    }

    public function render()
    {
        return view('livewire.template-table')
            ->with('shops', $this->getShops())
            ->with('templates', $this->getTemplates());
    }
}
