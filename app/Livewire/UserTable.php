<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UserTable extends Component
{
    public function render()
    {
        $users = User::where('role', '>', 0)
            ->where('id', '!=', auth()->user()->id)
            ->orderBy('role', 'asc')
            ->get();

        return view('livewire.user-table')
            ->with('users', $users);
    }
}
