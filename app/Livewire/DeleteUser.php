<?php

namespace App\Livewire;

use App\Models\User;
use LivewireUI\Modal\ModalComponent;

class DeleteUser extends ModalComponent
{
    public $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function delete()
    {
        $this->user->delete();

        session()->flash('message', 'User is successfully deleted.');

        $this->redirectRoute('user');
    }

    public function render()
    {
        return view('livewire.delete-user');
    }
}
