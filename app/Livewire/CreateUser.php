<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateUserForm;
use LivewireUI\Modal\ModalComponent;

class CreateUser extends ModalComponent
{
    public CreateUserForm $form;

    public function save()
    {
        $this->form->store();

        session()->flash('message', 'User is successfully created.');

        $this->redirectRoute('user');
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
