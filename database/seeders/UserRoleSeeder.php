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
            ['email' => 'admin@noirandbloom.co.ke'],
            [
                'name' => 'Atelier Lead Admin',
                'password' => Hash::make('password'),
                'account_tier' => 'admin',
                'phone_number' => '0711000111'
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@noirbloom.com'],
            [
                'name' => 'Atelier Admin Control',
                'password' => Hash::make('password'),
                'account_tier' => 'admin',
                'phone_number' => '0755667788'
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@noirandbloom.co.ke'],
            [
                'name' => 'Atelier Concierge Staff',
                'password' => Hash::make('password'),
                'account_tier' => 'staff',
                'phone_number' => '0722000222'
            ]
        );
    }
}