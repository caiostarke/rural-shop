<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::view('/', 'welcome');

Volt::route('/explore', 'explore')
    ->middleware(['auth', 'verified'])
    ->name('explore');

Volt::route('/shopping-cart', 'shoppingcart')
    ->middleware(['auth', 'verified'])
    ->name('shopping.cart');

Volt::route('/checkout', 'checkout')
    ->middleware(['auth', 'verified'])
    ->name('checkout');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
