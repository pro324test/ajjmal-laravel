<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Models\Product;
use Livewire\Component;

class ShoppingCart extends Component
{
    public function addToCart($productId, $quantity = 1)
    {
        // In a real app, you'd check if user is authenticated
        // For demo, we'll use a dummy user ID
        $userId = 1;
        
        $product = Product::findOrFail($productId);
        
        if (!$product->inStock()) {
            session()->flash('error', 'Product is out of stock.');
            return;
        }
        
        $cartItem = CartItem::where('user_id', $userId)
                           ->where('product_id', $productId)
                           ->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity <= $product->stock_quantity) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                session()->flash('error', 'Not enough stock available.');
                return;
            }
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
        
        session()->flash('message', 'Product added to cart!');
    }
    
    public function updateQuantity($cartItemId, $quantity)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        
        if ($quantity <= 0) {
            $cartItem->delete();
            session()->flash('message', 'Item removed from cart.');
            return;
        }
        
        if ($quantity <= $cartItem->product->stock_quantity) {
            $cartItem->update(['quantity' => $quantity]);
            session()->flash('message', 'Cart updated.');
        } else {
            session()->flash('error', 'Not enough stock available.');
        }
    }
    
    public function removeFromCart($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();
        session()->flash('message', 'Item removed from cart.');
    }
    
    public function clearCart()
    {
        // Demo user ID
        $userId = 1;
        CartItem::where('user_id', $userId)->delete();
        session()->flash('message', 'Cart cleared.');
    }
    
    public function render()
    {
        // Demo user ID - in real app, use auth()->id()
        $userId = 1;
        
        $cartItems = CartItem::with(['product', 'product.vendor'])
                            ->where('user_id', $userId)
                            ->get();
        
        $total = $cartItems->sum(function ($item) {
            return $item->getTotalPrice();
        });
        
        return view('livewire.shopping-cart', [
            'cartItems' => $cartItems,
            'total' => $total,
        ])->layout('layouts.app');
    }
}
