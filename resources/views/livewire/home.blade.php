<div>
<div>
    <!-- Hero Section -->
    <section class="hero-gradient text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-bold mb-8 leading-tight">
                    Welcome to <span class="text-yellow-300">MarketHub</span>
                </h1>
                <p class="text-xl md:text-2xl mb-12 text-blue-100 max-w-3xl mx-auto leading-relaxed">
                    Your one-stop multivendor marketplace for everything you need. Discover amazing products from trusted vendors worldwide.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center">
                    <a href="{{ route('products.index') }}" class="btn-primary text-lg px-8 py-4">
                        🛍️ Shop Now
                    </a>
                    <a href="{{ route('vendor.register') }}" class="btn-secondary text-lg px-8 py-4">
                        🚀 Become a Vendor
                    </a>
                </div>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-yellow-300 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-pink-300 rounded-full opacity-20 animate-pulse"></div>
    </section>

    <!-- Featured Categories -->
    <section class="py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    Shop by <span class="text-gradient">Category</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Discover products from our top categories, carefully curated for your needs</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="group">
                        <div class="category-card">
                            @if($category->image)
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-20 h-20 mx-auto mb-6 rounded-2xl group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                            <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 text-lg">{{ $category->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    <span class="text-gradient">Featured</span> Products
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Handpicked products from our trusted vendors, specially selected for quality and value</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($featuredProducts as $product)
                    <div class="product-card group">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            @if($product->first_image)
                                <img src="{{ $product->first_image }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-6">
                            @if($product->featured)
                                <span class="inline-block bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs px-3 py-1 rounded-full mb-3 font-bold">⭐ FEATURED</span>
                            @endif
                            
                            <h3 class="font-bold text-gray-900 mb-3 text-lg leading-tight">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-blue-600 transition-colors duration-300">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-4 flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                by {{ $product->vendor->shop_name }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    @if($product->isOnSale())
                                        <span class="text-2xl font-bold text-red-500">${{ number_format($product->sale_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</span>
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full font-bold">SALE</span>
                                    @else
                                        <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <button class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-16">
                <a href="{{ route('products.index') }}" class="btn-primary text-lg px-10 py-4">
                    🛍️ View All Products
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
