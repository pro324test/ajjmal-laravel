<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Get user's orders
     */
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['orderItems.product', 'orderItems.vendor'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }

    /**
     * Get a specific order
     */
    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->load(['orderItems.product', 'orderItems.vendor']);

        return response()->json([
            'order' => $order
        ]);
    }

    /**
     * Create a new order from cart
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_address' => 'required|array',
            'billing_address.name' => 'required|string',
            'billing_address.address' => 'required|string',
            'billing_address.city' => 'required|string',
            'billing_address.state' => 'required|string',
            'billing_address.zip' => 'required|string',
            'billing_address.country' => 'required|string',
            'shipping_address' => 'required|array',
            'payment_method' => 'required|string',
            'notes' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $cartItems = Cart::where('user_id', $request->user()->id)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 422);
        }

        // Validate stock for all items
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            if (!$product->in_stock || $product->status !== 'published') {
                return response()->json([
                    'message' => "Product '{$product->name}' is not available"
                ], 422);
            }

            if ($product->manage_stock && $cartItem->quantity > $product->stock_quantity) {
                return response()->json([
                    'message' => "Insufficient stock for '{$product->name}'. Available: {$product->stock_quantity}"
                ], 422);
            }
        }

        DB::beginTransaction();
        
        try {
            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->effective_price;
            });

            $taxAmount = $subtotal * 0.1; // 10% tax
            $shippingAmount = 10.00; // Fixed shipping
            $totalAmount = $subtotal + $taxAmount + $shippingAmount;

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'billing_address' => $request->billing_address,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $unitPrice = $product->effective_price;
                $totalPrice = $cartItem->quantity * $unitPrice;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'vendor_id' => $product->vendor_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'product_snapshot' => [
                        'name' => $product->name,
                        'sku' => $product->sku,
                        'description' => $product->description,
                        'image' => $product->first_image,
                    ],
                ]);

                // Update stock
                if ($product->manage_stock) {
                    $product->decrement('stock_quantity', $cartItem->quantity);
                    if ($product->stock_quantity <= 0) {
                        $product->update(['in_stock' => false]);
                    }
                }
            }

            // Clear cart
            Cart::where('user_id', $request->user()->id)->delete();

            DB::commit();

            $order->load(['orderItems.product', 'orderItems.vendor']);

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel an order
     */
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$order->canBeCancelled()) {
            return response()->json([
                'message' => 'Order cannot be cancelled'
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        // Restore stock
        foreach ($order->orderItems as $orderItem) {
            $product = $orderItem->product;
            if ($product && $product->manage_stock) {
                $product->increment('stock_quantity', $orderItem->quantity);
                $product->update(['in_stock' => true]);
            }
        }

        return response()->json([
            'message' => 'Order cancelled successfully',
            'order' => $order
        ]);
    }

    /**
     * Get vendor orders
     */
    public function vendorOrders(Request $request)
    {
        $vendor = $request->user()->vendor;
        
        if (!$vendor) {
            return response()->json(['message' => 'Vendor profile not found'], 404);
        }

        $orders = Order::whereHas('orderItems', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })
        ->with(['orderItems' => function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id)->with('product');
        }, 'user'])
        ->orderBy('created_at', 'desc')
        ->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }

    /**
     * Get all orders for admin
     */
    public function adminOrders(Request $request)
    {
        $orders = Order::with(['orderItems.product', 'orderItems.vendor', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }
}
