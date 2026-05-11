@extends('layouts.saas')

@section('title', 'Gimnasios eliminados')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Gimnasios eliminados</h4>
        <p class="text-muted mb-0">Registros eliminados lógicamente — pueden restaurarse a estado suspendido</p>
    </div>
    <a href="{{ route('saas.gimnasios.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver al listado
    </a>
</div>

{{-- Filtro --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('saas.gimnasios.eliminados') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold mb-1">Buscar por nombre</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="buscar" class="form-control"
                           placeholder="Nombre del gimnasio…"
                           value="{{ request('buscar') }}">
                </div>
            </div>
            <div class="col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-saas">Filtrar</button>
                @if(request('buscar'))
                    <a href="{{ route('saas.gimnasios.eliminados') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabla --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nombre</th>
                        <th>Ciudad</th>
                        <th>Plan</th>
                        <th class="text-center">Socios</th>
                        <th>Eliminado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gimnasios as $gimnasio)
                    <tr class="text-muted">
                        <td class="ps-4">
                            <div class="fw-semibold text-body">{{ $gimnasio->nombre }}</div>
                            <div class="small">{{ $gimnasio->email ?? '—' }}</div>
                        </td>
                        <td>{{ $gimnasio->ciudad ?? '—' }}</td>
                        <td>
                            @php $planNombre = $gimnasio->empresa?->suscripcionActiva?->plan?->nombre @endphp
                            @if($planNombre)
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $planNombre }}</span>
                            @else
                                <span class="small">Sin plan</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $gimnasio->socios_count }}</td>
                        <td class="small">{{ $gimnasio->updated_at->format('d/m/Y') }}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('saas.gimnasios.show', $gimnasio->id) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Ver ficha">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('saas.gimnasios.restaurar', $gimnasio->id) }}"
                                      onsubmit="return confirm('¿Restaurar «{{ $gimnasio->nombre }}»? Volverá a estado suspendido.')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success" title="Restaurar">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-trash3 fs-2 d-block mb-2 opacity-25"></i>
                            No hay gimnasios eliminados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($gimnasios->hasPages())
    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
        <span class="text-muted small">
            Mostrando {{ $gimnasios->firstItem() }}–{{ $gimnasios->lastItem() }}
            de {{ $gimnasios->total() }} gimnasios eliminados
        </span>
        {{ $gimnasios->links() }}
    </div>
    @endif
</div>
@endsection
