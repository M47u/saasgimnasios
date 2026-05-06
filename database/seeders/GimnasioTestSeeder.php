<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Empresa;
use App\Models\Gimnasio;
use App\Models\Suscripcion;
use App\Models\SaasPlan;
use App\Models\GymUser;
use App\Models\PlanMembresia;
use App\Models\Socio;
use App\Models\MemberUser;
use App\Models\Membresia;
use App\Models\Pago;
use App\Models\Asistencia;

class GimnasioTestSeeder extends Seeder
{
    public function run(): void
    {
        if (Gimnasio::where('slug', 'gimnasio-central')->exists()) {
            $this->command->warn('GimnasioTestSeeder ya fue ejecutado. Omitiendo.');
            return;
        }

        // ── Empresa ──────────────────────────────────────────────────────────
        $empresa = Empresa::create([
            'nombre' => 'Gimnasio Test SA',
        ]);

        // ── Gimnasio ─────────────────────────────────────────────────────────
        $gimnasio = Gimnasio::create([
            'empresa_id' => $empresa->id,
            'nombre'     => 'Gimnasio Central',
            'slug'       => 'gimnasio-central',
            'email'      => 'admin@gimnasiocentral.com',
            'telefono'   => '+54 11 5555-0000',
            'direccion'  => 'Av. Corrientes 1234',
            'ciudad'     => 'Buenos Aires',
            'provincia'  => 'Buenos Aires',
            'pais'       => 'AR',
            'estado'     => 'activo',
        ]);

        // ── Suscripción SaaS (plan Pro) ───────────────────────────────────────
        $planPro = SaasPlan::where('slug', 'pro')->first();

        if ($planPro) {
            Suscripcion::create([
                'empresa_id'  => $empresa->id,
                'plan_id'     => $planPro->id,
                'ciclo'       => 'mensual',
                'inicio'      => today(),
                'fin'         => today()->addDays(30),
                'estado'      => 'activa',
                'monto_pagado' => $planPro->precio_mensual,
            ]);
        }

        // ── Plan de membresía del gimnasio ────────────────────────────────────
        $planMembresia = PlanMembresia::create([
            'gimnasio_id'       => $gimnasio->id,
            'nombre'            => 'Plan Mensual',
            'precio'            => 5000.00,
            'duracion_dias'     => 30,
            'incluye_clases'    => true,
            'dias_acceso_semana' => 7,
            'descripcion'       => 'Acceso completo al gimnasio durante 30 días.',
            'activo'            => true,
        ]);

        // ── Usuarios del gimnasio ─────────────────────────────────────────────
        $adminGym = GymUser::create([
            'gimnasio_id' => $gimnasio->id,
            'nombre'      => 'Admin',
            'apellido'    => 'Central',
            'email'       => 'admin@gimnasio.com',
            'password'    => Hash::make('password'),
            'rol'         => 'admin',
            'activo'      => true,
        ]);

        GymUser::create([
            'gimnasio_id' => $gimnasio->id,
            'nombre'      => 'Laura',
            'apellido'    => 'García',
            'email'       => 'recep@gimnasio.com',
            'password'    => Hash::make('password'),
            'rol'         => 'recepcionista',
            'activo'      => true,
        ]);

        GymUser::create([
            'gimnasio_id' => $gimnasio->id,
            'nombre'      => 'Pablo',
            'apellido'    => 'Herrera',
            'email'       => 'entrenador@gimnasio.com',
            'password'    => Hash::make('password'),
            'rol'         => 'entrenador',
            'activo'      => true,
        ]);

        // ── Socios con member_user, membresía, pago y asistencias ─────────────
        $socios = [
            [
                'nombre'    => 'Juan',
                'apellido'  => 'García',
                'email'     => 'socio1@test.com',
                'dni'       => '35123456',
                'nacimiento' => '1990-03-15',
                'fin_memb'  => today()->addDays(30),    // Normal
                'asistencias' => [0, 2, 5],
            ],
            [
                'nombre'    => 'María',
                'apellido'  => 'López',
                'email'     => 'socio2@test.com',
                'dni'       => '28456789',
                'nacimiento' => '1985-07-22',
                'fin_memb'  => today()->addDays(30),    // Normal
                'asistencias' => [0, 3, 6],
            ],
            [
                'nombre'    => 'Carlos',
                'apellido'  => 'Rodríguez',
                'email'     => 'socio3@test.com',
                'dni'       => '40987654',
                'nacimiento' => '1995-11-08',
                'fin_memb'  => today()->addDays(30),    // Normal
                'asistencias' => [0, 1, 4],
            ],
            [
                'nombre'    => 'Ana',
                'apellido'  => 'Martínez',
                'email'     => 'socio4@test.com',
                'dni'       => '32654321',
                'nacimiento' => '1988-01-30',
                'fin_memb'  => today()->addDays(4),     // Por vencer en 7 días
                'asistencias' => [1, 2, 7],
            ],
            [
                'nombre'    => 'Diego',
                'apellido'  => 'Fernández',
                'email'     => 'socio5@test.com',
                'dni'       => '37891234',
                'nacimiento' => '1992-06-14',
                'fin_memb'  => today()->subDays(5),     // Vencida (estado activa para demo)
                'asistencias' => [0, 4, 6],
            ],
        ];

        foreach ($socios as $i => $s) {
            $socio = Socio::create([
                'gimnasio_id'     => $gimnasio->id,
                'nombre'          => $s['nombre'],
                'apellido'        => $s['apellido'],
                'email'           => $s['email'],
                'dni'             => $s['dni'],
                'fecha_nacimiento' => $s['nacimiento'],
                'estado'          => 'activo',
                'nivel'           => ['principiante', 'intermedio', 'avanzado'][$i % 3],
                'frecuencia_semanal' => 3,
            ]);

            MemberUser::create([
                'socio_id'    => $socio->id,
                'gimnasio_id' => $gimnasio->id,
                'email'       => $s['email'],
                'password'    => Hash::make('password'),
            ]);

            $membresia = Membresia::create([
                'socio_id'       => $socio->id,
                'gimnasio_id'    => $gimnasio->id,
                'plan_id'        => $planMembresia->id,
                'inicio'         => today()->subDays(30 - (int) $s['fin_memb']->diffInDays(today(), false)),
                'fin'            => $s['fin_memb'],
                'estado'         => 'activa',
                'registrado_por' => $adminGym->id,
            ]);

            Pago::create([
                'gimnasio_id'    => $gimnasio->id,
                'socio_id'       => $socio->id,
                'membresia_id'   => $membresia->id,
                'monto'          => 5000.00,
                'metodo'         => 'efectivo',
                'estado'         => 'aprobado',
                'registrado_por' => $adminGym->id,
                'pagado_at'      => now()->subHours($i),
            ]);

            foreach ($s['asistencias'] as $diasAtras) {
                Asistencia::create([
                    'gimnasio_id'      => $gimnasio->id,
                    'socio_id'         => $socio->id,
                    'metodo_registro'  => 'manual',
                    'registrado_por'   => $adminGym->id,
                    'ingreso'          => now()->subDays($diasAtras)->setHour(8 + $i)->setMinute($i * 7),
                ]);
            }
        }

        $this->command->info("✓ Gimnasio Central seeded — gym_users: admin/recep/entrenador@gimnasio.com | socios: socio1-5@test.com (password: password)");
    }
}
