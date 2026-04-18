<?php

namespace Database\Seeders;

use App\Models\Perfil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $this->call([
            PerfilSeeder::class,
            UsuariosSeeder::class,

            // Ticket related seeders
            CategorySeeder::class,
            DepartamentSeeder::class,
            PrioritySeeder::class,
            StatusSeeder::class,
            DepartamentUserSeeder::class,
            CategoryTechnicianSeeder::class,
        ]);
    }
}
