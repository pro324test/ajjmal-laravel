<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Shopping Cart</h1>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold">Cart Items ({{ $cartItems->count() }})</h2>
                    <button wire:click="clearCart" 
                            class="text-red-600 hover:text-red-800 text-sm"
                            onclick="return confirm('Are you sure you want to clear your cart?')">
                        Clear Cart
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                @foreach($cartItems as $item)
                    <div class="flex items-center justify-between border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                @if($item->product->images && count($item->product->images) > 0)
                                    <img src="{{ $item->product->images[0] }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded">
                                @else
                                    <span class="text-gray-400 text-xs">No Image</span>
                                @endif
                            </div>
                            
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-600">by {{ $item->product->vendor->store_name }}</p>
                                <p class="text-sm font-medium text-gray-800">
                                    ${{ number_format($item->product->getCurrentPrice(), 2) }} each
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                                    -
                                </button>
                                
                                <span class="w-12 text-center font-medium">{{ $item->quantity }}</span>
                                
                                <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                                    +
                                </button>
                            </div>
                            
                            <div class="text-right">
                                <p class="font-semibold text-gray-800">
                                    ${{ number_format($item->getTotalPrice(), 2) }}
                                </p>
                            </div>
                            
                            <button wire:click="removeFromCart({{ $item->id }})" 
                                    class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Remove this item from cart?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Cart Summary -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold">Total:</span>
                    <span class="text-2xl font-bold text-gray-800">${{ number_format($total, 2) }}</span>
                </div>
                
                <div class="flex space-x-4">
                    <a href="{{ route('home') }}" 
                       class="flex-1 bg-gray-500 text-white text-center py-3 rounded hover:bg-gray-600">
                        Continue Shopping
                    </a>
                    <button class="flex-1 bg-blue-500 text-white py-3 rounded hover:bg-blue-600">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <div class="mb-4">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 6H6L5 9z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
            <p class="text-gray-500 mb-4">Start adding some products to your cart!</p>
            <a href="{{ route('home') }}" 
               class="bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600">
                Browse Products
            </a>
        </div>
    @endif
</div>
