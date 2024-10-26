<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        User::create([
            'username' => 'admin',
            'name' => 'Admin',
            'password' => bcrypt('1'),
            'phone' => '081234567890',
            'gender' => 'male',
            'address' => 'Simpang Pulai',
            'status' => 'active',
        ])->assignRole('admin');

        User::create([
            'username' => 'pemilik',
            'name' => 'Pemilik',
            'password' => bcrypt('1'),
            'phone' => '081234567890',
            'gender' => 'female',
            'address' => 'Simpang Pulai',
            'status' => 'active',
        ])->assignRole('owner');

        User::create([
            'username' => 'karyawan1',
            'name' => 'Karyawan 1',
            'password' => bcrypt('1'),
            'phone' => '081234567890',
            'gender' => 'female',
            'address' => 'Simpang Pulai',
            'status' => 'active',
        ])->assignRole('employee');

        User::create([
            'username' => 'karyawan2',
            'name' => 'Karyawan 2',
            'password' => bcrypt('1'),
            'phone' => '081234567890',
            'gender' => 'female',
            'address' => 'Simpang Pulai',
            'status' => 'active',
        ])->assignRole('employee');

        User::create([
            'username' => 'pelanggan',
            'name' => 'Pelanggan',
            'password' => bcrypt('1'),
            'phone' => '087789616639',
            'gender' => 'female',
            'address' => 'Simpang Pulai',
            'status' => 'active',
        ])->assignRole('customer');

        for ($i = 1; $i <= 72; $i++) {
            $name = $faker->firstName;
            $username = Str::slug($name, '') . rand(0, 999);

            User::create([
                'username' => $username,
                'name' => $name,
                'password' => Hash::make('1'),
                'phone' => $faker->numerify('08##########'),
                'gender' => $faker->randomElement(['male', 'female']),
                'address' => $faker->address,
                'status' => 'active',
            ])->assignRole('customer');
        }
    }
}
