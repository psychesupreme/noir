<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the Noir & Bloom application database.
     */
    public function run(): void
    {
        $this->call([
            UserRoleSeeder::class,
            BranchSeeder::class,
            ProductSeeder::class,
            OccasionSeeder::class,
        ]);
    }
}
