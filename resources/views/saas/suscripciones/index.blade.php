@extends('layouts.saas')

@section('title', 'Suscripciones')

@section('content')
@php
$badgeMap = [
    'activa'     => 'success',
    'trial'      => 'warning text-dark',
    'vencida'    => 'danger',
    'suspendida' => 'secondary',
    'cancelada'  => 'dark',
];
@endphp

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Suscripciones</h4>
        <p class="text-muted mb-0 small">Gestión de suscripciones activas y vencidas</p>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle rounded-3 p-2 text-success">
                        <i class="bi bi-check-circle fs-5"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold lh-1">{{ $stats['activas'] }}</div>
                        <div class="text-muted small">Activas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning-subtle rounded-3 p-2 text-warning">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold lh-1">{{ $stats['trial'] }}</div>
                        <div class="text-muted small">En Trial</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger-subtle rounded-3 p-2 text-danger">
                        <i class="bi bi-x-circle fs-5"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold lh-1">{{ $stats['vencidas'] }}</div>
                        <div class="text-muted small">Vencidas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm {{ $stats['vencen_pronto'] > 0 ? 'border-warning border-2' : '' }}">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-orange-subtle rounded-3 p-2" style="background-color:#fff3cd;">
                        <i class="bi bi-alarm fs-5 text-warning"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold lh-1 {{ $stats['vencen_pronto'] > 0 ? 'text-warning' : '' }}">
                            {{ $stats['vencen_pronto'] }}
                        </div>
                        <div class="text-muted small">Vencen en 7 días</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filtros --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('saas.suscripciones.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
            <select name="estado" class="form-select form-select-sm" style="width:auto">
                <option value="">Todos los estados</option>
                @foreach(['activa','trial','vencida','suspendida','cancelada'] as $e)
                    <option value="{{ $e }}" {{ request('estado') === $e ? 'selected' : '' }}>
                        {{ ucfirst($e) }}
                    </option>
                @endforeach
            </select>

            <select name="plan_id" class="form-select form-select-sm" style="width:auto">
                <option value="">Todos los planes</option>
                @foreach($planes as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->nombre }}
                    </option>
                @endforeach
            </select>

            <div class="form-check form-switch mb-0 ms-1">
                <input class="form-check-input" type="checkbox" name="vence_pronto" value="1" id="vencePronto"
                       {{ request()->boolean('vence_pronto') ? 'checked' : '' }}
                       onchange="this.form.submit()">
                <label class="form-check-label small" for="vencePronto">Vencen pronto</label>
            </div>

            <button type="submit" class="btn btn-sm btn-saas">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>

            @if(request()->hasAny(['estado','plan_id','vence_pronto']))
                <a href="{{ route('saas.suscripciones.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-lg"></i> Limpiar
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
                        <th class="ps-4">Empresa / Gimnasio</th>
                        <th>Plan</th>
                        <th>Ciclo</th>
                        <th>Inicio</th>
                        <th>Vencimiento</th>
                        <th>Días rest.</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Monto</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suscripciones as $sus)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold small">{{ $sus->empresa?->nombre ?? '—' }}</div>
                            <div class="text-muted" style="font-size:.75rem">
                                @foreach($sus->empresa?->gimnasios ?? [] as $gym)
                                    <span>{{ $gym->nombre }}</span>@if(! $loop->last), @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="small fw-semibold">{{ $sus->plan?->nombre ?? '—' }}</td>
                        <td class="small text-muted">{{ ucfirst($sus->ciclo) }}</td>
                        <td class="small">{{ $sus->inicio?->format('d/m/Y') ?? '—' }}</td>
                        <td class="small">{{ $sus->fin?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            @if(in_array($sus->estado, ['activa','trial']))
                                @php $dias = $sus->diasRestantes(); @endphp
                                <span class="badge {{ $dias <= 7 ? 'bg-danger' : ($dias <= 15 ? 'bg-warning text-dark' : 'bg-light text-dark border') }}">
                                    {{ $dias }}d
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $badgeMap[$sus->estado] ?? 'secondary' }}">
                                {{ ucfirst($sus->estado) }}
                            </span>
                        </td>
                        <td class="text-end pe-4 small fw-semibold">
                            @if($sus->monto_pagado)
                                ${{ number_format($sus->monto_pagado, 2, ',', '.') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="pe-3">
                            <a href="{{ route('saas.suscripciones.show', $sus->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-credit-card fs-2 d-block mb-2 opacity-25"></i>
                            No hay suscripciones con los filtros seleccionados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($suscripciones->hasPages())
    <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center py-2 px-4">
        <small class="text-muted">
            Mostrando {{ $suscripciones->firstItem() }}–{{ $suscripciones->lastItem() }}
            de {{ $suscripciones->total() }} registros
        </small>
        {{ $suscripciones->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
