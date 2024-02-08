<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Form;

class EditUserForm extends Form
{
    public ?User $user;

    public $name = '';
    public $email = '';
    public $role = '';
    public $shop = [];
    public $preset = '';
    public $change_password = false;
    public $password = '';
    public $password_confirmation = '';

    public function setUser(User $user)
    {
        $this->user = $user;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->shop = $user->shop ?? [];
        $this->preset = $user->preset;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:225',
            'email' => 'required|string|email:rfc,dns|max:225',
            'role' => 'required|integer',
            'shop' => 'required_if:role,2|array',
            'preset' => 'required|string|max:225',
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
            'role.required' => 'The role is required.',
            'shop.required_if' => 'The shop is required.',
            'preset.required' => 'The preset is required.',
            'preset.max' => 'The preset is too long.',
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
            'role' => $this->role,
            'shop' => ! empty($this->shop) ? $this->shop : null,
            'preset' => $this->preset,
        ]);

        if ($this->change_password) {
            $this->user->password = Hash::make($this->password);
        }

        $this->user->save();
    }
}
