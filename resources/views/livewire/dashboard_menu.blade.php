<?php

use Livewire\Volt\Component;

new class extends Component {
    public $isCreateProductOpened = false;
    public $isProductsCreatedOpened = false;
    public $isOrdersOpened = false;

    public function open($target) {
        switch ($target) {
            case 'isCreateProductOpened':
                $this->isOrdersOpened = false;
                $this->isProductsCreatedOpened = false;
                $this->isCreateProductOpened = true;
                break;

            case 'isProductsCreatedOpened':
                $this->isOrdersOpened = false;
                $this->isCreateProductOpened = false;
                $this->isProductsCreatedOpened = true;
                break;

            
            case 'isOrdersOpened':
                $this->isCreateProductOpened = false;
                $this->isProductsCreatedOpened = false;
                $this->isOrdersOpened = true;
                break;

            
            default:
                break;
        }
    }
}; ?>

<div class="">
    <h1> Products </h1>

    <section class="mt-5 buttons">

        <x-button green class="mr-2" wire:click="open('isCreateProductOpened')" >
            Create Product
        </x-button>

        <x-button sky class="mr-2" wire:click="open('isProductsCreatedOpened')" >
            Products Created
        </x-button>

        <x-button black wire:click="open('isOrdersOpened')">
            Orders
        </x-button>

    
    </section>

    <section class="mt-10 product__create">
        @if ($isCreateProductOpened)
            <livewire:createproduct />
        
        @elseif ($isProductsCreatedOpened)
            <livewire:displayproducts />

        @elseif ($isOrdersOpened)
            <livewire:displayorders />

        @endif

    </section>


</div>
