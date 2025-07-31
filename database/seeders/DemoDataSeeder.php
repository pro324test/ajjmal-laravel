<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create vendor users
        $vendor1 = User::create([
            'name' => 'John Doe',
            'email' => 'vendor1@example.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
        ]);

        $vendor2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'vendor2@example.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
        ]);

        // Create customer user
        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // Create vendor records
        $vendorRecord1 = Vendor::create([
            'user_id' => $vendor1->id,
            'store_name' => 'Tech Store',
            'description' => 'Electronics and gadgets store',
            'phone' => '123-456-7890',
            'address' => '123 Tech Street, Silicon Valley',
            'status' => 'approved',
        ]);

        $vendorRecord2 = Vendor::create([
            'user_id' => $vendor2->id,
            'store_name' => 'Fashion Hub',
            'description' => 'Latest fashion and clothing',
            'phone' => '098-765-4321',
            'address' => '456 Fashion Ave, New York',
            'status' => 'approved',
        ]);

        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices and accessories',
            'is_active' => true,
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'description' => 'Fashion and apparel',
            'is_active' => true,
        ]);

        $books = Category::create([
            'name' => 'Books',
            'slug' => 'books',
            'description' => 'Books and educational materials',
            'is_active' => true,
        ]);

        // Create products
        Product::create([
            'vendor_id' => $vendorRecord1->id,
            'category_id' => $electronics->id,
            'name' => 'Smartphone Pro',
            'slug' => 'smartphone-pro',
            'description' => 'Latest smartphone with advanced features and high-quality camera.',
            'short_description' => 'Advanced smartphone with great camera',
            'sku' => 'PHONE-001',
            'price' => 699.99,
            'sale_price' => 599.99,
            'stock_quantity' => 50,
            'status' => 'active',
        ]);

        Product::create([
            'vendor_id' => $vendorRecord1->id,
            'category_id' => $electronics->id,
            'name' => 'Wireless Headphones',
            'slug' => 'wireless-headphones',
            'description' => 'High-quality wireless headphones with noise cancellation.',
            'short_description' => 'Premium wireless headphones',
            'sku' => 'HEAD-001',
            'price' => 199.99,
            'stock_quantity' => 100,
            'status' => 'active',
        ]);

        Product::create([
            'vendor_id' => $vendorRecord2->id,
            'category_id' => $clothing->id,
            'name' => 'Cotton T-Shirt',
            'slug' => 'cotton-t-shirt',
            'description' => 'Comfortable cotton t-shirt available in various colors.',
            'short_description' => '100% cotton comfortable t-shirt',
            'sku' => 'SHIRT-001',
            'price' => 29.99,
            'sale_price' => 24.99,
            'stock_quantity' => 200,
            'status' => 'active',
        ]);

        Product::create([
            'vendor_id' => $vendorRecord2->id,
            'category_id' => $clothing->id,
            'name' => 'Denim Jeans',
            'slug' => 'denim-jeans',
            'description' => 'Classic denim jeans with modern fit.',
            'short_description' => 'Stylish denim jeans',
            'sku' => 'JEANS-001',
            'price' => 79.99,
            'stock_quantity' => 75,
            'status' => 'active',
        ]);

        Product::create([
            'vendor_id' => $vendorRecord1->id,
            'category_id' => $books->id,
            'name' => 'Programming Fundamentals',
            'slug' => 'programming-fundamentals',
            'description' => 'Comprehensive guide to programming fundamentals.',
            'short_description' => 'Learn programming basics',
            'sku' => 'BOOK-001',
            'price' => 49.99,
            'stock_quantity' => 30,
            'status' => 'active',
        ]);
    }
}
