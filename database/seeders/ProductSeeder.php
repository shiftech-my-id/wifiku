<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Paket 50K', 'company_id' => 1, 'price' => 50000],
            ['name' => 'Paket 100K', 'company_id' => 1, 'price' => 100000],
            ['name' => 'Paket 150K', 'company_id' => 1, 'price' => 150000],
        ];

        DB::table('products')->insert($items);
    }
}
