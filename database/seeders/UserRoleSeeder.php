<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Retail Client Node
        User::create([
            'name' => 'Wanjiku Mwangi',
            'email' => 'wanjiku@retail.me',
            'password' => Hash::make('secret123'),
            'account_tier' => 'retail',
            'phone_number' => '0711223344',
            'default_delivery_address' => 'Muthaiga, Old Muthaiga Road Estates, Nairobi',
            'default_region' => 'Nairobi'
        ]);

        // 2. Wholesale Client Node
        User::create([
            'name' => 'Alex Kamau',
            'email' => 'kamau@ateliermarketing.co.ke',
            'password' => Hash::make('secret123'),
            'account_tier' => 'wholesale',
            'phone_number' => '0722123456',
            'kra_pin' => 'A001987654Z',
            'default_delivery_address' => 'Westlands, Delta Corner / PwC Towers, Nairobi',
            'default_region' => 'Nairobi'
        ]);

        // 3. Partner Node (Growers / Logistics Managers)
        User::create([
            'name' => 'Naivasha Growers Alliance',
            'email' => 'logistics@naivashafarms.co.ke',
            'password' => Hash::make('secret123'),
            'account_tier' => 'partners',
            'phone_number' => '0733445566',
            'default_delivery_address' => 'Limuru, Tea Estate Curation Ridge, Kiambu',
            'default_region' => 'Kiambu'
        ]);

        // 4. Atelier Internal Staff Node
        User::create([
            'name' => 'Sarah Lavoine Odhiambo',
            'email' => 'sarah@noirbloom.com',
            'password' => Hash::make('secret123'),
            'account_tier' => 'staff',
            'phone_number' => '0744556677'
        ]);

        // 5. System Administrator Node
        User::create([
            'name' => 'Atelier Admin Control',
            'email' => 'admin@noirbloom.com',
            'password' => Hash::make('secret123'),
            'account_tier' => 'admin',
            'phone_number' => '0755667788'
        ]);
    }
}