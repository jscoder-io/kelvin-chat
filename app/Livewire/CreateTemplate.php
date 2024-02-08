<?php

namespace App\Livewire;

use App\Livewire\Forms\CreateTemplateForm;
use App\Models\Shop;
use LivewireUI\Modal\ModalComponent;

class CreateTemplate extends ModalComponent
{
    public CreateTemplateForm $form;

    public $shops = [];

    protected function getShops()
    {
        $shops = Shop::latest();
        if (auth()->user()->role > 1) {
            $shops->whereIn('id', auth()->user()->shop);
        }

        return $shops->get();
    }

    public function save()
    {
        $this->form->shop = array_values($this->shops);

        $this->form->store();

        session()->flash('message', 'Template is successfully created.');

        $this->redirectRoute('template');
    }

    public function selectOption($shopId)
    {
        if (in_array($shopId, $this->shops)) {
            $key = array_search($shopId, $this->shops);
            unset($this->shops[$key]);
        } else {
            $this->shops[] = (string) $shopId;
        }
        $this->skipRender();
    }

    public function render()
    {
        return view('livewire.create-template')
            ->with('shopList', $this->getShops());
    }
}
