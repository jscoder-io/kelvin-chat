<?php

namespace App\Livewire;

use App\Models\Message;
use LivewireUI\Modal\ModalComponent;

class ViewOrder extends ModalComponent
{
    public $message;

    public function mount(Message $message)
    {
        $this->message = $message;
    }

    public function render()
    {
        return view('livewire.view-order');
    }
}
