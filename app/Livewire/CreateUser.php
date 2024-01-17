<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateUserForm;
use LivewireUI\Modal\ModalComponent;

class CreateUser extends ModalComponent
{
    public CreateUserForm $form;

    public $shops = [];

    public function save()
    {
        $this->form->shop = array_values($this->shops);

        $this->form->store();

        session()->flash('message', 'User is successfully created.');

        $this->redirectRoute('user');
    }

    public function selectOption($shopId)
    {
        if (in_array($shopId, $this->shops)) {
            $key = array_search($shopId, $this->shops);
            unset($this->shops[$key]);
        } else {
            $this->shops[] = (string) $shopId;
        }
        $this->skipRender();
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
