<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::create([
            'name' => 'Nairobi Central Atelier',
            'code' => 'NB-NBO',
            'location_city' => 'Nairobi',
            'is_active' => true,
        ]);

        Branch::create([
            'name' => 'Kiambu Ridge Hub',
            'code' => 'NB-KBU',
            'location_city' => 'Kiambu',
            'is_active' => true,
        ]);
    }
}