<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. System Administrator Node (Only admin is seeded)
        User::create([
            'name' => 'Atelier Admin Control',
            'email' => 'admin@noirbloom.com',
            'password' => Hash::make('secret123'),
            'account_tier' => 'admin',
            'phone_number' => '0755667788'
        ]);
    }
}