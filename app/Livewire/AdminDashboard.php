<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class AdminDashboard extends Component
{
    use WithPagination;

    public function approveVendor($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $vendor->update(['status' => 'approved']);
        session()->flash('message', 'Vendor approved successfully!');
    }

    public function rejectVendor($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $vendor->update(['status' => 'rejected']);
        session()->flash('message', 'Vendor rejected.');
    }

    public function suspendVendor($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $vendor->update(['status' => 'suspended']);
        session()->flash('message', 'Vendor suspended.');
    }

    public function render()
    {
        $stats = [
            'total_users' => User::count(),
            'total_vendors' => Vendor::count(),
            'pending_vendors' => Vendor::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
        ];

        $pendingVendors = Vendor::with('user')
            ->where('status', 'pending')
            ->paginate(10);

        $recentVendors = Vendor::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.admin-dashboard', [
            'stats' => $stats,
            'pendingVendors' => $pendingVendors,
            'recentVendors' => $recentVendors,
        ])->layout('layouts.app');
    }
}
