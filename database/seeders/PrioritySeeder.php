<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tkt_priorities')->insert([
            ['name' => 'Baja', 'sla_hours' => 48, 'color' => '#00FF00', 'idcia' => 1],
            ['name' => 'Media', 'sla_hours' => 24, 'color' => '#FFFF00', 'idcia' => 1],
            ['name' => 'Alta', 'sla_hours' => 8, 'color' => '#FFA500', 'idcia' => 1],
            ['name' => 'Crítica', 'sla_hours' => 4, 'color' => '#FF0000', 'idcia' => 1],
        ]);
    }
}
