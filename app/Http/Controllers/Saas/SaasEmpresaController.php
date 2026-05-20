<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;

class SaasEmpresaController extends Controller
{
    public function index(Request $request)
    {
        $query = Empresa::withCount('gimnasios')
            ->with('suscripcionActiva.plan');

        if ($busqueda = $request->input('busqueda')) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('email', 'like', "%{$busqueda}%")
                  ->orWhere('razon_social', 'like', "%{$busqueda}%");
            });
        }

        $empresas = $query->orderBy('nombre')->paginate(20)->withQueryString();

        return view('saas.empresas.index', compact('empresas'));
    }

    public function show(int $id)
    {
        $empresa = Empresa::with([
            'gimnasios',
            'suscripcionActiva.plan',
        ])->withCount('gimnasios')->findOrFail($id);

        $historialSuscripciones = $empresa->suscripciones()
            ->with(['plan', 'registradoPor'])
            ->latest()
            ->take(10)
            ->get();

        return view('saas.empresas.show', compact('empresa', 'historialSuscripciones'));
    }

    public function edit(int $id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('saas.empresas.edit', compact('empresa'));
    }

    public function update(Request $request, int $id)
    {
        $empresa = Empresa::findOrFail($id);

        $data = $request->validate([
            'nombre'       => 'required|string|max:255',
            'razon_social' => 'nullable|string|max:255',
            'email'        => "nullable|email|max:255|unique:empresas,email,{$id}",
            'telefono'     => 'nullable|string|max:50',
            'pais'         => 'nullable|string|max:10',
        ]);

        $empresa->update($data);

        return redirect()
            ->route('saas.empresas.show', $empresa->id)
            ->with('success', "Empresa «{$empresa->nombre}» actualizada correctamente.");
    }

    public function destroy(int $id)
    {
        $empresa = Empresa::withCount('gimnasios')->findOrFail($id);

        if ($empresa->gimnasios_count > 0) {
            return back()->with('error', "No se puede eliminar «{$empresa->nombre}»: tiene {$empresa->gimnasios_count} gimnasio(s) asociado(s).");
        }

        $nombre = $empresa->nombre;
        $empresa->delete();

        return redirect()
            ->route('saas.empresas.index')
            ->with('success', "Empresa «{$nombre}» eliminada.");
    }
}
