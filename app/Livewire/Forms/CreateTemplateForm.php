<?php

namespace App\Livewire\Forms;

use App\Models\Template;
use Livewire\Form;

class CreateTemplateForm extends Form
{
    public $message = '';
    public $shop = [];

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

    public function store()
    {
        $this->validate();

        tap((new Template)->forceFill([
            'message' => $this->message,
            'shop' => ! empty($this->shop) ? $this->shop : null,
        ]))->save();
    }
}
