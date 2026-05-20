@extends('layouts.saas')

@section('title', $empresa->nombre)

@section('content')
@php
$badgeMap = ['activa'=>'success','trial'=>'warning text-dark','vencida'=>'danger','suspendida'=>'secondary','cancelada'=>'dark'];
$gymBadge = ['activo'=>'success','trial'=>'warning text-dark','suspendido'=>'secondary','cancelado'=>'dark'];
$sus = $empresa->suscripcionActiva;
@endphp

{{-- Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h4 class="mb-0 fw-bold">{{ $empresa->nombre }}</h4>
        @if($empresa->razon_social && $empresa->razon_social !== $empresa->nombre)
            <p class="text-muted mb-0 small">{{ $empresa->razon_social }}</p>
        @endif
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('saas.empresas.edit', $empresa->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Editar
        </a>
        @if($empresa->gimnasios_count === 0)
        <form method="POST" action="{{ route('saas.empresas.destroy', $empresa->id) }}"
              onsubmit="return confirm('¿Eliminar «{{ $empresa->nombre }}»? Esta acción no se puede deshacer.')">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger">
                <i class="bi bi-trash3 me-1"></i> Eliminar
            </button>
        </form>
        @endif
        <a href="{{ route('saas.empresas.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Datos --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-building me-2"></i>Datos de la empresa</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted small">Nombre</dt>
                    <dd class="col-7 small fw-semibold">{{ $empresa->nombre }}</dd>

                    <dt class="col-5 text-muted small">Razón social</dt>
                    <dd class="col-7 small">{{ $empresa->razon_social ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Email</dt>
                    <dd class="col-7 small">{{ $empresa->email ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Teléfono</dt>
                    <dd class="col-7 small">{{ $empresa->telefono ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">País</dt>
                    <dd class="col-7 small">{{ $empresa->pais ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Gimnasios</dt>
                    <dd class="col-7 small fw-bold text-primary">{{ $empresa->gimnasios_count }}</dd>

                    <dt class="col-5 text-muted small">Registrada</dt>
                    <dd class="col-7 small">{{ $empresa->created_at->format('d/m/Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Suscripción activa --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-credit-card me-2"></i>Suscripción activa</h6>
            </div>
            <div class="card-body">
                @if($sus)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-primary-subtle rounded-3 p-3 text-primary">
                            <i class="bi bi-tags fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5">{{ $sus->plan?->nombre ?? '—' }}</div>
                            <span class="badge bg-{{ $badgeMap[$sus->estado] ?? 'secondary' }}">
                                {{ ucfirst($sus->estado) }}
                            </span>
                        </div>
                    </div>
                    <dl class="row mb-3">
                        <dt class="col-6 text-muted small">Inicio</dt>
                        <dd class="col-6 small">{{ $sus->inicio?->format('d/m/Y') ?? '—' }}</dd>

                        <dt class="col-6 text-muted small">Vencimiento</dt>
                        <dd class="col-6 small">{{ $sus->fin?->format('d/m/Y') ?? '—' }}</dd>

                        @if($sus->estaActiva())
                        <dt class="col-6 text-muted small">Días restantes</dt>
                        <dd class="col-6 small">
                            @php $dias = $sus->diasRestantes(); @endphp
                            <span class="fw-bold {{ $dias <= 7 ? 'text-danger' : ($dias <= 15 ? 'text-warning' : 'text-success') }}">
                                {{ $dias }} días
                            </span>
                        </dd>
                        @endif

                        <dt class="col-6 text-muted small">Ciclo</dt>
                        <dd class="col-6 small">{{ ucfirst($sus->ciclo) }}</dd>

                        <dt class="col-6 text-muted small">Monto</dt>
                        <dd class="col-6 small fw-semibold">
                            {{ $sus->monto_pagado ? '$' . number_format($sus->monto_pagado, 2, ',', '.') : '—' }}
                        </dd>
                    </dl>
                    <a href="{{ route('saas.suscripciones.show', $sus->id) }}"
                       class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-arrow-right me-1"></i> Ver suscripción completa
                    </a>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-exclamation-triangle fs-2 d-block mb-2 text-warning opacity-75"></i>
                        <div class="fw-semibold text-warning-emphasis">Sin suscripción activa</div>
                        <div class="small mt-1">Asignala desde el gimnasio correspondiente.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Gimnasios --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-building me-2"></i>Gimnasios
                    <span class="badge bg-secondary ms-1">{{ $empresa->gimnasios_count }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                @forelse($empresa->gimnasios as $gym)
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                    <div>
                        <a href="{{ route('saas.gimnasios.show', $gym->id) }}"
                           class="fw-semibold small text-decoration-none">
                            {{ $gym->nombre }}
                        </a>
                        <div class="text-muted" style="font-size:.75rem">
                            {{ collect([$gym->ciudad, $gym->provincia])->filter()->implode(', ') ?: 'Sin ubicación' }}
                        </div>
                    </div>
                    <span class="badge bg-{{ $gymBadge[$gym->estado] ?? 'secondary' }}">
                        {{ ucfirst($gym->estado) }}
                    </span>
                </div>
                @empty
                <div class="text-center text-muted py-4 small">Sin gimnasios asociados.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Historial de suscripciones --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-clock-history me-2"></i>Historial de suscripciones
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Plan</th>
                                <th>Ciclo</th>
                                <th>Inicio</th>
                                <th>Vencimiento</th>
                                <th>Estado</th>
                                <th>Registrado por</th>
                                <th class="text-end pe-4">Monto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historialSuscripciones as $h)
                            <tr>
                                <td class="ps-4 fw-semibold small">{{ $h->plan?->nombre ?? '—' }}</td>
                                <td class="small text-muted">{{ ucfirst($h->ciclo) }}</td>
                                <td class="small">{{ $h->inicio?->format('d/m/Y') ?? '—' }}</td>
                                <td class="small">{{ $h->fin?->format('d/m/Y') ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $badgeMap[$h->estado] ?? 'secondary' }}">
                                        {{ ucfirst($h->estado) }}
                                    </span>
                                </td>
                                <td class="small text-muted">{{ $h->registradoPor?->nombre ?? '—' }}</td>
                                <td class="text-end pe-4 small fw-semibold">
                                    {{ $h->monto_pagado ? '$' . number_format($h->monto_pagado, 2, ',', '.') : '—' }}
                                </td>
                                <td class="pe-3">
                                    <a href="{{ route('saas.suscripciones.show', $h->id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Sin historial.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
