<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tkt_statuses')->insert([
            ['name' => 'Abierto', 'color' => '#2196F3' ,'idcia' => 1],
            ['name' => 'Pendiente aprobación', 'color' => '#FB8C00' ,'idcia' => 1],
            ['name' => 'Asignado', 'color' => '#8E24AA' ,'idcia' => 1],
            ['name' => 'En proceso', 'color' => '#FDD835' ,'idcia' => 1],
            ['name' => 'En espera', 'color' => '#9E9E9E' ,'idcia' => 1],
            ['name' => 'Resuelto', 'color' => '#43A047' ,'idcia' => 1],
            ['name' => 'Cerrado', 'color' => '#424242' ,'idcia' => 1],
        ]);
    }
}
