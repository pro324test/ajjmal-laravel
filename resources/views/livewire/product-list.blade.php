<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Products</h1>
        
        <!-- Search and Filter Section -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Search products..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select wire:model.live="category_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <div class="flex space-x-2">
                        <button wire:click="sortBy('name')" 
                                class="px-3 py-2 text-sm border rounded {{ $sort_by === 'name' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }}">
                            Name {{ $sort_by === 'name' ? ($sort_direction === 'asc' ? '↑' : '↓') : '' }}
                        </button>
                        <button wire:click="sortBy('price')" 
                                class="px-3 py-2 text-sm border rounded {{ $sort_by === 'price' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700' }}">
                            Price {{ $sort_by === 'price' ? ($sort_direction === 'asc' ? '↑' : '↓') : '' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-gray-400">No Image</span>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($product->short_description, 60) }}</p>
                        <p class="text-xs text-gray-500 mb-2">by {{ $product->vendor->store_name }}</p>
                        <p class="text-sm text-blue-600 mb-2">{{ $product->category->name }}</p>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex flex-col">
                                @if($product->sale_price)
                                    <span class="text-lg font-bold text-green-600">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="text-lg font-bold text-gray-800">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            @if($product->inStock())
                                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                                    Add to Cart
                                </button>
                            @else
                                <span class="text-red-500 text-sm font-medium">Out of Stock</span>
                            @endif
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-2">Stock: {{ $product->stock_quantity }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <p class="text-gray-500 text-lg">No products found matching your criteria.</p>
        </div>
    @endif
</div>
