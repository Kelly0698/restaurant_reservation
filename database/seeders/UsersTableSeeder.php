<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Kelly',
                'role_id' => 1,
                'email' => '75262@siswa.unimas.my',
                'phone_num' => '+601118940965',
                'password' => Hash::make('123456'),
            ],
        ]);
    }
}