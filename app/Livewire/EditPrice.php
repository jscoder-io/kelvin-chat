<?php

namespace App\Livewire;

use App\Livewire\Forms\EditPriceForm;
use App\Models\Message;
use LivewireUI\Modal\ModalComponent;

class EditPrice extends ModalComponent
{
    public EditPriceForm $form;

    public function mount(Message $message)
    {
        $this->form->setMessage($message);
    }

    public function save()
    {
        $this->form->update();

        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.edit-price');
    }
}
