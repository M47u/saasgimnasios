<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogrosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('logros')->insert([
            [
                'codigo'          => 'racha_7_dias',
                'nombre'          => 'Una semana seguida',
                'descripcion'     => 'Asististe 7 días consecutivos al gimnasio',
                'criterio'        => 'asistencias_consecutivas',
                'valor_objetivo'  => 7,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'codigo'          => 'racha_30_dias',
                'nombre'          => 'Mes de racha',
                'descripcion'     => 'Asististe 30 días consecutivos al gimnasio',
                'criterio'        => 'asistencias_consecutivas',
                'valor_objetivo'  => 30,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'codigo'          => 'un_mes_activo',
                'nombre'          => 'Primer mes',
                'descripcion'     => 'Completaste tu primer mes como socio',
                'criterio'        => 'meses_activo',
                'valor_objetivo'  => 1,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'codigo'          => 'tres_meses_activo',
                'nombre'          => 'Tres meses',
                'descripcion'     => 'Llevás 3 meses activo en el gimnasio',
                'criterio'        => 'meses_activo',
                'valor_objetivo'  => 3,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'codigo'          => '10_clases',
                'nombre'          => '10 clases asistidas',
                'descripcion'     => 'Asististe a 10 clases grupales',
                'criterio'        => 'clases_asistidas',
                'valor_objetivo'  => 10,
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}