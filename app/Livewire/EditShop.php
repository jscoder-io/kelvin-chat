<?php

namespace App\Livewire;

use App\Livewire\Forms\EditShopForm;
use App\Models\Shop;
use LivewireUI\Modal\ModalComponent;

class EditShop extends ModalComponent
{
    public EditShopForm $form;

    public function mount(Shop $shop)
    {
        $this->form->setShop($shop);
    }

    public function save()
    {
        $this->form->update();

        session()->flash('message', 'Shop is successfully updated.');

        $this->redirectRoute('shop');
    }

    public function render()
    {
        return view('livewire.edit-shop');
    }
}
