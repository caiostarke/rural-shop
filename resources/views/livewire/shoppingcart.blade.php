<?php

use Livewire\Volt\Component;
use App\Models\Product;

new class extends Component {
    public $products;
    public $cart;
    public $cartProducts;

    public function mount() {
        $this->products = Product::all();
        $this->cart = Session::get('cart', []);

        $this->cartProducts = $this->getProductsDetails($this->cart);
    }

    public function getProductsDetails($cart) {
        $cartProducts = [];
        $subtotal = 0;

        foreach ($cart as $productID => $quantity) {
            $product = Product::find($productID);
            
            if ($product) {
                $cartProducts[] = [ 
                    'id' => $product->id,
                    'name' => $product->title,
                    'price' => $product->price,
                    'quantity' => $quantity,
                ];

                $subtotal += $product->price;
            };

        }

        $this->cart['subtotal'] = $subtotal;

        return $cartProducts;
    }


    public function cleanCart() {
        $this->cart = [];
        $this->cartProducts = [];
        Session::forget('cart');
    }

    public function removeFromCart($productID) {
        unset($this->cart[$productID]);
        Session::put('cart', $this->cart);

        $this->dispatch('productUpdated');
    }


}; ?>

<div class="px-1 pt-5 md:mx-auto md:w-3/4">

    <x-card title="Shopping Cart" class="grid gap-3">
        @foreach ($cartProducts as $product)
            <x-card wire:key="{{$product['name']}}"  class="flex flex-row items-center justify-between px-2" title="{{ $product['name'] }}">
                <p class="text-sm font-semibold text-blue-700 product__price" > $ {{$product['price']}} </p> 
                <x-button red wire:click="removeFromCart({{ $product['id'] }})">Remove from Cart</x-button>
            </x-card>
        @endforeach 



        <x-slot name="footer" class=" gap-x-4">
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 buttons">
                    <x-button  black icon="arrow-left"   label="Back To Explore Products" href="{{ route('explore')}}" />
     
                    <x-button red label="Clean Cart" wire:click="cleanCart" />
                    
                    <x-button green href="{{route('checkout')}}" primary label="Checkout" wire:click="agree" />
                </div>
                
                @if ( isset($cart['subtotal']))
                    <p class="text-sm font-semibold text-blue-700"> Subtotal: $ {{ $cart['subtotal']}}</p>
                @endif
            </div>

        </x-slot>
    </x-card>

</div>
