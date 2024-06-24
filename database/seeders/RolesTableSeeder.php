<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'role_name' => 'ADMIN',
                'level' => 2,
                'status' => 'Enable',
            ],
            [
                'role_name' => 'USER',
                'level' => 2,
                'status' => 'Enable',
            ],
        ]);
    }
}