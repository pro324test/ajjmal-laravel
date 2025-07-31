<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Get all approved vendors
     */
    public function index(Request $request)
    {
        $vendors = Vendor::where('status', 'approved')
            ->with('user')
            ->paginate($request->get('per_page', 15));

        return response()->json($vendors);
    }

    /**
     * Get a specific vendor
     */
    public function show(Vendor $vendor)
    {
        if ($vendor->status !== 'approved') {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        $vendor->load(['user', 'products' => function ($query) {
            $query->published()->inStock()->with('category');
        }]);

        return response()->json([
            'vendor' => $vendor
        ]);
    }

    /**
     * Get vendor dashboard data
     */
    public function dashboard(Request $request)
    {
        $vendor = $request->user()->vendor;
        
        if (!$vendor) {
            return response()->json(['message' => 'Vendor profile not found'], 404);
        }

        $totalProducts = $vendor->products()->count();
        $publishedProducts = $vendor->products()->where('status', 'published')->count();
        $totalOrders = $vendor->orderItems()->distinct('order_id')->count();
        $totalRevenue = $vendor->orderItems()->sum('total_price');

        return response()->json([
            'vendor' => $vendor,
            'stats' => [
                'total_products' => $totalProducts,
                'published_products' => $publishedProducts,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
            ]
        ]);
    }

    /**
     * Get all vendors for admin
     */
    public function adminVendors(Request $request)
    {
        $vendors = Vendor::with('user')
            ->paginate($request->get('per_page', 15));

        return response()->json($vendors);
    }

    /**
     * Approve vendor
     */
    public function approve(Vendor $vendor)
    {
        $vendor->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return response()->json([
            'message' => 'Vendor approved successfully',
            'vendor' => $vendor
        ]);
    }

    /**
     * Reject vendor
     */
    public function reject(Vendor $vendor)
    {
        $vendor->update([
            'status' => 'rejected',
            'approved_at' => null,
        ]);

        return response()->json([
            'message' => 'Vendor rejected successfully',
            'vendor' => $vendor
        ]);
    }

    /**
     * Admin dashboard
     */
    public function adminDashboard()
    {
        $totalVendors = Vendor::count();
        $approvedVendors = Vendor::where('status', 'approved')->count();
        $pendingVendors = Vendor::where('status', 'pending')->count();

        return response()->json([
            'stats' => [
                'total_vendors' => $totalVendors,
                'approved_vendors' => $approvedVendors,
                'pending_vendors' => $pendingVendors,
            ]
        ]);
    }
}
