<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('usuarios')->insert([
            [
                'idperfil' => 1,
                'idcia' => 1,
                'nombre' => 'Admin',
                'usuario' => 'admin',
                'hash' => '$2y$12$BKYaezMDsYm120xaRsEH7upGhGLSyzaBCiAint07QjREn2ezLG47K',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idperfil' => 1,
                'idcia' => 1,
                'nombre' => 'Gerente Sistemas',
                'usuario' => 'gerente.sistemas',
                'hash' => '$2y$12$BKYaezMDsYm120xaRsEH7upGhGLSyzaBCiAint07QjREn2ezLG47K',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idperfil' => 2,
                'idcia' => 1,
                'nombre' => 'MARTINEZ DURAN PEDRO',
                'usuario' => 'sistemas.soporte',
                'hash' => '$2y$12$BKYaezMDsYm120xaRsEH7upGhGLSyzaBCiAint07QjREn2ezLG47K',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idperfil' => 2,
                'idcia' => 1,
                'nombre' => 'RODRIGUEZ MUÑOZ GUADALUPE',
                'usuario' => 'sistemas.redes&telecom',
                'hash' => '$2y$12$BKYaezMDsYm120xaRsEH7upGhGLSyzaBCiAint07QjREn2ezLG47K',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idperfil' => 2,
                'idcia' => 1,
                'nombre' => 'CASTILLO GARCIA RENE',
                'usuario' => 'sistemas.desarrollo',
                'hash' => '$2y$12$BKYaezMDsYm120xaRsEH7upGhGLSyzaBCiAint07QjREn2ezLG47K',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idperfil' => 2,
                'idcia' => 1,
                'nombre' => 'HERNANDEZ TAVARES SERGIO',
                'usuario' => 'sistemas.desarrollo2',
                'hash' => '$2y$12$BKYaezMDsYm120xaRsEH7upGhGLSyzaBCiAint07QjREn2ezLG47K',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idperfil' => 3,
                'idcia' => 1,
                'nombre' => 'Usuario Ventas',
                'usuario' => 'usuario.ventas',
                'hash' => '$2y$12$BKYaezMDsYm120xaRsEH7upGhGLSyzaBCiAint07QjREn2ezLG47K',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'idperfil' => 4,
                'idcia' => 1,
                'nombre' => 'Gerente de Ventas',
                'usuario' => 'gerente.ventas',
                'hash' => '$2y$12$BKYaezMDsYm120xaRsEH7upGhGLSyzaBCiAint07QjREn2ezLG47K',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idperfil' => 5,
                'idcia' => 1,
                'nombre' => 'Coordinador',
                'usuario' => 'coordinador.ventas',
                'hash' => '$2y$12$BKYaezMDsYm120xaRsEH7upGhGLSyzaBCiAint07QjREn2ezLG47K',
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),

            ]
        ]);

    }
}
