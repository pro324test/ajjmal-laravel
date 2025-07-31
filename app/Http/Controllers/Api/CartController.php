<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Get user's cart items
     */
    public function index(Request $request)
    {
        $cartItems = Cart::where('user_id', $request->user()->id)
            ->with(['product' => function ($query) {
                $query->with('vendor');
            }])
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->effective_price;
        });

        return response()->json([
            'cart_items' => $cartItems,
            'total' => $total,
            'count' => $cartItems->sum('quantity')
        ]);
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'options' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::find($request->product_id);

        // Check if product is available
        if (!$product->in_stock || $product->status !== 'published') {
            return response()->json([
                'message' => 'Product is not available'
            ], 422);
        }

        // Check stock quantity
        if ($product->manage_stock && $request->quantity > $product->stock_quantity) {
            return response()->json([
                'message' => 'Insufficient stock. Available: ' . $product->stock_quantity
            ], 422);
        }

        // Check if item already exists in cart
        $existingCartItem = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingCartItem) {
            $newQuantity = $existingCartItem->quantity + $request->quantity;
            
            // Check stock for updated quantity
            if ($product->manage_stock && $newQuantity > $product->stock_quantity) {
                return response()->json([
                    'message' => 'Cannot add more items. Stock limit reached.'
                ], 422);
            }

            $existingCartItem->update([
                'quantity' => $newQuantity,
            ]);

            $cartItem = $existingCartItem;
        } else {
            $cartItem = Cart::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'options' => $request->options ?? [],
            ]);
        }

        $cartItem->load('product');

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_item' => $cartItem
        ], 201);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = $cart->product;

        // Check stock quantity
        if ($product->manage_stock && $request->quantity > $product->stock_quantity) {
            return response()->json([
                'message' => 'Insufficient stock. Available: ' . $product->stock_quantity
            ], 422);
        }

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'message' => 'Cart item updated successfully',
            'cart_item' => $cart
        ]);
    }

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, Cart $cart)
    {
        if ($cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cart->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully'
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear(Request $request)
    {
        Cart::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'message' => 'Cart cleared successfully'
        ]);
    }
}
