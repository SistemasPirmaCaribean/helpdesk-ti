<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tkt_categories')->insert([
            // ======================
            // AGRUPACION
            // ======================
            ['name' => 'Desarrollo de sistemas administrativos', 'idcia' => 1], // Sergio
            ['name' => 'Desarrollo de sistemas operativos', 'idcia' => 1], // Rene

            ['name' => 'Soporte sistemas administrativos', 'idcia' => 1], // Sergio
            ['name' => 'Soporte sistemas operativos', 'idcia' => 1], // Rene

            ['name' => 'Internet y red', 'idcia' => 1], // Lupe
            ['name' => 'Equipo de cómputo', 'idcia' => 1], // Pedro
            ['name' => 'Instalación de software', 'idcia' => 1], // Pedro
            ['name' => 'Carpetas compartidas', 'idcia' => 1], // Pedro

            ['name' => 'Telefonía', 'idcia' => 1], // Lupe
            ['name' => 'Radios de comunicación', 'idcia' => 1], // Lupe

            ['name' => 'Sistema CONTPAQi', 'idcia' => 1], // Lupe

            ['name' => 'Otros', 'idcia' => 1]

            // ======================
            // DESARROLLO
            // ======================
            // ['name' => 'Desarrollo - Requerimiento', 'idcia' => 1],
            // ['name' => 'Desarrollo - Bug / Error', 'idcia' => 1],
            // ['name' => 'Desarrollo - Nueva funcionalidad', 'idcia' => 1],
            // ['name' => 'Desarrollo - Mejora', 'idcia' => 1],
            // ['name' => 'Desarrollo - Base de datos', 'idcia' => 1],
            // ['name' => 'Desarrollo - Integración', 'idcia' => 1],
            // ['name' => 'Desarrollo - Documentación', 'idcia' => 1],

            // ======================
            // SOPORTE
            // ======================
            // ['name' => 'Soporte - Problema equipo de computo', 'idcia' => 1],
            // ['name' => 'Soporte - Problemas de acceso', 'idcia' => 1],
            // ['name' => 'Soporte - Cambio de contraseña', 'idcia' => 1],
            // ['name' => 'Soporte - Instalación de software', 'idcia' => 1],
            // ['name' => 'Soporte - Capacitación', 'idcia' => 1],
            // ['name' => 'Soporte - Error de usuario', 'idcia' => 1],
            // ['name' => 'Soporte - Mantenimiento preventivo', 'idcia' => 1],
            // ['name' => 'Soporte - Mantenimiento correctivo', 'idcia' => 1],
            // ['name' => 'Soporte - Actualización de software', 'idcia' => 1],
            // ['name' => 'Soporte - Respaldo y recuperación', 'idcia' => 1],
            // ['name' => 'Soporte - Carpeta compartida', 'idcia' => 1],
            // ['name' => 'Soporte - Configuración de impresora', 'idcia' => 1],
            // ['name' => 'Soporte - Configuración de VPN', 'idcia' => 1],

            // ======================
            // TELECOM / REDES
            // ======================
            // ['name' => 'Telecom - Falla de internet', 'idcia' => 1],
            // ['name' => 'Telecom - Problemas de WiFi', 'idcia' => 1],
            // ['name' => 'Telecom - Configuración de red', 'idcia' => 1],
            // ['name' => 'Telecom - Cableado estructurado', 'idcia' => 1],
            // ['name' => 'Telecom - Configuración de correo', 'idcia' => 1],
            // ['name' => 'Telecom - Telefonia Celular', 'idcia' => 1],
            // ['name' => 'Telecom - Problemas de teléfono celular', 'idcia' => 1],
            // ['name' => 'Telecom - Configuración de VPN', 'idcia' => 1],

            // ======================
            // CONTPAQi (COMPAQ)
            // ======================
            // ['name' => 'CONTPAQi - Error sistema', 'idcia' => 1],
            // ['name' => 'CONTPAQi - Instalación', 'idcia' => 1],
            // ['name' => 'CONTPAQi - Actualización', 'idcia' => 1],
            // ['name' => 'CONTPAQi - Timbrado', 'idcia' => 1],
            // ['name' => 'CONTPAQi - Consultoría', 'idcia' => 1],
            // ['name' => 'CONTPAQi - Configuración empresa', 'idcia' => 1],
            // ['name' => 'CONTPAQi - Configuración usuario', 'idcia' => 1],
            // ['name' => 'CONTPAQi - Respaldo y recuperación', 'idcia' => 1],
            // ['name' => 'CONTPAQi - Reportes', 'idcia' => 1],

            // ======================
            // GENERALES (mantén algunos)
            // ======================
            // ['name' => 'Hardware', 'idcia' => 1],
            // ['name' => 'Software', 'idcia' => 1],
            // ['name' => 'Redes', 'idcia' => 1],
            // ['name' => 'Seguridad', 'idcia' => 1],
            // ['name' => 'Otros', 'idcia' => 1],
        ]);
    }
}
