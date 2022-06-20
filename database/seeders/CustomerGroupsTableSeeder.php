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

        for($i = 1; $i <= 10; $i++) {
            CustomerGroup::create([
                'name' => "Group {$i}"
            ]);
        }
    }
}
