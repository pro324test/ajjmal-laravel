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
     * Get all products with pagination and filtering
     */
    public function index(Request $request)
    {
        $query = Product::with(['vendor', 'category'])
            ->published()
            ->inStock();

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by vendor
        if ($request->has('vendor')) {
            $query->whereHas('vendor', function ($q) use ($request) {
                $q->where('slug', $request->vendor);
            });
        }

        // Search by name/description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter featured products
        if ($request->has('featured') && $request->featured) {
            $query->featured();
        }

        // Sort products
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['name', 'price', 'created_at', 'avg_rating'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate($request->get('per_page', 15));

        return response()->json($products);
    }

    /**
     * Get a specific product
     */
    public function show(Product $product)
    {
        $product->load(['vendor', 'category']);
        
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     * Get vendor's products
     */
    public function vendorProducts(Request $request)
    {
        $vendor = $request->user()->vendor;
        
        if (!$vendor) {
            return response()->json(['message' => 'Vendor profile not found'], 404);
        }

        $products = Product::where('vendor_id', $vendor->id)
            ->with(['category'])
            ->paginate($request->get('per_page', 15));

        return response()->json($products);
    }

    /**
     * Create a new product
     */
    public function store(Request $request)
    {
        $vendor = $request->user()->vendor;
        
        if (!$vendor || !$vendor->isApproved()) {
            return response()->json(['message' => 'Vendor not approved'], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'sometimes|string|max:500',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'sometimes|numeric|min:0|lt:price',
            'stock_quantity' => 'sometimes|integer|min:0',
            'manage_stock' => 'sometimes|boolean',
            'weight' => 'sometimes|numeric|min:0',
            'dimensions' => 'sometimes|string',
            'images' => 'sometimes|array',
            'attributes' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::create([
            'vendor_id' => $vendor->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'sku' => $request->sku,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock_quantity' => $request->stock_quantity ?? 0,
            'manage_stock' => $request->manage_stock ?? true,
            'in_stock' => ($request->stock_quantity ?? 0) > 0,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'images' => $request->images ?? [],
            'attributes' => $request->attributes ?? [],
            'status' => 'draft',
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    /**
     * Update a product
     */
    public function update(Request $request, Product $product)
    {
        $vendor = $request->user()->vendor;
        
        if (!$vendor || $product->vendor_id !== $vendor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'short_description' => 'sometimes|string|max:500',
            'sku' => 'sometimes|string|unique:products,sku,' . $product->id,
            'price' => 'sometimes|numeric|min:0',
            'sale_price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'manage_stock' => 'sometimes|boolean',
            'weight' => 'sometimes|numeric|min:0',
            'dimensions' => 'sometimes|string',
            'images' => 'sometimes|array',
            'attributes' => 'sometimes|array',
            'status' => 'sometimes|in:draft,published,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = $request->only([
            'category_id', 'name', 'description', 'short_description', 'sku',
            'price', 'sale_price', 'stock_quantity', 'manage_stock', 'weight',
            'dimensions', 'images', 'attributes', 'status'
        ]);

        if (isset($updateData['name'])) {
            $updateData['slug'] = Str::slug($updateData['name']);
        }

        if (isset($updateData['stock_quantity'])) {
            $updateData['in_stock'] = $updateData['stock_quantity'] > 0;
        }

        $product->update($updateData);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }

    /**
     * Delete a product
     */
    public function destroy(Request $request, Product $product)
    {
        $vendor = $request->user()->vendor;
        
        if (!$vendor || $product->vendor_id !== $vendor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
