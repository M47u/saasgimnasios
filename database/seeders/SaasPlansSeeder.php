<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaasPlansSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('saas_plans')->insert([
            [
                'nombre'              => 'Starter',
                'slug'                => 'starter',
                'precio_mensual'      => 19,
                'precio_anual'        => 190,
                'max_socios'          => 150,
                'max_usuarios'        => 3,
                'max_sucursales'      => 1,
                'limite_ia_mensual'   => 0,
                'modulos_habilitados' => json_encode(['socios','membresias','pagos','caja','asistencias','rutinas','clases','reportes_basicos']),
                'activo'              => 1,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'nombre'              => 'Pro',
                'slug'                => 'pro',
                'precio_mensual'      => 39,
                'precio_anual'        => 390,
                'max_socios'          => 600,
                'max_usuarios'        => 10,
                'max_sucursales'      => 3,
                'limite_ia_mensual'   => 800,
                'modulos_habilitados' => json_encode(['socios','membresias','pagos','caja','asistencias','rutinas','clases','productos','reportes_completos','ia','gamificacion','notificaciones']),
                'activo'              => 1,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'nombre'              => 'Premium',
                'slug'                => 'premium',
                'precio_mensual'      => 79,
                'precio_anual'        => 790,
                'max_socios'          => 0,
                'max_usuarios'        => 0,
                'max_sucursales'      => 0,
                'limite_ia_mensual'   => 0,
                'modulos_habilitados' => json_encode(['*']),
                'activo'              => 1,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'nombre'              => 'Enterprise',
                'slug'                => 'enterprise',
                'precio_mensual'      => 0,
                'precio_anual'        => 0,
                'max_socios'          => 0,
                'max_usuarios'        => 0,
                'max_sucursales'      => 0,
                'limite_ia_mensual'   => 0,
                'modulos_habilitados' => json_encode(['*']),
                'activo'              => 1,
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ]);
    }
}