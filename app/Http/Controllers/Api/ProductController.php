<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with(['vendor', 'category'])
            ->where('status', 'active')
            ->paginate(15);

        return response()->json([
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        if (!$user->isVendor() || !$user->vendor || !$user->vendor->isApproved()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'attributes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = Product::create([
            'vendor_id' => $user->vendor->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'sku' => $request->sku,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'weight' => $request->weight,
            'images' => $request->images,
            'attributes' => $request->attributes,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load(['vendor', 'category']),
        ], 201);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json([
            'product' => $product->load(['vendor', 'category']),
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $user = $request->user();
        
        if (!$user->isVendor() || $product->vendor_id !== $user->vendor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'sometimes|required|string|unique:products,sku,' . $product->id,
            'price' => 'sometimes|required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'attributes' => 'nullable|array',
            'status' => 'sometimes|in:active,inactive,out_of_stock',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->all();
        if (isset($data['name']) && $data['name'] !== $product->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        $product->update($data);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->load(['vendor', 'category']),
        ]);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Request $request, Product $product)
    {
        $user = $request->user();
        
        if (!$user->isVendor() || $product->vendor_id !== $user->vendor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }

    /**
     * Get products for the authenticated vendor.
     */
    public function vendorProducts(Request $request)
    {
        $user = $request->user();
        
        if (!$user->isVendor() || !$user->vendor) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $products = Product::with(['category'])
            ->where('vendor_id', $user->vendor->id)
            ->paginate(15);

        return response()->json([
            'products' => $products,
        ]);
    }
}
