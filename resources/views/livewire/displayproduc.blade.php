<?php

use Livewire\Volt\Component;
use App\Models\Product;

new class extends Component {
    public $productID;
    public $product;
    public $updateProducts = false;

    public $title;
    public $quantity;
    public $description;
    public $price;

    public function mount(){
        $this->product = Product::find($this->productID); 
    }

    public function updateProduct() {
        $this->product->title = $this->title;
        $this->product->quantity = $this->quantity;
        $this->product->description = $this->description;
        $this->product->price = $this->price;

        $this->product->save();

        $this->mount();

        session()->flash('status', "product updated");
    }
}; ?> 

<div>
    @if (session('status'))
        <div class="py-3 pl-3 text-white bg-red-500">
            <h1> {{session('status')}} </h1>
        </div>
    @endif

    <div class="flex flex-col gap-2 card">
        <x-input wire:model='title' label="Title of product" placeholder="{{$product->title}}" />
        
        <img class="pr-5" src="{{asset('/storage/images/' . $product->image )}}"  alt="">

        <x-input wire:model='quantity' label="Quantity of product" placeholder="{{ $product->quantity }}" />

        <x-input wire:model='description' label="Description of product" placeholder="{{ $product->description }}" />

        <x-input wire:model='price' label="Price of product" placeholder="{{ $product->price }}" />

        <x-button primary wire:click='updateProduct'>Save </x-button>
            
    </div>

</div>
