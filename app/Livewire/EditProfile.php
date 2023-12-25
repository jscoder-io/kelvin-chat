<?php

namespace App\Livewire;

use App\Livewire\Forms\EditProfileForm;
use App\Models\User;
use LivewireUI\Modal\ModalComponent;

class EditProfile extends ModalComponent
{
    public EditProfileForm $form;

    public function mount()
    {
        $this->form->setUser(auth()->user());
    }

    public function save()
    {
        $this->form->update();

        session()->flash('message', 'Profile is successfully updated.');

        $this->redirectRoute('dashboard');
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}
