<?php

namespace App\Livewire;

use App\Livewire\CheckTokenShop;
use App\Livewire\Forms\EditTokenForm;
use App\Models\Shop;
use LivewireUI\Modal\ModalComponent;

class EditToken extends ModalComponent
{
    public EditTokenForm $form;

    public function mount(Shop $shop)
    {
        $this->form->setShop($shop);
    }

    public function save()
    {
        $this->form->update();

        $this->dispatch('openModal', component: 'check-token-shop', arguments: ['shop' => $this->form->shop->id, 'close' => true]);
    }

    public function render()
    {
        return view('livewire.edit-token');
    }
}
