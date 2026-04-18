<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tkt_departament_users')->insert([
            [
                'iddepartament' => 2,
                'iduser' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'iddepartament' => 2,
                'iduser' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'iddepartament' => 2,
                'iduser' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
