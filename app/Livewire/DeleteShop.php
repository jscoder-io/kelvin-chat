<?php

namespace App\Livewire;

use App\Models\Shop;
use LivewireUI\Modal\ModalComponent;

class DeleteShop extends ModalComponent
{
    public $shop;

    public function mount(Shop $shop)
    {
        $this->shop = $shop;
    }

    public function delete()
    {
        $this->shop->delete();

        session()->flash('message', 'Shop is successfully deleted.');

        $this->redirectRoute('shop');
    }

    public function render()
    {
        return view('livewire.delete-shop');
    }
}
