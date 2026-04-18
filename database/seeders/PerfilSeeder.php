<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('perfils')->insert([
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Perfil con acceso total al sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Tecnico',
                'descripcion' => 'Tecnico encargado de resolver los tickets asignados',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Usuario',
                'descripcion' => 'Usuario que crea los tickets y consulta su estado',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gerente',
                'descripcion' => 'Gerente encargado aprovar los tickets y supervisar el trabajo de los tecnicos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Coordinador',
                'descripcion' => 'Coordinador revisa los tickets y crea tickets',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
