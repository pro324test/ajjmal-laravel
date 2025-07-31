<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function index(Request $request)
    {
        $query = Category::active();

        // Get only root categories if requested
        if ($request->get('root_only', false)) {
            $query->root();
        }

        // Include children relationships
        if ($request->get('with_children', false)) {
            $query->with('children');
        }

        $categories = $query->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    /**
     * Get a specific category
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products' => function ($query) {
            $query->published()->inStock()->with('vendor');
        }]);

        return response()->json([
            'category' => $category
        ]);
    }

    /**
     * Create a new category (admin only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'sometimes|string',
            'parent_id' => 'sometimes|exists:categories,id',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $request->image,
            'parent_id' => $request->parent_id,
            'is_active' => $request->is_active ?? true,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    /**
     * Update a category (admin only)
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image' => 'sometimes|string',
            'parent_id' => 'sometimes|exists:categories,id',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = $request->only([
            'name', 'description', 'image', 'parent_id', 'is_active', 'sort_order'
        ]);

        if (isset($updateData['name'])) {
            $updateData['slug'] = Str::slug($updateData['name']);
        }

        $category->update($updateData);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    /**
     * Delete a category (admin only)
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with products'
            ], 422);
        }

        // Check if category has child categories
        if ($category->children()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with subcategories'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
