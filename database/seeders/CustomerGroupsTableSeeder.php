<?php

namespace Database\Seeders;

use App\Models\CustomerGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CustomerGroup::truncate();

        $customerGroupCount = config('database.seeding.customer_group_count');

        for($i = 1; $i <= $customerGroupCount; $i++) {
            CustomerGroup::create([
                'name' => "Group {$i}"
            ]);
        }
    }
}
