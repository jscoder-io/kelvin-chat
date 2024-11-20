<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateShopForm;
use LivewireUI\Modal\ModalComponent;

class CreateShop extends ModalComponent
{
    public CreateShopForm $form;

    public function save()
    {
        $this->form->store();

        $this->forceClose()->closeModal();

        //session()->flash('message', 'Shop is successfully created.');

        //$this->redirectRoute('shop');
    }

    public function render()
    {
        return view('livewire.create-shop');
    }
}
