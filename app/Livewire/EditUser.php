<?php

namespace App\Livewire;

use App\Livewire\Forms\EditUserForm;
use App\Models\User;
use LivewireUI\Modal\ModalComponent;

class EditUser extends ModalComponent
{
    public EditUserForm $form;

    public $shops;

    public function mount(User $user)
    {
        $this->form->setUser($user);

        $this->shops = $user->shop ?? [];
    }

    public function save()
    {
        $this->form->shop = array_values($this->shops);

        $this->form->update();

        session()->flash('message', 'User is successfully updated.');

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
        return view('livewire.edit-user');
    }
}
