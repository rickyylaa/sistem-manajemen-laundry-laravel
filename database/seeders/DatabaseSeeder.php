<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([RoleSeeder::class]);
        $this->call([UserSeeder::class]);
        $this->call([PackageSeeder::class]);
        $this->call([TransactionSeeder::class]);
        $this->call([CouponSeeder::class]);
        $this->call([ReviewSeeder::class]);
    }
}
