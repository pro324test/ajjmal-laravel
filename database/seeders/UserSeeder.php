<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create vendor users
        \App\Models\User::create([
            'name' => 'John Vendor',
            'email' => 'vendor1@example.com',
            'password' => 'password',
            'role' => 'vendor',
            'phone' => '+1234567890',
            'address' => '123 Vendor Street, City, State',
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'name' => 'Jane Vendor',
            'email' => 'vendor2@example.com',
            'password' => 'password',
            'role' => 'vendor',
            'phone' => '+1234567891',
            'address' => '456 Vendor Avenue, City, State',
            'is_active' => true,
        ]);

        // Create customer users
        \App\Models\User::create([
            'name' => 'Customer One',
            'email' => 'customer1@example.com',
            'password' => 'password',
            'role' => 'customer',
            'phone' => '+1234567892',
            'address' => '789 Customer Lane, City, State',
            'is_active' => true,
        ]);

        \App\Models\User::create([
            'name' => 'Customer Two',
            'email' => 'customer2@example.com',
            'password' => 'password',
            'role' => 'customer',
            'phone' => '+1234567893',
            'address' => '321 Customer Boulevard, City, State',
            'is_active' => true,
        ]);
    }
}
