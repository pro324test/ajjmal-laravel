<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class VendorDashboard extends Component
{
    use WithPagination;

    public $showAddProduct = false;
    public $editingProduct = null;
    
    // Product form fields
    public $name = '';
    public $description = '';
    public $short_description = '';
    public $category_id = '';
    public $sku = '';
    public $price = '';
    public $sale_price = '';
    public $stock_quantity = '';
    public $weight = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'short_description' => 'nullable|string|max:500',
        'category_id' => 'required|exists:categories,id',
        'sku' => 'required|string',
        'price' => 'required|numeric|min:0',
        'sale_price' => 'nullable|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'weight' => 'nullable|numeric|min:0',
    ];

    public function showAddProductForm()
    {
        $this->resetForm();
        $this->showAddProduct = true;
    }

    public function hideAddProductForm()
    {
        $this->showAddProduct = false;
        $this->resetForm();
    }

    public function editProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $this->editingProduct = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->short_description = $product->short_description;
        $this->category_id = $product->category_id;
        $this->sku = $product->sku;
        $this->price = $product->price;
        $this->sale_price = $product->sale_price;
        $this->stock_quantity = $product->stock_quantity;
        $this->weight = $product->weight;
        $this->showAddProduct = true;
    }

    public function saveProduct()
    {
        $this->validate();

        // Create sample vendor for demo (normally would check auth)
        $vendor = Vendor::first();
        if (!$vendor) {
            $vendor = Vendor::create([
                'user_id' => 1, // Demo user
                'store_name' => 'Demo Store',
                'status' => 'approved',
            ]);
        }

        $data = [
            'vendor_id' => $vendor->id,
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'short_description' => $this->short_description,
            'category_id' => $this->category_id,
            'sku' => $this->sku,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'stock_quantity' => $this->stock_quantity,
            'weight' => $this->weight,
        ];

        if ($this->editingProduct) {
            $product = Product::findOrFail($this->editingProduct);
            $product->update($data);
            session()->flash('message', 'Product updated successfully!');
        } else {
            Product::create($data);
            session()->flash('message', 'Product created successfully!');
        }

        $this->hideAddProductForm();
        $this->resetPage();
    }

    public function deleteProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete();
        session()->flash('message', 'Product deleted successfully!');
        $this->resetPage();
    }

    private function resetForm()
    {
        $this->editingProduct = null;
        $this->name = '';
        $this->description = '';
        $this->short_description = '';
        $this->category_id = '';
        $this->sku = '';
        $this->price = '';
        $this->sale_price = '';
        $this->stock_quantity = '';
        $this->weight = '';
    }

    public function render()
    {
        // For demo purposes, show all products. In real app, filter by vendor
        $products = Product::with(['category', 'vendor'])->paginate(10);
        $categories = Category::where('is_active', true)->get();

        return view('livewire.vendor-dashboard', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
