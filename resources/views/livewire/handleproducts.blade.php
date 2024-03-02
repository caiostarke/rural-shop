<?php

use Livewire\Volt\Component;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Redis;



new class extends Component {
    public $products;
    public $trendingProducts;
    public $cart;
    public $cartProducts;
    public $input;

    #[On('productUpdated')]
    public function mount() {
        $this->trendingProducts = Redis::zrange('trending_products', 0, -1);
        $this->trendingProducts = Product::whereIn('id', $this->trendingProducts)->get();
        
        $this->products = Product::all();
        $this->cart = Session::get('cart', []);

        $this->cartProducts = $this->getProductsDetails($this->cart);
    }

    public function updatedInput() {
        $this->products = Product::where('title', 'like', '%' . $this->input. '%')
                                   ->orWhere('description', 'like', '%' . $this->input. '%')
                                    ->get();
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

    public function addToCart($productID) {
        $this->cart[$productID] = isset($this->cart[$productID]) ? $this->cart[$productID] + 1 : 1;
        Session::put('cart', $this->cart);

        Redis::zadd('trending_products', time(), $productID);

        session()->flash('status', "Product added to cart Successfully");

        $this->dispatch('productUpdated');
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

<div>
    <nav class="fixed flex w-2/4 transform -translate-x-1/2 rounded search__products left-1/2 top-4">
        <x-input
        placeholder="Search for Products"
        wire:model.live="input"
        />

        <x-button orange icon="shopping-cart" class="ml-3 rounded-full" x-on:click="$openModal('persistentModal')" primary/>
    </nav>

    @if (session('status'))
        <div class="px-6 py-3 bg-green-200">
            <h1> {{ session('status')}} </h1>
        </div>
    @endif

    <div class="mt-14 display__trend--products ">

        <x-modal name="persistentModal" persistent>
            <x-card title="Shopping Cart" class="grid gap-3 ">

                @foreach ($cartProducts as $product)
                    <x-card wire:key="{{$product['name']}}"  class="flex flex-row items-center justify-between px-2" title="{{ $product['name'] }}">
                        <p class="text-sm font-semibold text-blue-700 product__price" > $ {{$product['price']}} </p> 
                        <x-button red wire:click="removeFromCart({{ $product['id'] }})">Remove from Cart</x-button>
                    </x-card>
                @endforeach 

                <x-slot name="footer" class=" gap-x-4">
                    
                    <div class="flex items-center justify-between">
                        <div class="buttons">
                            <x-button flat label="Close" x-on:click="close" />
             
                            <x-button href="{{route('shopping.cart')}}" primary label="Go to Shopping Cart" wire:click="agree" />

                            <x-button red label="Clean Cart" wire:click="cleanCart" />
        
                        </div>
                        
                        @if ( isset($cart['subtotal']))
                            <p class="text-sm font-semibold text-blue-700"> Subtotal: $ {{ $cart['subtotal']}}</p>
                        @endif
                    </div>

                </x-slot>
            </x-card>
        </x-modal>
        

        @if (!$input)

        <div class="flex title__trend--products ">
            <i class="pl-2 pr-3 text-xl text-red-600 fa-solid fa-fire"></i> 
            <h1 class="mb-5 text-xl text-red-600 "> Trending products </h1>
        </div>
        

        <nav class="grid items-center grid-cols-1 gap-3 mb-10 trend--products lg:grid-cols-3 md:grid-cols-2">

            @if (count($trendingProducts) > 0 )
                @foreach ($trendingProducts as $product)
    
                    <x-card wire:key="{{$product->id}}"  class="mx-12 shadow-2xl md:mx-auto " title="{{ $product->title }}" shadow="2xl">
                        <img class="w-3/4 pr-5 md:w-auto" src="{{asset('/storage/images/' . $product->image )}}"  alt="">
                        <p class="mt-5 text-sm font-semibold product__description" > {{$product->description}} </p>
                            
                        <p class="mt-5 text-sm font-semibold text-blue-700 product__price" > $ {{$product->price}} </p>    
                        
                        <section class="mt-5 action__button">
                            <x-button class="ml-1" orange right-icon="shopping-cart" wire:click="addToCart('{{$product->id}}')" > Add To Cart</x-button>
                        </section>

                    </x-card>
    
                @endforeach
            
            @endif
    
        </nav>
        
        <hr class="mt-16 mb-16" />

        @endif


        <div class="flex title__trend--products ">
            <i class="pl-2 pr-3 text-xl text-blue-600 fa-solid fa-store"></i> 
            <h1 class="mb-5 text-xl text-blue-600 "> Products </h1>
        </div>

        <nav class="grid items-center grid-cols-1 gap-3 trend--products lg:grid-cols-3 md:grid-cols-2">

            @if (count($products) > 0)
                @foreach ($products as $product)
    
                    <x-card wire:key="{{$product->id}}"  class="mx-12 shadow-2xl md:mx-auto " title="{{ $product->title }}" shadow="2xl">
                        <img class="w-3/4 pr-5 md:w-auto" src="{{asset('/storage/images/' . $product->image )}}"  alt="">
                        <p class="mt-5 text-sm font-semibold product__description" > {{$product->description}} </p>
                            
                        <p class="mt-5 text-sm font-semibold text-blue-700 product__price" > $ {{$product->price}} </p>    
                        
                        <section class="mt-5 action__button">
                            <x-button class="ml-1" orange right-icon="shopping-cart" wire:click="addToCart('{{$product->id}}')" > Add To Cart</x-button>
                        </section>

                    </x-card>
    
                @endforeach
            
            @endif
    
        </nav>

    
    </div>  
</div>
