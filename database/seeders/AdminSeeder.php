<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('admins')->insert([
            'name' => 'AmrHussien',
            'email' => 'amr@gmail.com',
            'phone' => '123456',
            'is_activate' => 1,
            'password' => bcrypt(123123),
        ]);
    }
}
