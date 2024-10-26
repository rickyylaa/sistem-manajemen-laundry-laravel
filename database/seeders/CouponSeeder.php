<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::create([
            'customer_id' => 4,
            'customer_name' => 'Pelanggan',
            'customer_phone' => '087789616639',
            'amount' => 10,
            'status' => 'not used',
            'temporary' => 4
        ]);
    }
}
