@extends('layouts.saas')

@section('title', 'Detalle de suscripción')

@section('content')
@php
$badgeMap = [
    'activa'     => 'success',
    'trial'      => 'warning text-dark',
    'vencida'    => 'danger',
    'suspendida' => 'secondary',
    'cancelada'  => 'dark',
];
$canRenovar   = in_array($suscripcion->estado, ['activa', 'trial', 'vencida']);
$canSuspender = in_array($suscripcion->estado, ['activa', 'trial']);
$canCancelar  = $suscripcion->estado !== 'cancelada';
@endphp

{{-- Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-3 mb-1">
            <h4 class="mb-0 fw-bold">{{ $suscripcion->empresa?->nombre ?? 'Sin empresa' }}</h4>
            <span class="badge bg-{{ $badgeMap[$suscripcion->estado] ?? 'secondary' }} rounded-pill fs-6">
                {{ ucfirst($suscripcion->estado) }}
            </span>
        </div>
        <p class="text-muted mb-0 small">
            <i class="bi bi-tags me-1"></i>{{ $suscripcion->plan?->nombre ?? '—' }}
            &nbsp;·&nbsp;
            <i class="bi bi-arrow-repeat me-1"></i>{{ ucfirst($suscripcion->ciclo) }}
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @if($canRenovar)
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRenovar">
                <i class="bi bi-arrow-clockwise me-1"></i> Renovar
            </button>
        @endif
        @if($canSuspender)
            <form method="POST" action="{{ route('saas.suscripciones.suspender', $suscripcion->id) }}"
                  onsubmit="return confirm('¿Suspender esta suscripción? Los gimnasios asociados también serán suspendidos.')">
                @csrf
                <button class="btn btn-warning">
                    <i class="bi bi-pause-circle me-1"></i> Suspender
                </button>
            </form>
        @endif
        @if($canCancelar)
            <form method="POST" action="{{ route('saas.suscripciones.cancelar', $suscripcion->id) }}"
                  onsubmit="return confirm('¿Cancelar esta suscripción? Esta acción no suspende los gimnasios.')">
                @csrf
                <button class="btn btn-outline-danger">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
            </form>
        @endif
        <a href="{{ route('saas.suscripciones.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Detalle de suscripción --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-credit-card me-2"></i>Suscripción</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted small">Plan</dt>
                    <dd class="col-7 small fw-semibold">{{ $suscripcion->plan?->nombre ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Ciclo</dt>
                    <dd class="col-7 small">{{ ucfirst($suscripcion->ciclo) }}</dd>

                    <dt class="col-5 text-muted small">Inicio</dt>
                    <dd class="col-7 small">{{ $suscripcion->inicio?->format('d/m/Y') ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Vencimiento</dt>
                    <dd class="col-7 small">{{ $suscripcion->fin?->format('d/m/Y') ?? '—' }}</dd>

                    @if(in_array($suscripcion->estado, ['activa','trial']))
                    <dt class="col-5 text-muted small">Días restantes</dt>
                    <dd class="col-7 small">
                        @php $dias = $suscripcion->diasRestantes(); @endphp
                        <span class="fw-bold {{ $dias <= 7 ? 'text-danger' : ($dias <= 15 ? 'text-warning' : 'text-success') }}">
                            {{ $dias }} días
                        </span>
                    </dd>
                    @endif

                    @if($suscripcion->estaEnTrial())
                    <dt class="col-5 text-muted small">Trial hasta</dt>
                    <dd class="col-7 small">{{ $suscripcion->trial_ends_at?->format('d/m/Y') ?? '—' }}</dd>
                    @endif

                    <dt class="col-5 text-muted small">Monto pagado</dt>
                    <dd class="col-7 small fw-semibold">
                        {{ $suscripcion->monto_pagado ? '$' . number_format($suscripcion->monto_pagado, 2, ',', '.') : '—' }}
                    </dd>

                    <dt class="col-5 text-muted small">Comprobante</dt>
                    <dd class="col-7 small">{{ $suscripcion->comprobante ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Registrado por</dt>
                    <dd class="col-7 small">{{ $suscripcion->registradoPor?->nombre ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Creado</dt>
                    <dd class="col-7 small">{{ $suscripcion->created_at->format('d/m/Y H:i') }}</dd>

                    @if($suscripcion->notas)
                    <dt class="col-5 text-muted small">Notas</dt>
                    <dd class="col-7 small">{{ $suscripcion->notas }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    {{-- Empresa y gimnasios --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-building me-2"></i>Empresa</h6>
            </div>
            <div class="card-body">
                <div class="fw-semibold mb-1">{{ $suscripcion->empresa?->nombre ?? '—' }}</div>
                <div class="text-muted small mb-3">{{ $suscripcion->empresa?->email ?? 'Sin email' }}</div>

                <div class="text-muted small fw-semibold mb-2">GIMNASIOS ASOCIADOS</div>
                @forelse($suscripcion->empresa?->gimnasios ?? [] as $gym)
                @php
                $gymBadge = ['activo'=>'success','trial'=>'warning text-dark','suspendido'=>'secondary','cancelado'=>'dark'];
                @endphp
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <a href="{{ route('saas.gimnasios.show', $gym->id) }}" class="text-decoration-none fw-semibold small">
                            {{ $gym->nombre }}
                        </a>
                        <div class="text-muted" style="font-size:.75rem">{{ $gym->ciudad ?? 'Sin ciudad' }}</div>
                    </div>
                    <span class="badge bg-{{ $gymBadge[$gym->estado] ?? 'secondary' }}">
                        {{ ucfirst($gym->estado) }}
                    </span>
                </div>
                @empty
                <div class="text-muted small">Sin gimnasios asociados.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Límites del plan --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-sliders me-2"></i>Límites del plan</h6>
            </div>
            <div class="card-body">
                @if($suscripcion->plan)
                @php $plan = $suscripcion->plan; @endphp
                <dl class="row mb-0">
                    <dt class="col-7 text-muted small">Socios máx.</dt>
                    <dd class="col-5 small fw-semibold text-end">
                        {{ $plan->max_socios == 0 ? 'Ilimitado' : $plan->max_socios }}
                    </dd>

                    <dt class="col-7 text-muted small">Usuarios máx.</dt>
                    <dd class="col-5 small fw-semibold text-end">
                        {{ $plan->max_usuarios == 0 ? 'Ilimitado' : $plan->max_usuarios }}
                    </dd>

                    <dt class="col-7 text-muted small">Sucursales máx.</dt>
                    <dd class="col-5 small fw-semibold text-end">
                        {{ $plan->max_sucursales == 0 ? 'Ilimitado' : $plan->max_sucursales }}
                    </dd>

                    <dt class="col-7 text-muted small">Límite IA / mes</dt>
                    <dd class="col-5 small fw-semibold text-end">
                        {{ $plan->limite_ia_mensual == 0 ? 'Sin IA' : $plan->limite_ia_mensual . ' tokens' }}
                    </dd>

                    <dt class="col-7 text-muted small">Precio mensual</dt>
                    <dd class="col-5 small fw-semibold text-end">
                        ${{ number_format($plan->precio_mensual, 2, ',', '.') }}
                    </dd>

                    <dt class="col-7 text-muted small">Precio anual</dt>
                    <dd class="col-5 small fw-semibold text-end">
                        ${{ number_format($plan->precio_anual, 2, ',', '.') }}
                    </dd>
                </dl>

                @if($plan->modulos_habilitados)
                <div class="mt-3">
                    <div class="text-muted small fw-semibold mb-2">MÓDULOS</div>
                    @if(count($plan->modulos_habilitados) === 1 && $plan->modulos_habilitados[0] === '*')
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:.7rem">
                            <i class="bi bi-infinity me-1"></i>Todos los módulos
                        </span>
                    @else
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($plan->modulos_habilitados as $modulo)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size:.7rem">
                                    {{ $modulo }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endif
                @else
                <div class="text-muted small">Sin plan asignado.</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Historial de suscripciones de la empresa --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-clock-history me-2"></i>Historial de suscripciones
                    <span class="badge bg-secondary ms-1">{{ $historial->count() }}</span>
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
                            @foreach($historial as $h)
                            <tr class="{{ $h->id === $suscripcion->id ? 'table-active' : '' }}">
                                <td class="ps-4 fw-semibold small">
                                    {{ $h->plan?->nombre ?? '—' }}
                                    @if($h->id === $suscripcion->id)
                                        <span class="badge bg-primary-subtle text-primary ms-1" style="font-size:.65rem">actual</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ ucfirst($h->ciclo) }}</td>
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
                                    @if($h->id !== $suscripcion->id)
                                        <a href="{{ route('saas.suscripciones.show', $h->id) }}"
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Modal Renovar --}}
@if($canRenovar)
<div class="modal fade" id="modalRenovar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('saas.suscripciones.renovar', $suscripcion->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-arrow-clockwise me-2"></i>Renovar suscripción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        Se creará una nueva suscripción de <strong>{{ ucfirst($suscripcion->ciclo) }}</strong>
                        con el plan <strong>{{ $suscripcion->plan?->nombre }}</strong>,
                        vigente por {{ $suscripcion->ciclo === 'anual' ? '365' : '30' }} días desde hoy.
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Monto pagado</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="monto_pagado" class="form-control"
                                   step="0.01" min="0" placeholder="0.00"
                                   value="{{ $suscripcion->ciclo === 'anual' ? $suscripcion->plan?->precio_anual : $suscripcion->plan?->precio_mensual }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Comprobante / Referencia</label>
                        <input type="text" name="comprobante" class="form-control"
                               placeholder="Nro. de transferencia, recibo, etc.">
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-semibold">Notas <span class="text-muted fw-normal">(opcional)</span></label>
                        <textarea name="notas" class="form-control" rows="2"
                                  placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Confirmar renovación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
