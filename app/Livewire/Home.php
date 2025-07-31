<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;

class Home extends Component
{
    public function render()
    {
        $featuredProducts = Product::published()
            ->inStock()
            ->featured()
            ->with(['vendor', 'category'])
            ->limit(8)
            ->get();

        $categories = Category::active()
            ->root()
            ->limit(6)
            ->get();

        $vendors = Vendor::where('status', 'approved')
            ->with('user')
            ->limit(4)
            ->get();

        return view('livewire.home', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'vendors' => $vendors,
        ])->layout('layouts.app', ['title' => 'MarketHub - Your Multivendor Marketplace']);
    }
}
