<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $vendor = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $featured = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingVendor()
    {
        $this->resetPage();
    }

    public function updatingMinPrice()
    {
        $this->resetPage();
    }

    public function updatingMaxPrice()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category', 'vendor', 'minPrice', 'maxPrice', 'featured']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with(['vendor', 'category'])
            ->published()
            ->inStock();

        // Apply filters
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('short_description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->category);
            });
        }

        if ($this->vendor) {
            $query->whereHas('vendor', function ($q) {
                $q->where('slug', $this->vendor);
            });
        }

        if ($this->minPrice) {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice) {
            $query->where('price', '<=', $this->maxPrice);
        }

        if ($this->featured) {
            $query->featured();
        }

        // Apply sorting
        if (in_array($this->sortBy, ['name', 'price', 'created_at', 'avg_rating'])) {
            $query->orderBy($this->sortBy, $this->sortOrder);
        }

        $products = $query->paginate(12);

        $categories = Category::active()->root()->get();
        $vendors = Vendor::where('status', 'approved')->get();

        return view('livewire.product-list', [
            'products' => $products,
            'categories' => $categories,
            'vendors' => $vendors,
        ])->layout('layouts.app', ['title' => 'Products - MarketHub']);
    }
}
