<?php

namespace App\Livewire;

use App\Models\Template;
use LivewireUI\Modal\ModalComponent;

class DeleteTemplate extends ModalComponent
{
    public $template;

    public function mount(Template $template)
    {
        $this->template = $template;
    }

    public function delete()
    {
        $this->template->delete();

        session()->flash('message', 'Template is successfully deleted.');

        $this->redirectRoute('template');
    }

    public function render()
    {
        return view('livewire.delete-template');
    }
}
