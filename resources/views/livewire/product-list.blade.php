<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">All Products</h1>
            <p class="text-gray-600">Discover amazing products from our trusted vendors</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                        <button wire:click="clearFilters" class="text-sm text-blue-600 hover:text-blue-800">
                            Clear All
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                        <input type="text" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="Search products..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select wire:model.live="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Vendor Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                        <select wire:model.live="vendor" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Vendors</option>
                            @foreach($vendors as $v)
                                <option value="{{ $v->slug }}">{{ $v->shop_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <div class="flex space-x-2">
                            <input type="number" 
                                   wire:model.live.debounce.500ms="minPrice" 
                                   placeholder="Min"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <input type="number" 
                                   wire:model.live.debounce.500ms="maxPrice" 
                                   placeholder="Max"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Featured Products -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   wire:model.live="featured" 
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Featured Products Only</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:w-3/4">
                <!-- Sort Options -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-gray-600 mb-4 sm:mb-0">
                        Showing {{ $products->count() }} of {{ $products->total() }} products
                    </div>
                    
                    <div class="flex space-x-4">
                        <select wire:model.live="sortBy" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="created_at">Newest</option>
                            <option value="name">Name</option>
                            <option value="price">Price</option>
                            <option value="avg_rating">Rating</option>
                        </select>
                        
                        <select wire:model.live="sortOrder" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="asc">Low to High</option>
                            <option value="desc">High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition">
                            <a href="{{ route('products.show', $product->slug) }}">
                                @if($product->first_image)
                                    <img src="{{ $product->first_image }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-t-lg">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="p-4">
                                @if($product->featured)
                                    <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded mb-2">Featured</span>
                                @endif
                                
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('products.show', $product->slug) }}" class="hover:text-blue-600">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                
                                <p class="text-sm text-gray-600 mb-2">by {{ $product->vendor->shop_name }}</p>
                                <p class="text-sm text-gray-500 mb-3">{{ Str::limit($product->short_description, 60) }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        @if($product->isOnSale())
                                            <span class="text-lg font-bold text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                                            <span class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No products found</h3>
                            <p class="mt-2 text-gray-500">Try adjusting your search or filter criteria</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
