<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin Account
        Admin::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'mobile' => '9876543210',
                'password' => Hash::make('123456'),
                'role' => 'super_admin',
                'status' => 'active',
            ]
        );

        // 2. Restaurant Owner Account (auth via users table)
        $owner = User::updateOrCreate(
            ['email' => 'owner@restaurant.com'],
            [
                'name' => 'Zakir Khan',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'restaurant_owner')->value('id'),
            ]
        );

        // Link existing restaurant to this owner if any exists
        $restaurant = Restaurant::first();
        if ($restaurant && is_null($restaurant->user_id)) {
            $restaurant->update(['user_id' => $owner->id]);
        }
    }
}
