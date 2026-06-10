<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::updateOrCreate(
            ['code' => 'NB-NBO'],
            [
                'name' => 'Nairobi Central Atelier',
                'location_city' => 'Nairobi',
                'is_active' => true,
            ]
        );

        Branch::updateOrCreate(
            ['code' => 'NB-KBU'],
            [
                'name' => 'Kiambu Ridge Hub',
                'location_city' => 'Kiambu',
                'is_active' => true,
            ]
        );
    }
}