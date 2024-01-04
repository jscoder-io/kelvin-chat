<?php

namespace App\Livewire;

use App\Models\Token;
use Livewire\Component;

class TokenAlert extends Component
{
    public function render()
    {
        $tokens = Token::where('status', 'invalid')
            ->limit(3)->get()->filter(function ($token) {
                if ($token->shop) {
                    return true;
                }
                return false;
            });

        return view('livewire.token-alert')
            ->with('tokens', $tokens);
    }
}
