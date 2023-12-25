<?php

namespace App\Livewire\Forms;

use App\Models\Shop;
use App\Models\Token;
use Livewire\Form;

class CreateShopForm extends Form
{
    public $name = '';
    public $marketplace = '';
    public $token = [];

    protected $label = [
        'jwt-token' => 'Jwt Token',
        'session-key' => 'Session Key',
    ];

    public function rules()
    {
        return [
            'name' => 'required|string|max:225',
            'marketplace' => 'required|string|max:225',
            'token.jwt-token' => 'required_if:marketplace,carousell.sg|string|max:225',
            'token.session-key' => 'required_if:marketplace,carousell.sg|string|max:225',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'name.max' => 'The name is too long.',
            'marketplace.required' => 'The marketplace is required.',
            'marketplace.max' => 'The marketplace is too long.',
            'token.jwt-token.required_if' => 'The jwt token is required.',
            'token.jwt-token.max' => 'The jwt token is too long.',
            'token.session-key.required_if' => 'The session key is required.',
            'token.session-key.max' => 'The session key is too long.',
        ];
    }

    public function store()
    {
        $this->validate();

        $shop = Shop::create($this->only(['name', 'marketplace']));

        foreach ($this->token as $key => $value) {
            $token = Token::create([
                'label' => $this->label[$key] ?? 'Unknown',
                'key' => $key,
                'value' => $value,
                'shop_id' => $shop->id,
            ]);
        }
    }
}
