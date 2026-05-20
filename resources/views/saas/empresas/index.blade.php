@extends('layouts.saas')

@section('title', 'Empresas')

@section('content')
@php
$badgeMap = ['activa'=>'success','trial'=>'warning text-dark','vencida'=>'danger','suspendida'=>'secondary','cancelada'=>'dark'];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Empresas</h4>
        <p class="text-muted mb-0 small">Organizaciones que agrupan uno o más gimnasios</p>
    </div>
</div>

{{-- Búsqueda --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('saas.empresas.index') }}" class="d-flex gap-2">
            <input type="text" name="busqueda" class="form-control form-control-sm"
                   placeholder="Buscar por nombre, razón social o email..."
                   value="{{ request('busqueda') }}" style="max-width:360px">
            <button type="submit" class="btn btn-sm btn-saas">
                <i class="bi bi-search me-1"></i> Buscar
            </button>
            @if(request('busqueda'))
                <a href="{{ route('saas.empresas.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
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
                        <th class="ps-4">Empresa</th>
                        <th>Email</th>
                        <th>País</th>
                        <th class="text-center">Gimnasios</th>
                        <th>Plan activo</th>
                        <th>Suscripción</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($empresas as $empresa)
                    @php $sus = $empresa->suscripcionActiva; @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $empresa->nombre }}</div>
                            @if($empresa->razon_social && $empresa->razon_social !== $empresa->nombre)
                                <div class="text-muted small">{{ $empresa->razon_social }}</div>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $empresa->email ?? '—' }}</td>
                        <td class="small text-muted">{{ $empresa->pais ?? '—' }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $empresa->gimnasios_count }}</span>
                        </td>
                        <td class="small fw-semibold">{{ $sus?->plan?->nombre ?? '—' }}</td>
                        <td>
                            @if($sus)
                                <span class="badge bg-{{ $badgeMap[$sus->estado] ?? 'secondary' }}">
                                    {{ ucfirst($sus->estado) }}
                                </span>
                                @if($sus->proximaAVencer(7))
                                    <span class="badge bg-danger ms-1" style="font-size:.65rem">
                                        {{ $sus->diasRestantes() }}d
                                    </span>
                                @endif
                            @else
                                <span class="text-muted small">Sin suscripción</span>
                            @endif
                        </td>
                        <td class="pe-3 text-end">
                            <a href="{{ route('saas.empresas.show', $empresa->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-building fs-2 d-block mb-2 opacity-25"></i>
                            No se encontraron empresas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($empresas->hasPages())
    <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center py-2 px-4">
        <small class="text-muted">
            Mostrando {{ $empresas->firstItem() }}–{{ $empresas->lastItem() }}
            de {{ $empresas->total() }} registros
        </small>
        {{ $empresas->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
