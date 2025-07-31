<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techVendor = \App\Models\Vendor::where('slug', 'tech-world')->first();
        $fashionVendor = \App\Models\Vendor::where('slug', 'fashion-hub')->first();
        
        $electronicsCategory = \App\Models\Category::where('slug', 'electronics')->first();
        $smartphonesCategory = \App\Models\Category::where('slug', 'smartphones')->first();
        $laptopsCategory = \App\Models\Category::where('slug', 'laptops')->first();
        $clothingCategory = \App\Models\Category::where('slug', 'clothing')->first();

        $products = [
            // Tech World Products
            [
                'vendor_id' => $techVendor->id,
                'category_id' => $smartphonesCategory->id,
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'The latest iPhone with cutting-edge technology, advanced camera system, and powerful A17 Pro chip.',
                'short_description' => 'Latest iPhone with advanced features',
                'sku' => 'TECH-IP15-PRO',
                'price' => 1199.00,
                'sale_price' => 1099.00,
                'stock_quantity' => 25,
                'manage_stock' => true,
                'in_stock' => true,
                'images' => [],
                'status' => 'published',
                'featured' => true,
                'avg_rating' => 4.8,
                'reviews_count' => 156,
            ],
            [
                'vendor_id' => $techVendor->id,
                'category_id' => $smartphonesCategory->id,
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Premium Android smartphone with S Pen, exceptional camera capabilities, and all-day battery life.',
                'short_description' => 'Premium Android with S Pen',
                'sku' => 'TECH-SGS24-ULTRA',
                'price' => 1299.00,
                'stock_quantity' => 18,
                'manage_stock' => true,
                'in_stock' => true,
                'images' => [],
                'status' => 'published',
                'featured' => true,
                'avg_rating' => 4.7,
                'reviews_count' => 89,
            ],
            [
                'vendor_id' => $techVendor->id,
                'category_id' => $laptopsCategory->id,
                'name' => 'MacBook Pro 16-inch',
                'slug' => 'macbook-pro-16-inch',
                'description' => 'Professional laptop with M3 Max chip, stunning Liquid Retina XDR display, and exceptional performance.',
                'short_description' => 'Professional laptop with M3 Max chip',
                'sku' => 'TECH-MBP16-M3',
                'price' => 2499.00,
                'stock_quantity' => 12,
                'manage_stock' => true,
                'in_stock' => true,
                'images' => [],
                'status' => 'published',
                'featured' => false,
                'avg_rating' => 4.9,
                'reviews_count' => 67,
            ],
            [
                'vendor_id' => $techVendor->id,
                'category_id' => $laptopsCategory->id,
                'name' => 'Dell XPS 13',
                'slug' => 'dell-xps-13',
                'description' => 'Ultra-portable laptop with Intel Core processors, InfinityEdge display, and premium build quality.',
                'short_description' => 'Ultra-portable premium laptop',
                'sku' => 'TECH-DELL-XPS13',
                'price' => 1399.00,
                'sale_price' => 1199.00,
                'stock_quantity' => 15,
                'manage_stock' => true,
                'in_stock' => true,
                'images' => [],
                'status' => 'published',
                'featured' => true,
                'avg_rating' => 4.6,
                'reviews_count' => 134,
            ],
            [
                'vendor_id' => $techVendor->id,
                'category_id' => $electronicsCategory->id,
                'name' => 'iPad Air',
                'slug' => 'ipad-air',
                'description' => 'Versatile tablet with M2 chip, Apple Pencil support, and all-day battery life for work and creativity.',
                'short_description' => 'Versatile tablet with M2 chip',
                'sku' => 'TECH-IPAD-AIR',
                'price' => 599.00,
                'stock_quantity' => 30,
                'manage_stock' => true,
                'in_stock' => true,
                'images' => [],
                'status' => 'published',
                'featured' => false,
                'avg_rating' => 4.5,
                'reviews_count' => 98,
            ],

            // Fashion Hub Products
            [
                'vendor_id' => $fashionVendor->id,
                'category_id' => $clothingCategory->id,
                'name' => 'Classic Denim Jacket',
                'slug' => 'classic-denim-jacket',
                'description' => 'Timeless denim jacket made from premium cotton denim with vintage wash and comfortable fit.',
                'short_description' => 'Timeless denim jacket, premium cotton',
                'sku' => 'FASH-DENIM-JAC',
                'price' => 89.00,
                'sale_price' => 69.00,
                'stock_quantity' => 45,
                'manage_stock' => true,
                'in_stock' => true,
                'images' => [],
                'status' => 'published',
                'featured' => true,
                'avg_rating' => 4.4,
                'reviews_count' => 78,
            ],
            [
                'vendor_id' => $fashionVendor->id,
                'category_id' => $clothingCategory->id,
                'name' => 'Premium Cotton T-Shirt',
                'slug' => 'premium-cotton-t-shirt',
                'description' => 'Soft and comfortable 100% organic cotton t-shirt with modern fit and sustainable production.',
                'short_description' => '100% organic cotton, sustainable',
                'sku' => 'FASH-COTTON-TEE',
                'price' => 29.00,
                'stock_quantity' => 120,
                'manage_stock' => true,
                'in_stock' => true,
                'images' => [],
                'status' => 'published',
                'featured' => false,
                'avg_rating' => 4.2,
                'reviews_count' => 156,
            ],
            [
                'vendor_id' => $fashionVendor->id,
                'category_id' => $clothingCategory->id,
                'name' => 'Designer Sneakers',
                'slug' => 'designer-sneakers',
                'description' => 'Stylish and comfortable sneakers with premium materials, cushioned sole, and modern design.',
                'short_description' => 'Stylish sneakers, premium materials',
                'sku' => 'FASH-SNEAK-DES',
                'price' => 149.00,
                'stock_quantity' => 60,
                'manage_stock' => true,
                'in_stock' => true,
                'images' => [],
                'status' => 'published',
                'featured' => true,
                'avg_rating' => 4.6,
                'reviews_count' => 92,
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
