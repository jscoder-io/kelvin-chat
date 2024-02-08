<?php

namespace App\Livewire;

use App\Livewire\Forms\EditTemplateForm;
use App\Models\Shop;
use App\Models\Template;
use LivewireUI\Modal\ModalComponent;

class EditTemplate extends ModalComponent
{
    public EditTemplateForm $form;

    public $shops = [];

    protected function getShops()
    {
        $shops = Shop::latest();
        if (auth()->user()->role > 1) {
            $shops->whereIn('id', auth()->user()->shop);
        }

        return $shops->get();
    }

    public function mount(Template $template)
    {
        $this->form->setTemplate($template);

        $this->shops = $template->shop ?? [];
    }

    public function save()
    {
        $this->form->shop = array_values($this->shops);

        $this->form->update();

        session()->flash('message', 'Template is successfully updated.');

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
        return view('livewire.edit-template')
            ->with('shopList', $this->getShops());
    }
}
