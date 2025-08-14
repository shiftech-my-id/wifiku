<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'username' => 'admin',
                'name' => 'Fahmi Fauzi Rahman',
                'company_id' => 1,
            ],
            [
                'id' => 2,
                'username' => 'admin',
                'name' => 'John Doe',
                'company_id' => 2,
            ],
        ];

        $password = Hash::make('123456');

        foreach ($users as &$user) {
            $user['password'] = $password;
            $user['active'] = true;
        }

        DB::table('users')->insert($users);
    }
}
