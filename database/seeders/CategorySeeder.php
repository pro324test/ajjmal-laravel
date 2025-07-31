<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and gadgets',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Fashion and apparel',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home improvement and garden supplies',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Books and literature',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        // Create subcategories
        $electronics = \App\Models\Category::where('slug', 'electronics')->first();
        
        \App\Models\Category::create([
            'name' => 'Smartphones',
            'slug' => 'smartphones',
            'description' => 'Mobile phones and accessories',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        \App\Models\Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'description' => 'Portable computers',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
