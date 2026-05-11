@extends('layouts.saas')

@section('title', 'Gimnasios')

@section('content')
@php
$badgeMap = ['activo' => 'success', 'suspendido' => 'danger', 'trial' => 'warning text-dark'];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Gimnasios</h4>
        <p class="text-muted mb-0">Gestión de todos los gimnasios registrados</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('saas.gimnasios.eliminados') }}" class="btn btn-outline-secondary">
            <i class="bi bi-trash3 me-1"></i> Eliminados
        </a>
        <a href="{{ route('saas.gimnasios.create') }}" class="btn btn-saas">
            <i class="bi bi-plus-lg me-1"></i> Nuevo gimnasio
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('saas.gimnasios.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold mb-1">Buscar por nombre</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="buscar" class="form-control"
                           placeholder="Nombre del gimnasio…"
                           value="{{ request('buscar') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Estado</label>
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="activo"     {{ request('estado') === 'activo'     ? 'selected' : '' }}>Activo</option>
                    <option value="trial"      {{ request('estado') === 'trial'      ? 'selected' : '' }}>Trial</option>
                    <option value="suspendido" {{ request('estado') === 'suspendido' ? 'selected' : '' }}>Suspendido</option>
                </select>
            </div>
            <div class="col-md-auto d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-saas">Filtrar</button>
                @if(request('buscar') || request('estado'))
                    <a href="{{ route('saas.gimnasios.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
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
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gimnasios as $gimnasio)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $gimnasio->nombre }}</div>
                            <div class="text-muted small">{{ $gimnasio->email ?? '—' }}</div>
                        </td>
                        <td>{{ $gimnasio->ciudad ?? '—' }}</td>
                        <td>
                            @php $planNombre = $gimnasio->empresa?->suscripcionActiva?->plan?->nombre @endphp
                            @if($planNombre)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $planNombre }}</span>
                            @else
                                <span class="text-muted small">Sin plan</span>
                            @endif
                        </td>
                        <td class="text-center fw-semibold">{{ $gimnasio->socios_count }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $badgeMap[$gimnasio->estado] ?? 'secondary' }} rounded-pill">
                                {{ ucfirst($gimnasio->estado) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="{{ route('saas.gimnasios.show', $gimnasio->id) }}"
                                   class="btn btn-sm btn-outline-secondary" title="Ver ficha">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('saas.gimnasios.edit', $gimnasio->id) }}"
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($gimnasio->estado === 'suspendido')
                                    <form method="POST"
                                          action="{{ route('saas.gimnasios.reactivar', $gimnasio->id) }}"
                                          class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success" title="Reactivar">
                                            <i class="bi bi-play-circle"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST"
                                          action="{{ route('saas.gimnasios.suspender', $gimnasio->id) }}"
                                          class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-warning" title="Suspender">
                                            <i class="bi bi-pause-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-building fs-2 d-block mb-2 opacity-25"></i>
                            No hay gimnasios que coincidan con los filtros.
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
            de {{ $gimnasios->total() }} gimnasios
        </span>
        {{ $gimnasios->links() }}
    </div>
    @endif
</div>
@endsection
