<?php

namespace App\Livewire\Forms;

use App\Marketplace\Factory as MarketplaceFactory;
use App\Models\Message;
use Livewire\Form;

class EditPriceForm extends Form
{
    public ?Message $message;

    public $price;

    public function setMessage(Message $message)
    {
        $this->message = $message;

        $this->price = $message->data['product']['price_formatted'];
    }

    public function rules()
    {
        return [
            'price' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be numeric.',
        ];
    }

    public function update()
    {
        $this->validate();

        $marketplace = MarketplaceFactory::create($this->message->shop);

        // Save via API
        $marketplace->updatePrice($this->message->data['product']['id'], $this->price);

        // Pull offer data
        $price_formatted = $this->message->price_formatted;
        $data = $this->message->data;

        $offerDetail = $marketplace->offerDetail($this->message->chat_id);
        if (! empty($offerDetail)) {
            $price_formatted = $offerDetail['price_formatted'];
            $data = $offerDetail['data'];
        }

        $this->message->update([
            'price_formatted' => $price_formatted,
            'data' => $data,
        ]);
    }
}
