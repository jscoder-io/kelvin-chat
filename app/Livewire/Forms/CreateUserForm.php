<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Livewire\Form;

class CreateUserForm extends Form
{
    public $name = '';
    public $email = '';
    public $role = '';
    public $shop = [];
    public $password = '';
    public $password_confirmation = '';

    public function rules()
    {
        return [
            'name' => 'required|string|max:225',
            'email' => 'required|string|email:rfc,dns|max:225',
            'role' => 'required|integer',
            'shop' => 'required_if:role,2|array',
            'password' => 'required|string|min:6|max:225|confirmed',
            'password_confirmation' => 'required|string|max:225',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'name.max' => 'The name is too long.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email is invalid.',
            'role.required' => 'The role is required.',
            'shop.required_if' => 'The shop is required.',
            'password.required' => 'The password is required.',
            'password_confirmation.required' => 'The password confirmation is required.',
        ];
    }

    public function store()
    {
        $this->validate();

        tap((new User)->forceFill([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'email_verified_at' => Date::now(),
            'role' => $this->role,
            'shop' => ! empty($this->shop) ? $this->shop : null,
        ]))->save();
    }
}
