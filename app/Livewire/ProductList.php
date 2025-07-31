<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $category_id = '';
    public $sort_by = 'name';
    public $sort_direction = 'asc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryId()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $field;
            $this->sort_direction = 'asc';
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with(['vendor', 'category'])
            ->where('status', 'active');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        $products = $query->orderBy($this->sort_by, $this->sort_direction)
                         ->paginate(12);

        $categories = Category::where('is_active', true)->get();

        return view('livewire.product-list', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
