<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::truncate();

        $faker = \Faker\Factory::create();

        $customerCount = config('database.seeding.customer_group_count');

        for ($i = 0; $i < $customerCount; $i++) {
            Customer::create([
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'phone_number' => $faker->phoneNumber(),
                'email' => $faker->email()
            ]);
        }
    }
}
