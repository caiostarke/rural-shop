<?php

use Livewire\Volt\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\order_products;
use Livewire\Attributes\On; 

new class extends Component {
    public $products;
    public $cart;
    public $cartProducts;

    #[On('productUpdated')]
    public function mount() {
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

    #[On('productUpdated')]
    public function recalcSubtotal() {
        $this->cart['subtotal'] = 0;
        foreach ($this->cartProducts as $product) {
            $this->cart['subtotal'] += $product['quantity'] * $product['price']; 
        }
    }

    public function increaseQuantity($id) {
        foreach ($this->cartProducts as &$product) {
            if ($product['id'] == $id) {
                $product['quantity'] += 1;
                break;
            }
        }

        $this->dispatch('productUpdated');
    }

    public function decreaseQuantity($id) {
        foreach ($this->cartProducts as &$product) {
            if ($product['id'] == $id) {
                if ($product['quantity'] == 0) {
                    break;
                } 
                $product['quantity'] -= 1;
                break;
            }

        }

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

    public function finishCheckout() {
        $order = Order::create([
            'user_id' => Auth::user()->id,
            'total_amount' => $this->cart['subtotal'],
            'status' => 'completed',
        ]);

        foreach ($this->cartProducts as $product) {
            order_products::create([
                'order_id' => $order->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }
 
        session()->flash('status', 'Order created successfully. Check your dashboard to see the status');
        return redirect()->route('explore');
    }
    
}; ?>


<div class="px-1 pt-5 md:mx-auto md:w-3/4">

    <div class="flex gap-2 ">

        <section class="justify-around w-3/4 opacity-50 payment__section">
            <div class="p-5 bg-white rounded-md shadow-md ">
                <h2 class="mb-6 text-2xl font-semibold">Checkout</h2>
                <!-- Order Summary -->
                <div class="mb-6">
                    <h3 class="mb-2 text-lg font-semibold">Order Summary</h3>
                    <div class="pt-2 border-t border-gray-200">
                        <!-- Example order item -->
                        @foreach ($cartProducts as $product)
                            <div wire:key="{{$product['id']}}"  class="flex items-center justify-between py-2">
                                <span class="font-medium">{{$product['name']}}</span>

                                <div>
                                    <span class="cursor-pointer" wire:click="decreaseQuantity({{$product['id']}})"> - </span>
                                    <span class="px-3 font-medium">{{$product['quantity']}}</span>
                                    <span class="cursor-pointer" wire:click="increaseQuantity({{$product['id']}})"> + </span>
                                </div>
                                <span class="text-gray-500">${{$product['price']}}</span>
                            </div>
                        @endforeach 

                        <!-- Total -->
                        <div class="flex items-center justify-between py-2">
                            <span class="font-semibold">Total</span>
                            <span class="font-semibold">${{$cart['subtotal']}}</span>
                        </div>
                    </div>
                </div>
                <!-- Payment Details -->
                <div class="mb-6">
                    <h3 class="mb-2 text-lg font-semibold">Payment Details</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Card Number -->
                        <input type="text" class="col-span-3 p-2 border border-gray-300 rounded-md" placeholder="Card Number">
                        <!-- Expiry Date -->
                        <input type="text" class="p-2 border border-gray-300 rounded-md" placeholder="Expiry Date (MM/YY)">
                        <!-- CVC -->
                        <input type="text" class="p-2 border border-gray-300 rounded-md" placeholder="CVC">
                    </div>
                </div>
                <!-- Billing Address -->
                <div class="mb-6">
                    <h3 class="mb-2 text-lg font-semibold">Billing Address</h3>
                    <input type="text" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Full Name">
                    <input type="text" class="w-full p-2 mt-2 border border-gray-300 rounded-md" placeholder="Address">
                    <input type="text" class="w-full p-2 mt-2 border border-gray-300 rounded-md" placeholder="City">
                    <input type="text" class="w-full p-2 mt-2 border border-gray-300 rounded-md" placeholder="Zip Code">
                </div>
                <!-- Checkout Button -->
                <button wire:click='finishCheckout' class="w-full px-4 py-2 text-white transition-colors duration-300 bg-blue-500 rounded-md hover:bg-blue-600">Place Order</button>
            </div>
        </section>
        

    </div>

    <div class="flex items-center justify-between mt-5">
        <div class="flex items-center gap-3 buttons">
            <x-button  black icon="arrow-left"   label="Back To Explore Products" href="{{ route('explore')}}" />

            <x-button red label="Clean Cart" wire:click="cleanCart" />
            
            <x-button green href="{{route('checkout')}}" primary label="Checkout" wire:click="agree" />
        </div>
        
        @if ( isset($cart['subtotal']))
            <p class="text-sm font-semibold text-blue-700"> Subtotal: $ {{ $cart['subtotal']}}</p>
        @endif
    </div>


</div>
