<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\ProductList;
use App\Livewire\ProductDetail;
use App\Livewire\ShoppingCart;

// Home page
Route::get('/', Home::class)->name('home');

// Products
Route::get('/products', ProductList::class)->name('products.index');
Route::get('/products/{product}', ProductDetail::class)->name('products.show');

// Categories
Route::get('/categories', function () {
    return view('categories.index');
})->name('categories.index');

Route::get('/categories/{category}', function () {
    return view('categories.show');
})->name('categories.show');

// Vendors
Route::get('/vendors', function () {
    return view('vendors.index');
})->name('vendors.index');

Route::get('/vendors/{vendor}', function () {
    return view('vendors.show');
})->name('vendors.show');

// Cart
Route::get('/cart', ShoppingCart::class)->name('cart.index');

// Authentication routes (placeholder)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Dashboard routes (placeholder)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/orders', function () {
    return view('orders.index');
})->name('orders.index');

// Vendor routes (placeholder)
Route::get('/vendor/register', function () {
    return view('vendor.register');
})->name('vendor.register');

Route::get('/vendor/dashboard', function () {
    return view('vendor.dashboard');
})->name('vendor.dashboard');
