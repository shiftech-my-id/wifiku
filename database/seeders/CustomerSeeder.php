<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'company_id' => 1,
                'code' => 'PL-0001',
                'name' => 'Yudi Heriadi',
                'active' => true,
            ],
            [
                'id' => 2,
                'company_id' => 1,
                'code' => 'PL-0002',
                'name' => 'Beni Sujana',
                'active' => true,
            ],
        ];

        DB::table('customers')->insert($items);
    }
}
