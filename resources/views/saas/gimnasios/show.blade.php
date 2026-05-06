@extends('layouts.saas')

@section('title', $gimnasio->nombre)

@section('content')
@php
$badgeMap = ['activo' => 'success', 'suspendido' => 'danger', 'trial' => 'warning text-dark'];
$suscripcion = $gimnasio->empresa?->suscripcionActiva;
@endphp

{{-- Header --}}
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-3 mb-1">
            <h4 class="mb-0 fw-bold">{{ $gimnasio->nombre }}</h4>
            <span class="badge bg-{{ $badgeMap[$gimnasio->estado] ?? 'secondary' }} rounded-pill fs-6">
{{-- Credenciales del admin (si es nuevo) --}}
@if(session('admin_credentials'))
<div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-start gap-3">
        <div class="text-success fs-5">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold mb-2">Usuario administrador creado</div>
            <div class="row g-3 small">
                <div class="col-md-6">
                    <div class="text-muted">Email:</div>
                    <div class="font-monospace bg-light px-2 py-1 rounded" style="font-size: 0.85rem;">
                        {{ session('admin_credentials.email') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted">Contraseña temporal:</div>
                    <div class="font-monospace bg-light px-2 py-1 rounded d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                        <span id="password-text">{{ session('admin_credentials.password') }}</span>
                        <button class="btn btn-sm btn-link p-0" type="button" onclick="copyPassword()">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="text-muted small mt-2 mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Comparte estas credenciales con el administrador del gimnasio. Se recomienda cambiar la contraseña en el primer ingreso.
            </div>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<script>
    function copyPassword() {
        const password = document.getElementById('password-text').textContent;
        navigator.clipboard.writeText(password).then(() => {
            alert('Contraseña copiada al portapapeles');
        });
    }
</script>
@endif

                {{ ucfirst($gimnasio->estado) }}
            </span>
        </div>
        <p class="text-muted mb-0">
            <i class="bi bi-geo-alt me-1"></i>
            {{ collect([$gimnasio->ciudad, $gimnasio->provincia])->filter()->implode(', ') ?: 'Sin ubicación' }}
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('saas.gimnasios.edit', $gimnasio->id) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Editar
        </a>
        @if($gimnasio->estado === 'suspendido')
            <form method="POST" action="{{ route('saas.gimnasios.reactivar', $gimnasio->id) }}">
                @csrf
                <button class="btn btn-success">
                    <i class="bi bi-play-circle me-1"></i> Reactivar
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('saas.gimnasios.suspender', $gimnasio->id) }}"
                  onsubmit="return confirm('¿Suspender este gimnasio?')">
                @csrf
                <button class="btn btn-warning">
                    <i class="bi bi-pause-circle me-1"></i> Suspender
                </button>
            </form>
        @endif
        <a href="{{ route('saas.gimnasios.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Datos generales --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2"></i>Datos generales</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted small">Email</dt>
                    <dd class="col-7 small">{{ $gimnasio->email ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Teléfono</dt>
                    <dd class="col-7 small">{{ $gimnasio->telefono ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Dirección</dt>
                    <dd class="col-7 small">{{ $gimnasio->direccion ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Ciudad</dt>
                    <dd class="col-7 small">{{ $gimnasio->ciudad ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Provincia</dt>
                    <dd class="col-7 small">{{ $gimnasio->provincia ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Empresa</dt>
                    <dd class="col-7 small">{{ $gimnasio->empresa?->nombre ?? '—' }}</dd>

                    <dt class="col-5 text-muted small">Socios activos</dt>
                    <dd class="col-7 small fw-bold text-success">{{ $gimnasio->socios_count }}</dd>

                    <dt class="col-5 text-muted small">Registrado</dt>
                    <dd class="col-7 small">{{ $gimnasio->created_at->format('d/m/Y') }}</dd>
                </dl>
            </div>
        </div>
    </div>

    {{-- Suscripción --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-credit-card me-2"></i>Suscripción activa</h6>
            </div>
            <div class="card-body">
                @if($suscripcion)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-primary-subtle rounded-3 p-3 text-primary">
                            <i class="bi bi-tags fs-4"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5">{{ $suscripcion->plan?->nombre ?? '—' }}</div>
                            <span class="badge bg-{{ $badgeMap[$suscripcion->estado] ?? 'secondary' }}">
                                {{ ucfirst($suscripcion->estado) }}
                            </span>
                        </div>
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-5 text-muted small">Inicio</dt>
                        <dd class="col-7 small">{{ $suscripcion->inicio?->format('d/m/Y') ?? '—' }}</dd>

                        <dt class="col-5 text-muted small">Vencimiento</dt>
                        <dd class="col-7 small">{{ $suscripcion->fin?->format('d/m/Y') ?? '—' }}</dd>

                        <dt class="col-5 text-muted small">Días restantes</dt>
                        <dd class="col-7 small fw-bold">{{ $suscripcion->diasRestantes() }}</dd>

                        <dt class="col-5 text-muted small">Ciclo</dt>
                        <dd class="col-7 small">{{ ucfirst($suscripcion->ciclo) }}</dd>

                        <dt class="col-5 text-muted small">Monto</dt>
                        <dd class="col-7 small">
                            ${{ number_format($suscripcion->monto_pagado ?? 0, 2, ',', '.') }}
                        </dd>
                    </dl>
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center py-4 gap-3 text-center">
                        <i class="bi bi-exclamation-triangle fs-2 text-warning opacity-75"></i>
                        <div>
                            <div class="fw-semibold text-warning-emphasis">Sin suscripción asignada</div>
                            <div class="text-muted small mt-1">Este gimnasio no tiene ningún plan activo.</div>
                        </div>
                        <a href="{{ route('saas.gimnasios.edit', $gimnasio->id) }}"
                           class="btn btn-sm btn-warning">
                            <i class="bi bi-plus-lg me-1"></i> Asignar plan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Usuarios internos --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-person-badge me-2"></i>Usuarios internos
                    <span class="badge bg-secondary ms-1">{{ $gimnasio->usuarios->count() }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                @if($gimnasio->usuarios->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-people fs-2 d-block mb-2 opacity-25"></i>
                        Sin usuarios registrados
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($gimnasio->usuarios as $usuario)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4">
                            <div>
                                <div class="small fw-semibold">{{ $usuario->nombre }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $usuario->email }}</div>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-1">
                                <span class="badge bg-light text-secondary border">{{ $usuario->rol ?? 'staff' }}</span>
                                @if($usuario->activo)
                                    <span class="badge bg-success-subtle text-success" style="font-size:.65rem">Activo</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger" style="font-size:.65rem">Inactivo</span>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- Últimas suscripciones SaaS --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2"></i>Historial de suscripciones</h6>
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
                                <th class="text-end pe-4">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasSuscripciones as $sus)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $sus->plan?->nombre ?? '—' }}</td>
                                <td class="text-muted small">{{ ucfirst($sus->ciclo) }}</td>
                                <td class="small">{{ $sus->inicio?->format('d/m/Y') ?? '—' }}</td>
                                <td class="small">{{ $sus->fin?->format('d/m/Y') ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $badgeMap[$sus->estado] ?? 'secondary' }}">
                                        {{ ucfirst($sus->estado) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 fw-semibold">
                                    ${{ number_format($sus->monto_pagado ?? 0, 2, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Sin registros.</td>
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
