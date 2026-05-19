<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Suscripcion;
use App\Models\SaasPlan;
use Illuminate\Http\Request;

class SaasSuscripcionController extends Controller
{
    public function index(Request $request)
    {
        // Solo la suscripción más reciente por empresa
        $query = Suscripcion::with(['empresa.gimnasios', 'plan', 'registradoPor'])
            ->whereIn('id', function ($sub) {
                $sub->selectRaw('MAX(id)')
                    ->from('suscripciones')
                    ->groupBy('empresa_id');
            })
            ->latest();

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        if ($planId = $request->input('plan_id')) {
            $query->where('plan_id', $planId);
        }

        if ($request->boolean('vence_pronto')) {
            $query->whereIn('estado', ['activa', 'trial'])
                ->whereDate('fin', '>=', today())
                ->whereDate('fin', '<=', today()->addDays(7));
        }

        $suscripciones = $query->paginate(20)->withQueryString();
        $planes = SaasPlan::where('activo', true)->orderBy('nombre')->get();

        $latestIds = Suscripcion::selectRaw('MAX(id) as id')->groupBy('empresa_id')->pluck('id');

        $stats = [
            'activas'       => Suscripcion::whereIn('id', $latestIds)->where('estado', 'activa')->count(),
            'trial'         => Suscripcion::whereIn('id', $latestIds)->where('estado', 'trial')->count(),
            'vencidas'      => Suscripcion::whereIn('id', $latestIds)->where('estado', 'vencida')->count(),
            'vencen_pronto' => Suscripcion::whereIn('id', $latestIds)
                ->whereIn('estado', ['activa', 'trial'])
                ->whereDate('fin', '>=', today())
                ->whereDate('fin', '<=', today()->addDays(7))
                ->count(),
        ];

        return view('saas.suscripciones.index', compact('suscripciones', 'planes', 'stats'));
    }

    public function show(int $id)
    {
        $suscripcion = Suscripcion::with(['empresa.gimnasios', 'plan', 'registradoPor'])
            ->findOrFail($id);

        $historial = Suscripcion::where('empresa_id', $suscripcion->empresa_id)
            ->with(['plan', 'registradoPor'])
            ->latest()
            ->get();

        $planes = SaasPlan::where('activo', true)->orderBy('nombre')->get();

        return view('saas.suscripciones.show', compact('suscripcion', 'historial', 'planes'));
    }

    public function renovar(Request $request, int $id)
    {
        $suscripcion = Suscripcion::with('empresa.gimnasios')->findOrFail($id);

        if (! in_array($suscripcion->estado, ['activa', 'trial', 'vencida'])) {
            return back()->with('error', 'Solo se pueden renovar suscripciones activas, en trial o vencidas.');
        }

        $data = $request->validate([
            'monto_pagado' => 'nullable|numeric|min:0',
            'comprobante'  => 'nullable|string|max:255',
            'notas'        => 'nullable|string|max:500',
        ]);

        $suscripcion->update(['estado' => 'vencida']);

        $fin = $suscripcion->ciclo === 'anual' ? today()->addDays(365) : today()->addDays(30);

        $nueva = Suscripcion::create([
            'empresa_id'    => $suscripcion->empresa_id,
            'plan_id'       => $suscripcion->plan_id,
            'ciclo'         => $suscripcion->ciclo,
            'inicio'        => today(),
            'fin'           => $fin,
            'estado'        => 'activa',
            'monto_pagado'  => $data['monto_pagado'] ?? null,
            'comprobante'   => $data['comprobante'] ?? null,
            'notas'         => $data['notas'] ?? null,
            'registrado_por' => auth('saas')->id(),
        ]);

        $suscripcion->empresa->gimnasios()
            ->whereIn('estado', ['trial', 'suspendido'])
            ->update(['estado' => 'activo']);

        return redirect()
            ->route('saas.suscripciones.show', $nueva->id)
            ->with('success', 'Suscripción renovada correctamente.');
    }

    public function suspender(int $id)
    {
        $suscripcion = Suscripcion::with('empresa.gimnasios')->findOrFail($id);

        if (! in_array($suscripcion->estado, ['activa', 'trial'])) {
            return back()->with('error', 'Solo se pueden suspender suscripciones activas o en trial.');
        }

        $suscripcion->update(['estado' => 'suspendida']);

        $suscripcion->empresa->gimnasios()
            ->where('estado', '!=', 'cancelado')
            ->update(['estado' => 'suspendido']);

        return back()->with('success', 'Suscripción suspendida. Los gimnasios asociados también fueron suspendidos.');
    }

    public function cancelar(int $id)
    {
        $suscripcion = Suscripcion::with('empresa.gimnasios')->findOrFail($id);

        if ($suscripcion->estado === 'cancelada') {
            return back()->with('error', 'La suscripción ya está cancelada.');
        }

        $suscripcion->update(['estado' => 'cancelada']);

        $suscripcion->empresa->gimnasios()
            ->whereIn('estado', ['activo', 'trial'])
            ->update(['estado' => 'suspendido']);

        return back()->with('success', 'Suscripción cancelada. Los gimnasios activos fueron suspendidos.');
    }
}
