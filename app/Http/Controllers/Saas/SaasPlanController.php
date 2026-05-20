<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use App\Models\Suscripcion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SaasPlanController extends Controller
{
    const MODULOS = [
        'socios', 'membresias', 'pagos', 'caja', 'asistencias',
        'rutinas', 'clases', 'reportes_basicos', 'productos',
        'reportes_completos', 'ia', 'gamificacion', 'notificaciones',
    ];

    public function index()
    {
        $planes = SaasPlan::withCount([
            'suscripciones as total_suscripciones',
            'suscripciones as suscripciones_activas' => fn ($q) => $q->whereIn('estado', ['activa', 'trial']),
        ])->orderBy('precio_mensual')->get();

        return view('saas.planes.index', compact('planes'));
    }

    public function create()
    {
        return view('saas.planes.create', ['modulos' => self::MODULOS]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['nombre']);

        // Garantizar slug único
        $base = $data['slug'];
        $i = 1;
        while (SaasPlan::where('slug', $data['slug'])->exists()) {
            $data['slug'] = "{$base}-{$i}";
            $i++;
        }

        $data['modulos_habilitados'] = $this->resolveModulos($request);

        SaasPlan::create($data);

        return redirect()->route('saas.planes.index')->with('success', "Plan «{$data['nombre']}» creado correctamente.");
    }

    public function edit(int $id)
    {
        $plan = SaasPlan::withCount([
            'suscripciones as suscripciones_activas' => fn ($q) => $q->whereIn('estado', ['activa', 'trial']),
        ])->findOrFail($id);

        return view('saas.planes.edit', ['plan' => $plan, 'modulos' => self::MODULOS]);
    }

    public function update(Request $request, int $id)
    {
        $plan = SaasPlan::findOrFail($id);
        $data = $this->validated($request, $plan);
        $data['modulos_habilitados'] = $this->resolveModulos($request);

        $plan->update($data);

        return redirect()->route('saas.planes.index')->with('success', "Plan «{$plan->nombre}» actualizado correctamente.");
    }

    public function toggleActivo(int $id)
    {
        $plan = SaasPlan::findOrFail($id);

        if ($plan->activo) {
            $activas = Suscripcion::where('plan_id', $plan->id)->whereIn('estado', ['activa', 'trial'])->count();
            if ($activas > 0) {
                return back()->with('error', "No se puede desactivar «{$plan->nombre}»: tiene {$activas} suscripción(es) activa(s).");
            }
        }

        $plan->update(['activo' => ! $plan->activo]);
        $msg = $plan->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Plan «{$plan->nombre}» {$msg}.");
    }

    public function destroy(int $id)
    {
        $plan = SaasPlan::findOrFail($id);

        $total = Suscripcion::where('plan_id', $plan->id)->count();
        if ($total > 0) {
            return back()->with('error', "No se puede eliminar «{$plan->nombre}»: tiene {$total} suscripción(es) asociada(s). Desactivalo en su lugar.");
        }

        $plan->delete();
        return redirect()->route('saas.planes.index')->with('success', "Plan «{$plan->nombre}» eliminado.");
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function validated(Request $request, ?SaasPlan $plan = null): array
    {
        return $request->validate([
            'nombre'          => 'required|string|max:100',
            'precio_mensual'  => 'required|numeric|min:0',
            'precio_anual'    => 'required|numeric|min:0',
            'max_socios'      => 'required|integer|min:0',
            'max_usuarios'    => 'required|integer|min:0',
            'max_sucursales'  => 'required|integer|min:0',
            'limite_ia_mensual' => 'required|integer|min:0',
            'activo'          => 'boolean',
        ]);
    }

    private function resolveModulos(Request $request): array
    {
        if ($request->boolean('todos_modulos')) {
            return ['*'];
        }
        return array_values(array_intersect(
            $request->input('modulos_habilitados', []),
            self::MODULOS
        ));
    }
}
