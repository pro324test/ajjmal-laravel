<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendor1 = \App\Models\User::where('email', 'vendor1@example.com')->first();
        $vendor2 = \App\Models\User::where('email', 'vendor2@example.com')->first();

        \App\Models\Vendor::create([
            'user_id' => $vendor1->id,
            'shop_name' => 'Tech World',
            'slug' => 'tech-world',
            'description' => 'Your one-stop shop for all electronic needs',
            'address' => '123 Vendor Street, Tech City, TC 12345',
            'phone' => '+1234567890',
            'email' => 'contact@techworld.com',
            'website' => 'https://techworld.com',
            'commission_rate' => 15.00,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        \App\Models\Vendor::create([
            'user_id' => $vendor2->id,
            'shop_name' => 'Fashion Hub',
            'slug' => 'fashion-hub',
            'description' => 'Latest trends in fashion and lifestyle',
            'address' => '456 Vendor Avenue, Fashion City, FC 67890',
            'phone' => '+1234567891',
            'email' => 'contact@fashionhub.com',
            'website' => 'https://fashionhub.com',
            'commission_rate' => 12.00,
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}
