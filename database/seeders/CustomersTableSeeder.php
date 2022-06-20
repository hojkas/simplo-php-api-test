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

        for ($i = 0; $i < 10; $i++) {
            Customer::create([
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'phone_number' => $faker->phoneNumber(),
                'email' => $faker->email()
            ]);
        }
    }
}
