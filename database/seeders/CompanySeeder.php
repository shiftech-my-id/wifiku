<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'code' => 'shiftnet',
                'name' => 'Shift Net',
                'owner_name' => 'Fahmi',
                'phone' => '085123123123',
                'address' => 'Talaga',
                'registration_datetime' => now(),
                'activation_datetime' => now(),
                'activation_code' => '',
                'active' => true,
            ],
            [
                'id' => 2,
                'code' => 'shiftnet2',
                'name' => 'Shift Net 2',
                'owner_name' => 'Fahmi FR',
                'phone' => '085123123123',
                'address' => 'Talaga',
                'registration_datetime' => now(),
                'activation_datetime' => now(),
                'activation_code' => '',
                'active' => false,
            ],
            [
                'id' => 3,
                'code' => 'shiftnet3',
                'name' => 'Shift Net 3',
                'owner_name' => 'FFR',
                'phone' => '085123123123',
                'address' => 'Talaga',
                'registration_datetime' => now(),
                'activation_datetime' => now(),
                'activation_code' => '',
                'active' => true,
            ],
        ];

        DB::table('companies')->insert($items);
    }
}
