<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@deshibid.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '01700000000',
            'email_verified_at' => now(),
        ]);

        // Demo Seller User
        User::create([
            'name' => 'Demo Seller',
            'email' => 'seller@deshibid.com',
            'password' => Hash::make('seller123'),
            'role' => 'seller',
            'status' => 'active',
            'phone' => '01700000001',
            'email_verified_at' => now(),
        ]);

        // Demo Bidder User
        User::create([
            'name' => 'Demo Bidder',
            'email' => 'bidder@deshibid.com',
            'password' => Hash::make('bidder123'),
            'role' => 'bidder',
            'status' => 'active',
            'phone' => '01700000002',
            'email_verified_at' => now(),
        ]);

        // Additional Seller for Testing
        User::create([
            'name' => 'Karim Seller',
            'email' => 'karim@deshibid.com',
            'password' => Hash::make('password'),
            'role' => 'seller',
            'status' => 'active',
            'phone' => '01711111111',
            'email_verified_at' => now(),
        ]);

        // Additional Bidder for Testing
        User::create([
            'name' => 'Rahim Bidder',
            'email' => 'rahim@deshibid.com',
            'password' => Hash::make('password'),
            'role' => 'bidder',
            'status' => 'active',
            'phone' => '01722222222',
            'email_verified_at' => now(),
        ]);
    }
}