<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Package;
use App\Models\Transaction;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $startDate = Carbon::createFromDate(2024, 1, 1);
        $now = Carbon::now();

        while ($startDate->lte($now)) {
            for ($i = 0; $i < 10; $i++) {
                $customer = User::role('customer')->inRandomOrder()->first();
                $package = Package::inRandomOrder()->first();

                $invoiceNumber = Transaction::count() + 1;
                $invoiceNumber = str_pad($invoiceNumber, 3, '0', STR_PAD_LEFT);
                $invoice = 'TRC-' . $invoiceNumber;

                $dateTime = $startDate->copy()->addMinutes($i * 10);

                if ($dateTime->lte($now)) {
                    $status = 'retrieved';
                } else {
                    $statusOptions = ['pending', 'processed', 'completed', 'retrieved'];
                    $status = $statusOptions[mt_rand(0, 2)];
                }

                $price = $package->price * $faker->numberBetween(1, 20);
                $amount = $status == 'retrieved' ? $price : mt_rand(0, $price);
                $amount = min($amount, $price);

                $coupon = Coupon::where('temporary', $customer->id)
                                ->where('amount', '<', 10)
                                ->where('status', 'not used')
                                ->first();
                if ($coupon) {
                    $coupon->increment('amount');
                } else {
                    Coupon::create([
                        'customer_id' => $customer->id,
                        'customer_name' => $customer->name,
                        'customer_phone' => $customer->phone,
                        'amount' => 1,
                        'status' => 'not used',
                        'temporary' => $customer->id
                    ]);
                }

                $transaction = Transaction::create([
                    'invoice' => $invoice,
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone,
                    'package' => $package->type,
                    'day' => $dateTime->format('l'),
                    'date' => $dateTime->format('d F Y'),
                    'weight' => $faker->numberBetween(1, 10),
                    'price' => $price,
                    'coupon' => 'not used',
                    'status' => $status,
                    'created_at' => $dateTime,
                ]);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'customer_id' => $customer->id,
                    'package_id' => $package->id,
                    'coupon_id' => $coupon ? $coupon->id : null,
                    'amount' => $amount,
                ]);
            }

            $startDate->addDay();
        }
    }
}
