<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@noirbloom.com'],
            [
                'name' => 'Atelier Admin Control',
                'password' => Hash::make('secret123'),
                'account_tier' => 'admin',
                'phone_number' => '0755667788'
            ]
        );
    }
}