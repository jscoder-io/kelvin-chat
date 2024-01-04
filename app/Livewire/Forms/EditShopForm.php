<?php

namespace App\Livewire\Forms;

use App\Models\Shop;
use App\Models\Token;
use Livewire\Form;

class EditShopForm extends Form
{
    public ?Shop $shop;

    public $name = '';
    public $marketplace = '';
    public $token = [];

    protected $label = [
        'jwt-token' => 'Jwt Token',
        'session-key' => 'Session Key',
        'csrf-token' => 'Csrf Token',
        '_csrf' => '_csrf',
    ];

    public function setShop(Shop $shop)
    {
        $this->shop = $shop;

        $this->name = $shop->name;
        $this->marketplace = $shop->marketplace;

        foreach ($shop->tokens as $token) {
            $this->token[$token->key] = $token->value;
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:225',
            'marketplace' => 'required|string|max:225',
            'token.jwt-token' => 'required_if:marketplace,carousell.sg|string|max:225',
            'token.session-key' => 'required_if:marketplace,carousell.sg|string|max:225',
            'token.csrf-token' => 'required_if:marketplace,carousell.sg|string|max:225',
            'token._csrf' => 'required_if:marketplace,carousell.sg|string|max:225',
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
            'token.csrf-token.required_if' => 'The csrf token is required.',
            'token.csrf-token.max' => 'The csrf token is too long.',
            'token._csrf.required_if' => 'The _csrf is required.',
            'token._csrf.max' => 'The _csrf is too long.',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->shop->update(['name' => $this->name]);

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
