<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tkt_departaments')->insert([
            ['name' => 'ADQUISICIONES', 'idcia' => 1],
            ['name' => 'CADENA SUMINISTRO', 'idcia' => 1],
            ['name' => 'CALIDAD', 'idcia' => 1],
            ['name' => 'COMERCIO INTERNACIONAL', 'idcia' => 1],
            ['name' => 'DESARROLLO ORGANIZACIONAL', 'idcia' => 1],
            ['name' => 'DISEÑO Y DESARROLLO', 'idcia' => 1],
            ['name' => 'FINANZAS', 'idcia' => 1],
            ['name' => 'INGENIERIA', 'idcia' => 1],
            ['name' => 'JURIDICO', 'idcia' => 1],
            ['name' => 'MANTENIMIENTO', 'idcia' => 1],
            ['name' => 'PRODUCCION ARMADO CORTE', 'idcia' => 1],
            ['name' => 'PRODUCCION ARMADO PESPUNTE', 'idcia' => 1],
            ['name' => 'PRODUCCION ENSAMBLE', 'idcia' => 1],
            ['name' => 'PROGRAMACION', 'idcia' => 1],
            ['name' => 'PUBLICIDAD', 'idcia' => 1],
            ['name' => 'RECURSOS HUMANOS', 'idcia' => 1],
            ['name' => 'RELACIONES PUBLICAS', 'idcia' => 1],
            ['name' => 'SISTEMAS TI', 'idcia' => 1],
        ]);
    }
}
