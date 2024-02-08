<?php

namespace App\Livewire;

use LivewireUI\Modal\ModalComponent;

class Help extends ModalComponent
{
    public $section;

    public static function modalMaxWidth(): string
    {
        return '7xl';
    }

    public function mount($section)
    {
        $this->section = $section;
    }

    public function render()
    {
        return view('livewire.help');
    }
}
