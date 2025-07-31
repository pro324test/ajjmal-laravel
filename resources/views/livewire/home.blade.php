<div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Welcome to MarketHub
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    Your one-stop multivendor marketplace for everything you need
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Shop Now
                    </a>
                    <a href="{{ route('vendor.register') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                        Become a Vendor
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Shop by Category</h2>
                <p class="text-gray-600">Discover products from our top categories</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="group">
                        <div class="bg-gray-100 rounded-lg p-6 text-center hover:shadow-lg transition group-hover:bg-blue-50">
                            @if($category->image)
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-16 h-16 mx-auto mb-4 rounded-lg">
                            @else
                                <div class="w-16 h-16 mx-auto mb-4 bg-blue-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">{{ $category->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Products</h2>
                <p class="text-gray-600">Handpicked products from our trusted vendors</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
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
                            <h3 class="font-semibold text-gray-900 mb-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-blue-600">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">by {{ $product->vendor->shop_name }}</p>
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
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    View All Products
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Vendors -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Vendors</h2>
                <p class="text-gray-600">Meet our trusted marketplace partners</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($vendors as $vendor)
                    <div class="bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition">
                        @if($vendor->logo)
                            <img src="{{ $vendor->logo }}" alt="{{ $vendor->shop_name }}" class="w-16 h-16 mx-auto mb-4 rounded-full">
                        @else
                            <div class="w-16 h-16 mx-auto mb-4 bg-blue-200 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-xl">{{ substr($vendor->shop_name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $vendor->shop_name }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($vendor->description, 60) }}</p>
                        <a href="{{ route('vendors.show', $vendor->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Visit Store →
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Start Selling?</h2>
            <p class="text-xl mb-8 text-blue-100">Join thousands of successful vendors on our platform</p>
            <a href="{{ route('vendor.register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Get Started Today
            </a>
        </div>
    </section>
</div>
