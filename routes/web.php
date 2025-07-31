<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ProductList;
use App\Livewire\VendorDashboard;
use App\Livewire\ShoppingCart;
use App\Livewire\AdminDashboard;

Route::get('/', ProductList::class)->name('home');
Route::get('/cart', ShoppingCart::class)->name('cart');
Route::get('/vendor/dashboard', VendorDashboard::class)->name('vendor.dashboard');
Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
