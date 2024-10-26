<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Review;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 50; $i++) {
            $customer = User::role('customer')->inRandomOrder()->first();
            $rating = $i < 32 ? 5 : $faker->numberBetween(1, 4);
            $comment = $faker->paragraphs(5, true);

            $adminId = $faker->boolean(70) ? 1 : null;
            $reply = $adminId ? ($faker->boolean(70) ? $faker->paragraphs(5, true) : null) : null;

            Review::create([
                'customer_id' => $customer->id,
                'admin_id' => 1,
                'rating' => $rating,
                'comment' => $comment,
                'reply' => $reply,
            ]);
        }
    }
}
