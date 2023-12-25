<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Form;

class EditProfileForm extends Form
{
    public ?User $user;

    public $name = '';
    public $email = '';
    public $change_password = false;
    public $password = '';
    public $password_confirmation = '';

    public function setUser(User $user)
    {
        $this->user = $user;

        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:225',
            'email' => 'required|string|email:rfc,dns|max:225',
            'password' => 'required_if:change_password,true|string|min:6|max:225|confirmed',
            'password_confirmation' => 'required_if:change_password,true|string|max:225',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'name.max' => 'The name is too long.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email is invalid.',
            'password.required_if' => 'The password is required.',
            'password_confirmation.required_if' => 'The password confirmation is required.',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->change_password) {
            $this->user->password = Hash::make($this->password);
        }

        $this->user->save();
    }
}
