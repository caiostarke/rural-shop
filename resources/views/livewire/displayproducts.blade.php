<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

new class extends Component {
    public $products;
    public $updateProducts = false;
    public $displayModeOpened = false;
    public $actualID = null;

    public function mount() {
        $this->products = Auth::user()->products()->get();
    }
    
    public function deleteProduct($productID) {
        $product = Product::find($productID);

        if ($product) {
            $product->delete();
                
            $this->mount();

            session()->flash('status', 'product deleted successfully');
        }
    }

    public function displayMode($productID) {
        $this->actualID = $productID;
    }
    
}; ?>

<div>

    @if (session('status'))
        <div class="py-2 pl-3 bg-red-300 alert danger">
            {{ session('status')}}
        </div>
    @endif


    <nav class="grid grid-cols-1 gap-3 trend--products lg:grid-cols-3 md:grid-cols-2">

        @if (count($products) > 0 && !$actualID)
            @foreach ($products as $product)

                <x-card wire:key="{{$product->id}}" class="shadow-2xl" title="{{ $product->title }}" shadow="2xl">
                    <img class="pr-5" src="{{asset('/storage/images/' . $product->image )}}"  alt="">

                    @if ($updateProducts)
                        <x-input class="mt-5 text-sm font-semibold product__description" type="text" name="product__description" id="product__description" placeholder="{{ $product->description }}" />
                        <x-input class="mt-5 text-sm font-semibold text-blue-700 product__price" type="text" name="product__description" id="product__description" placeholder="$ {{$product->price}}" />
                    
                    @else
                        <p class="mt-5 text-sm font-semibold product__description" > {{$product->description}} </p> 
                        <p class="mt-5 text-sm font-semibold text-blue-700 product__price" > $ {{$product->price}} </p>    
                    @endif
                        

                    <section class="mt-5 action__buttons">
                        <x-button red class="mr-2" wire:click="deleteProduct('{{$product->id}}')"> Delete Product </x-button>
                        <x-button orange wire:click="displayMode('{{ $product->id }}')"> Update Product </x-button>
                    </section>
                </x-card>

            @endforeach

        @endif

        @if ($actualID)
            <livewire:displayproduc :productID="$actualID" />
        @endif

    </nav>

</div>
