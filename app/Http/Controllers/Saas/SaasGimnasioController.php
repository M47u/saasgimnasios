<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Gimnasio;
use App\Models\SaasPlan;
use App\Models\Suscripcion;
use App\Models\GymUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SaasGimnasioController extends Controller
{
    public function index(Request $request)
    {
        $gimnasios = Gimnasio::with(['empresa.suscripcionActiva.plan'])
            ->withCount(['socios' => fn($q) => $q->where('estado', 'activo')])
            ->when($request->estado, fn($q, $v) => $q->where('estado', $v))
            ->when($request->buscar, fn($q, $v) => $q->where('nombre', 'like', "%{$v}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('saas.gimnasios.index', compact('gimnasios'));
    }

    public function create()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        $planes   = SaasPlan::where('activo', true)->orderBy('nombre')->get();

        return view('saas.gimnasios.create', compact('empresas', 'planes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'     => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'telefono'   => 'nullable|string|max:50',
            'direccion'  => 'nullable|string|max:255',
            'ciudad'     => 'nullable|string|max:100',
            'provincia'  => 'nullable|string|max:100',
            'empresa_id' => 'nullable|exists:empresas,id',
            'plan_id'    => 'nullable|exists:saas_plans,id',
        ]);

        if (empty($data['empresa_id'])) {
            $empresa = Empresa::create(['nombre' => $data['nombre']]);
            $data['empresa_id'] = $empresa->id;
        }

        $slug = $baseSlug = Str::slug($data['nombre']);
        $i = 1;
        while (Gimnasio::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        $conPlan = !empty($data['plan_id']);

        $gimnasio = Gimnasio::create([
            'empresa_id' => $data['empresa_id'],
            'nombre'     => $data['nombre'],
            'slug'       => $slug,
            'email'      => $data['email'] ?? null,
            'telefono'   => $data['telefono'] ?? null,
            'direccion'  => $data['direccion'] ?? null,
            'ciudad'     => $data['ciudad'] ?? null,
            'provincia'  => $data['provincia'] ?? null,
            'estado'     => $conPlan ? 'trial' : 'activo',
        ]);

        if ($conPlan) {
            Suscripcion::create([
                'empresa_id'     => $gimnasio->empresa_id,
                'plan_id'        => $data['plan_id'],
                'ciclo'          => 'mensual',
                'inicio'         => today(),
                'fin'            => today()->addDays(30),
                'trial_ends_at'  => today()->addDays(30),
                'estado'         => 'trial',
                'registrado_por' => auth('saas')->id(),
            ]);
        }

        return redirect()->route('saas.gimnasios.show', $gimnasio->id)
            ->with('success', "Gimnasio «{$gimnasio->nombre}» creado correctamente.");
    }

    public function show(int $id)
    {
        $gimnasio = Gimnasio::with([
            'empresa.suscripcionActiva.plan',
            'usuarios',
        ])
        ->withCount(['socios' => fn($q) => $q->where('estado', 'activo')])
        ->findOrFail($id);

        $ultimasSuscripciones = Suscripcion::where('empresa_id', $gimnasio->empresa_id)
            ->with('plan')
            ->latest()
            ->limit(5)
            ->get();

        return view('saas.gimnasios.show', compact('gimnasio', 'ultimasSuscripciones'));
    }

    public function edit(int $id)
    {
        $gimnasio    = Gimnasio::with('empresa.suscripcionActiva.plan')->findOrFail($id);
        $empresas    = Empresa::orderBy('nombre')->get();
        $planes      = SaasPlan::where('activo', true)->orderBy('nombre')->get();
        $suscripcion = $gimnasio->empresa?->suscripcionActiva;

        return view('saas.gimnasios.edit', compact('gimnasio', 'empresas', 'planes', 'suscripcion'));
    }

    public function update(Request $request, int $id)
    {
        $gimnasio = Gimnasio::findOrFail($id);

        $data = $request->validate([
            'nombre'     => 'required|string|max:255',
            'email'      => 'nullable|email|max:255',
            'telefono'   => 'nullable|string|max:50',
            'direccion'  => 'nullable|string|max:255',
            'ciudad'     => 'nullable|string|max:100',
            'provincia'  => 'nullable|string|max:100',
            'empresa_id' => 'nullable|exists:empresas,id',
        ]);

        $gimnasio->update($data);

        return redirect()->route('saas.gimnasios.show', $gimnasio->id)
            ->with('success', 'Gimnasio actualizado correctamente.');
    }

    public function suspender(int $id)
    {
        $gimnasio = Gimnasio::findOrFail($id);
            $gimnasio->update(['estado' => 'suspendido']);

            return back()->with('success', "Gimnasio «{$gimnasio->nombre}» suspendido.");
        }

        public function reactivar(int $id)
        {
            $gimnasio = Gimnasio::findOrFail($id);
            $gimnasio->update(['estado' => 'activo']);

            return back()->with('success', "Gimnasio «{$gimnasio->nombre}» reactivado.");
        }

        public function asignarSuscripcion(Request $request, int $id)
        {
            $gimnasio = Gimnasio::with('empresa.suscripcionActiva')->findOrFail($id);

            $suscripcionActiva = $gimnasio->empresa?->suscripcionActiva;
            $esCambio          = $request->boolean('cambiar_plan');

    public function reactivar(int $id)
    {
        $gimnasio = Gimnasio::findOrFail($id);
        $gimnasio->update(['estado' => 'activo']);

        return back()->with('success', "Gimnasio «{$gimnasio->nombre}» reactivado.");
    }

    public function asignarSuscripcion(Request $request, int $id)
    {
        $gimnasio = Gimnasio::with('empresa.suscripcionActiva')->findOrFail($id);

        $suscripcionActiva = $gimnasio->empresa?->suscripcionActiva;
        $esCambio          = $request->boolean('cambiar_plan');

        if ($suscripcionActiva && !$esCambio) {
            return back()->with('error', 'El gimnasio ya tiene una suscripción activa o en trial.');
        }

        $data = $request->validate([
            'plan_id'      => 'required|exists:saas_plans,id',
            'ciclo'        => 'required|in:mensual,anual',
            'monto_pagado' => 'nullable|numeric|min:0',
                return back()->with('error', 'El gimnasio ya tiene una suscripción activa o en trial.');
            }

            $data = $request->validate([
                'plan_id'      => 'required|exists:saas_plans,id',
                'ciclo'        => 'required|in:mensual,anual',
                'monto_pagado' => 'nullable|numeric|min:0',
                'comprobante'  => 'nullable|string|max:255',
                'es_trial'     => 'nullable|boolean',
            ]);

            if (!$gimnasio->empresa_id) {
                $empresa = Empresa::create(['nombre' => $gimnasio->nombre]);
                $gimnasio->update(['empresa_id' => $empresa->id]);
                $gimnasio->refresh();
            }

            if ($suscripcionActiva && $esCambio) {
                $suscripcionActiva->update(['estado' => 'vencida']);
            }

            $esTrial = !empty($data['es_trial']);
            $fin     = $data['ciclo'] === 'anual' ? today()->addDays(365) : today()->addDays(30);

            Suscripcion::create([
                'empresa_id'     => $gimnasio->empresa_id,
                'plan_id'        => $data['plan_id'],
                'ciclo'          => $data['ciclo'],
                'inicio'         => today(),
                'fin'            => $fin,
                'trial_ends_at'  => $esTrial ? $fin : null,
                'estado'         => $esTrial ? 'trial' : 'activa',
                'monto_pagado'   => $data['monto_pagado'] ?? null,
                'comprobante'    => $data['comprobante'] ?? null,
                'registrado_por' => auth('saas')->id(),
            ]);

            $gimnasio->update(['estado' => $esTrial ? 'trial' : 'activo']);

            $msg = $esCambio ? 'Plan cambiado correctamente.' : 'Suscripción asignada correctamente.';
            return redirect()->route('saas.gimnasios.show', $gimnasio->id)->with('success', $msg);
        }
}
