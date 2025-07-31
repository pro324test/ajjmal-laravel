<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_users'] }}</div>
            <div class="text-gray-600">Total Users</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-green-600">{{ $stats['total_vendors'] }}</div>
            <div class="text-gray-600">Total Vendors</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_vendors'] }}</div>
            <div class="text-gray-600">Pending Vendors</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['total_products'] }}</div>
            <div class="text-gray-600">Total Products</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-2xl font-bold text-red-600">{{ $stats['total_orders'] }}</div>
            <div class="text-gray-600">Total Orders</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Pending Vendors -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Pending Vendor Approvals</h2>
            </div>
            
            @if($pendingVendors->count() > 0)
                <div class="p-6">
                    @foreach($pendingVendors as $vendor)
                        <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $vendor->store_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $vendor->user->name }} ({{ $vendor->user->email }})</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($vendor->description, 100) }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click="approveVendor({{ $vendor->id }})" 
                                            class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                        Approve
                                    </button>
                                    <button wire:click="rejectVendor({{ $vendor->id }})" 
                                            class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="mt-4">
                        {{ $pendingVendors->links() }}
                    </div>
                </div>
            @else
                <div class="p-6 text-center text-gray-500">
                    No pending vendor approvals.
                </div>
            @endif
        </div>

        <!-- Recent Vendors -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">Recent Vendors</h2>
            </div>
            
            @if($recentVendors->count() > 0)
                <div class="p-6">
                    @foreach($recentVendors as $vendor)
                        <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-800">{{ $vendor->store_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $vendor->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $vendor->created_at->diffForHumans() }}</p>
                                </div>
                                <div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $vendor->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($vendor->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                            'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($vendor->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($vendor->status === 'approved')
                                <div class="mt-2 flex space-x-2">
                                    <button wire:click="suspendVendor({{ $vendor->id }})" 
                                            class="bg-orange-500 text-white px-3 py-1 rounded text-xs hover:bg-orange-600">
                                        Suspend
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center text-gray-500">
                    No vendors yet.
                </div>
            @endif
        </div>
    </div>
</div>
