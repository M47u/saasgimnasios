<?php

namespace App\Http\Controllers\Gym;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Clase;
use App\Models\Membresia;
use App\Models\Pago;
use App\Models\Socio;

class GymController extends Controller
{
    public function dashboard()
    {
        // HasGimnasioScope filtra automáticamente por gimnasio_id del guard 'gym'

        $sociosActivos   = Socio::where('estado', 'activo')->count();
        $sociosVencidos  = Socio::conMembresiaVencida()->count();
        $nuevosSociosMes = Socio::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

        $pagosHoy    = Pago::where('estado', 'aprobado')
                           ->whereDate('pagado_at', today())
                           ->sum('monto');

        $ingresosMes = Pago::where('estado', 'aprobado')
                           ->whereMonth('pagado_at', now()->month)
                           ->whereYear('pagado_at', now()->year)
                           ->sum('monto');

        $asistenciasHoy = Asistencia::whereDate('ingreso', today())->count();

        $membresiasPorVencer = Membresia::activas()
            ->whereBetween('fin', [today(), today()->addDays(7)])
            ->with('socio', 'plan')
            ->orderBy('fin')
            ->get();

        $clasesHoy = Clase::where('activo', true)
            ->whereJsonContains('dias_semana', now()->dayOfWeek)
            ->count();

        $sociosSinAsistencia = Socio::where('estado', 'activo')
            ->inactivos(15)
            ->count();

        $asistenciasHoyDetalle = Asistencia::whereDate('ingreso', today())
            ->with('socio')
            ->orderByDesc('ingreso')
            ->limit(25)
            ->get();

        return view('gym.dashboard', compact(
            'sociosActivos',
            'sociosVencidos',
            'nuevosSociosMes',
            'pagosHoy',
            'ingresosMes',
            'asistenciasHoy',
            'membresiasPorVencer',
            'clasesHoy',
            'sociosSinAsistencia',
            'asistenciasHoyDetalle',
        ));
    }
}
