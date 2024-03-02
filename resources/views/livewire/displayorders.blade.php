<?php

use Livewire\Volt\Component;
use App\Models\Order;
use Livewire\Attributes\On; 

new class extends Component {
    public $orders;
    public $orders_and_products = [];

    #[On('order_updated')] 
    public function mount() {
        $this->orders = Auth::user()->orders()->get();

        foreach ($this->orders as $order) {
            $this->orders_and_products[$order->id] = [
                'order' => $order,
                'products' => $order->products,
                'order_products' => $order->order_products()->get()
            ];
        }

    }   

    public function deleteOrder($orderID){
        $order = Order::find($orderID);
    
        if ($order) {
            $order->delete();

            $this->mount();

            session()->flash('status', 'order deleted successfully');
        }


    }

}; ?>


<div >

    @if (session('status'))
        <div class="bg-red-300 alert danger ">
            {{ session('status')}}
        </div>
    @endif

    <nav class="grid grid-cols-1 gap-3 trend--products lg:grid-cols-3 md:grid-cols-2">
        
        @if (count($orders) > 0)
            @foreach ($orders_and_products as $order)

                <x-card class="flex flex-col gap-3 shadow-2xl" shadow="2xl">

                    <p class="mb-2 text-sm font-semibold text-blue-700 product__price" > $ {{ $order['order']['total_amount']}} </p>    
                    <div>
                        <x-badge icon="check" class="text-white bg-green-500 " label="{{ $order['order']['status']}}" />
                    </div>

                    @foreach ($order['products']  as  $pd)
                     
                        <p class="mt-4 text-sm font-semibold text-red-700 product__price" > {{$pd['title']}} </p>    

                        <p class="mt-1 text-sm font-semibold text-red-700 product__price" > quantity: {{$order['order_products']->where('product_id', $pd->id)->first()->quantity}} </p>    

                        <p class="mt-1 mb-5 text-sm font-semibold text-blue-700 product__price" > Price paid per unit: $ {{$pd['price']}} </p>
                        <hr />    

                    @endforeach                  

                    <div>
                        <x-button red wire:click="deleteOrder({{$order['order']['id']}})"> Delete </x-button>

                    </div>

                </x-card>

            @endforeach
        
        @else
                
            <h1> ;c Unfortunately u dont have any order yet.</h1>

        @endif

    </nav>

</div>

