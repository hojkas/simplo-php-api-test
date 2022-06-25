<?php

namespace Database\Seeders;

use App\Models\Customer;
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

        $customerCount = config('database.seeding.customer_group_count');

        for ($i = 0; $i < $customerCount; $i++) {
            Customer::factory()->create();
        }
    }
}
