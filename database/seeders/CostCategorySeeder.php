<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Listrik', 'company_id' => 1],
            ['name' => 'Internet', 'company_id' => 1],
            ['name' => 'Gaji Karyawan', 'company_id' => 1],
        ];

        DB::table('cost_categories')->insert($items);
    }
}
