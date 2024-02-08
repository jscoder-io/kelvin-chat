<?php

namespace App\Livewire\Forms;

use App\Models\Template;
use Livewire\Form;

class EditTemplateForm extends Form
{
    public ?Template $template;

    public $message = '';
    public $shop = [];

    public function setTemplate(Template $template)
    {
        $this->template = $template;

        $this->message = $template->message;
        $this->shop = $template->shop ?? [];
    }

    public function rules()
    {
        return [
            'message' => 'required|string|max:225',
            'shop' => 'required|array',
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'The message is required.',
            'message.max' => 'The message is too long.',
            'shop.required' => 'The shop is required.',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->template->forceFill([
            'message' => $this->message,
            'shop' => ! empty($this->shop) ? $this->shop : null,
        ]);

        $this->template->save();
    }
}
