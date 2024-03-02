<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;


new class extends Component {

    use WithFileUploads;

    public $title;
    public $description;
    public $price;
    public $image;
    public $quantity;

    public function createProduct() {
        $validated = $this->validate([
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = time() . '.' . $this->image->getClientOriginalExtension();
        $this->image->storeAs('public/images', $imageName);
        
        $validated['user_id'] = Auth::user()->id;
        $validated['image'] = $imageName;

        Product::create($validated);

        session()->flash('status', 'product created successfully');
    } 

}; ?>

<form wire:submit='createProduct'>
    @if (session('status'))
        <div class="bg-red-300">
            {{session('status')}}
        </div>
    @endif

    <!-- Title -->
    <div>
        <x-input-label for="title" :value="__('Title ')" />
        <x-text-input wire:model="title" id="title" class="block mt-1 w-full" type="text" name="title" required autofocus autocomplete="title" placeholder="Title of Product" />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <!-- Description -->
    <div class="mt-5">
        <x-input-label for="description" :value="__('Description ')" />
        <x-text-input wire:model="description" id="description" class="block mt-1 w-full" type="text" name="description" required autofocus autocomplete="description" placeholder="Description of Product" />
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <!-- Price -->
    <div class="mt-5">
        <x-input-label for="price" :value="__('Price ')" />
        <x-text-input wire:model="price" id="price" class="block mt-1 w-full" type="text" name="number" required autofocus autocomplete="price" placeholder="Price of Product" />
        <x-input-error :messages="$errors->get('price')" class="mt-2" />
    </div>

    <!-- Quantity -->
    <div class="mt-5"> 
        <x-input-label for="quantity" :value="__('Quantity ')" />
        <x-text-input wire:model="quantity" id="quantity" class="block mt-1 w-full" type="text" name="number" required autofocus autocomplete="quantity" placeholder="Quantity of Product" />
        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
    </div>

    <!-- Image -->
    <div class="mt-5"> 
        <x-input-label for="image" :value="__('Image ')" />
        <x-text-input wire:model="image" id="image" class="block mt-1 w-full" type="file" name="image" required autofocus autocomplete="image" placeholder="Image of Product" />
        @error('image') <span class="error">{{ $message }}</span> @enderror

    </div>


    <x-primary-button  class="mt-5">
        Submit 
    </x-primary-button>

</form>
