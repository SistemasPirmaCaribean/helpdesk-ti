<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Usuario con id 3 es el técnico de soporte Pedro
        // usuario con id 4 es el técnico de redes y telecomunicaciones Lupe
        // Usuario con id 5 es el técnico de desarrollo Rene
        // usuario con id 6 es el técnico de desarrollo Sergio
        DB::table('tkt_category_technicians')->insert([
            // ======================
            // AGRUPACION
            // ======================
            
            ['idcategory' => 1, 'idtechnician' => 6],
            ['idcategory' => 2, 'idtechnician' => 5],
            ['idcategory' => 3, 'idtechnician' => 6],
            ['idcategory' => 4, 'idtechnician' => 5],
            ['idcategory' => 5, 'idtechnician' => 4],
            ['idcategory' => 6, 'idtechnician' => 3],
            ['idcategory' => 7, 'idtechnician' =>3],
            ['idcategory' => 8, 'idtechnician' => 3],
            ['idcategory' => 9, 'idtechnician' => 4],
            ['idcategory' => 10, 'idtechnician' => 4],
            ['idcategory' => 11, 'idtechnician' => 4],

            // DESARROLLO
            // ['idcategory' => 1, 'idtechnician' => 5],
            // ['idcategory' => 2, 'idtechnician' => 5],
            // ['idcategory' => 3, 'idtechnician' => 5],
            // ['idcategory' => 4, 'idtechnician' => 5],
            // ['idcategory' => 5, 'idtechnician' => 5],
            // ['idcategory' => 6, 'idtechnician' => 5],
            // ['idcategory' => 7, 'idtechnician' => 5],
            // ['idcategory' => 1, 'idtechnician' => 6],
            // ['idcategory' => 2, 'idtechnician' => 6],
            // ['idcategory' => 3, 'idtechnician' => 6],
            // ['idcategory' => 4, 'idtechnician' => 6],
            // ['idcategory' => 5, 'idtechnician' => 6],
            // ['idcategory' => 6, 'idtechnician' => 6],
            // ['idcategory' => 7, 'idtechnician' => 6],

            // SOPORTE
            // ['idcategory' => 8, 'idtechnician' => 3],
            // ['idcategory' => 9, 'idtechnician' => 3],
            // ['idcategory' => 10, 'idtechnician' => 3],
            // ['idcategory' => 11, 'idtechnician' => 3],
            // ['idcategory' => 12, 'idtechnician' => 3],
            // ['idcategory' => 13, 'idtechnician' => 3],
            // ['idcategory' => 14, 'idtechnician' => 3],
            // ['idcategory' => 15, 'idtechnician' => 3],
            // ['idcategory' => 16, 'idtechnician' => 3],
            // ['idcategory' => 17, 'idtechnician' => 3],
            // ['idcategory' => 18, 'idtechnician' => 3],
            // ['idcategory' => 19, 'idtechnician' => 3],
            // ['idcategory' => 20, 'idtechnician' => 3],

            // TELECOM / REDES
            //  ['idcategory' => 21, 'idtechnician' => 4],
            //  ['idcategory' => 22, 'idtechnician' => 4],
            //  ['idcategory' => 23, 'idtechnician' => 4],
            //  ['idcategory' => 24, 'idtechnician' => 4],
            //  ['idcategory' => 25, 'idtechnician' => 4],
            //  ['idcategory' => 26, 'idtechnician' => 4],
            //  ['idcategory' => 27, 'idtechnician' => 4],
            //  ['idcategory' => 28, 'idtechnician' => 4], 

            // CONTPAQi (COMPAQ)
            //  ['idcategory' => 29, 'idtechnician' => 4],
            //  ['idcategory' => 30, 'idtechnician' => 4],
            //  ['idcategory' => 31, 'idtechnician' => 4],
            //  ['idcategory' => 32, 'idtechnician' => 4],
            //  ['idcategory' => 33, 'idtechnician' => 4],
            //  ['idcategory' => 34, 'idtechnician' => 4],
            //  ['idcategory' => 35, 'idtechnician' => 4],
            //  ['idcategory' => 36, 'idtechnician' => 4],
            //  ['idcategory' => 37, 'idtechnician' => 4],
             
        ]);
    }
}
