<?php

namespace App\Livewire;

use App\Livewire\Forms\EditUserForm;
use App\Models\User;
use LivewireUI\Modal\ModalComponent;

class EditUser extends ModalComponent
{
    public EditUserForm $form;

    public function mount(User $user)
    {
        $this->form->setUser($user);
    }

    public function save()
    {
        $this->form->update();

        session()->flash('message', 'User is successfully updated.');

        $this->redirectRoute('user');
    }

    public function render()
    {
        return view('livewire.edit-user');
    }
}
