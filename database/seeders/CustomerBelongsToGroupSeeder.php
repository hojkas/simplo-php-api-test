<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerBelongsToGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer_customer_group')->truncate();
        
        $groups = CustomerGroup::all();
        $min_groups_per_customer = config('database.seeding.min_groups_per_user');
        $max_groups_per_customer = config('database.seeding.max_groups_per_user');
        
        Customer::all()->each(function($customer) use ($groups, $min_groups_per_customer, $max_groups_per_customer) {
            $customer->groups()->attach(
                $groups->random(rand($min_groups_per_customer, $max_groups_per_customer))->pluck('id')->toArray());
            
        });
    }
}
