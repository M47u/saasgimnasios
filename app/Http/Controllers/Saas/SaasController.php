<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Gimnasio;
use App\Models\Socio;
use App\Models\Suscripcion;

class SaasController extends Controller
{
    public function dashboard()
    {
        $totalGimnasios       = Gimnasio::count();
        $gimnasiosActivos     = Gimnasio::where('estado', 'activo')->count();
        $gimnasiosTrial       = Gimnasio::where('estado', 'trial')->count();
        $gimnasiosSuspendidos = Gimnasio::where('estado', 'suspendido')->count();
        $totalSocios          = Socio::count();

        $suscripcionesVencen = Suscripcion::whereIn('estado', ['activa', 'trial'])
            ->where('fin', '>=', today())
            ->where('fin', '<=', today()->addDays(7))
            ->with(['empresa.gimnasios', 'plan'])
            ->get();

        $gimnasiosRecientes = Gimnasio::with(['empresa.suscripcionActiva.plan'])
            ->withCount(['socios' => fn($q) => $q->where('estado', 'activo')])
            ->latest()
            ->limit(5)
            ->get();

        return view('saas.dashboard', compact(
            'totalGimnasios',
            'gimnasiosActivos',
            'gimnasiosTrial',
            'gimnasiosSuspendidos',
            'totalSocios',
            'suscripcionesVencen',
            'gimnasiosRecientes',
        ));
    }
}
