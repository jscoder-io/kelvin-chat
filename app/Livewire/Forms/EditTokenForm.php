<?php

namespace App\Livewire\Forms;

use App\Models\Shop;
use App\Models\Token;
use Livewire\Form;

class EditTokenForm extends Form
{
    public ?Shop $shop;

    public $name = '';
    public $marketplace = '';
    public $token = [];
    public $valid = [];

    protected $label = [
        'jwt-token' => 'Jwt Token',
        'session-key' => 'Session Key',
        'csrf-token' => 'Csrf Token',
        '_csrf' => '_csrf',
    ];

    public function rules()
    {
        return [
            'token.jwt-token' => 'required_if:marketplace,carousell.sg|string|max:225',
            'token.session-key' => 'required_if:marketplace,carousell.sg|string|max:225',
            'token.csrf-token' => 'required_if:marketplace,carousell.sg|string|max:225',
            'token._csrf' => 'required_if:marketplace,carousell.sg|string|max:225',
        ];
    }

    public function messages()
    {
        return [
            'token.jwt-token.required_if' => 'The jwt token is required.',
            'token.jwt-token.max' => 'The jwt token is too long.',
            'token.session-key.required_if' => 'The session key is required.',
            'token.session-key.max' => 'The session key is too long.',
            'token.csrf-token.required_if' => 'The csrf token is required.',
            'token.csrf-token.max' => 'The csrf token is too long.',
            'token._csrf.required_if' => 'The _csrf is required.',
            'token._csrf.max' => 'The _csrf is too long.',
        ];
    }

    public function setShop(Shop $shop)
    {
        $this->shop = $shop;

        $this->name = $shop->name;
        $this->marketplace = $shop->marketplace;

        foreach ($shop->tokens as $token) {
            $this->token[$token->key] = $token->value;
            $this->valid[$token->key] = ($token->status == 'valid') ? true : false;
        }
    }

    public function update()
    {
        $this->validate();

        foreach ($this->token as $key => $value) {
            $token = Token::where('key', $key)
                ->where('shop_id', $this->shop->id)
                ->first();

            if ($token) {
                $token->fill(['value' => $value])->save();
            } else {
                Token::create([
                    'label' => $this->label[$key] ?? 'Unknown',
                    'key' => $key,
                    'value' => $value,
                    'shop_id' => $this->shop->id,
                ]);
            }
        }
    }
}
