<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role')->insert([
            ['role_name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['role_name' => 'super-admin', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('role_emp')->insert([
            ['role_id' => 2, 'user_id' => 101, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
