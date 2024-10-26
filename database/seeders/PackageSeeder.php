<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Package::create([
            'type' => 'Cuci Kering',
            'price' => '2000',
            'status' => 'active',
        ]);

        Package::create([
            'type' => 'Cuci Basah',
            'price' => '1500',
            'status' => 'active',
        ]);

        Package::create([
            'type' => 'Setrika',
            'price' => '2000',
            'status' => 'active',
        ]);

        Package::create([
            'type' => 'Cuci + Setrika',
            'price' => '3000',
            'status' => 'active',
        ]);

        Package::create([
            'type' => 'Cuci + Ekpres',
            'price' => '5000',
            'status' => 'active',
        ]);

        Package::create([
            'type' => 'Setrika + Ekpres',
            'price' => '5000',
            'status' => 'active',
        ]);

        Package::create([
            'type' => 'Cuci + Setrika + Ekpres',
            'price' => '8000',
            'status' => 'active',
        ]);
    }
}
