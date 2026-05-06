@extends('layouts.saas')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Dashboard</h4>
        <p class="text-muted mb-0">Resumen general del sistema</p>
    </div>
    <span class="text-muted small">{{ now()->format('d/m/Y') }}</span>
</div>

{{-- Métricas --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0" style="background:rgba(83,74,183,.12)">
                    <i class="bi bi-building fs-3" style="color:#534AB7"></i>
                </div>
                <div>
                    <div class="text-muted small">Total gimnasios</div>
                    <div class="fs-2 fw-bold lh-1">{{ $totalGimnasios }}</div>
                    <div class="text-muted" style="font-size:.72rem">
                        {{ $gimnasiosTrial }} en trial &middot; {{ $gimnasiosSuspendidos }} suspendidos
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0 bg-success-subtle">
                    <i class="bi bi-check-circle fs-3 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Gimnasios activos</div>
                    <div class="fs-2 fw-bold lh-1 text-success">{{ $gimnasiosActivos }}</div>
                    <div class="text-muted" style="font-size:.72rem">
                        de {{ $totalGimnasios }} totales
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0 bg-info-subtle">
                    <i class="bi bi-people fs-3 text-info"></i>
                </div>
                <div>
                    <div class="text-muted small">Total socios</div>
                    <div class="fs-2 fw-bold lh-1">{{ number_format($totalSocios) }}</div>
                    <div class="text-muted" style="font-size:.72rem">en toda la plataforma</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0 bg-warning-subtle">
                    <i class="bi bi-clock-history fs-3 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Vencen en 7 días</div>
                    <div class="fs-2 fw-bold lh-1 {{ $suscripcionesVencen->count() > 0 ? 'text-warning' : '' }}">
                        {{ $suscripcionesVencen->count() }}
                    </div>
                    <div class="text-muted" style="font-size:.72rem">suscripciones próximas</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tablas --}}
<div class="row g-4">

    {{-- Gimnasios recientes --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-building me-2"></i>Últimos gimnasios registrados</h6>
                <a href="{{ route('saas.gimnasios.index') }}" class="btn btn-sm btn-outline-secondary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nombre</th>
                            <th>Plan</th>
                            <th class="text-center">Socios</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gimnasiosRecientes as $g)
                        @php
                        $badgeMap = ['activo' => 'success', 'suspendido' => 'danger', 'trial' => 'warning text-dark'];
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('saas.gimnasios.show', $g->id) }}"
                                   class="fw-semibold text-decoration-none text-body">{{ $g->nombre }}</a>
                                <div class="text-muted small">{{ $g->ciudad ?? '—' }}</div>
                            </td>
                            <td class="small">
                                {{ $g->empresa?->suscripcionActiva?->plan?->nombre ?? '—' }}
                            </td>
                            <td class="text-center fw-semibold">{{ $g->socios_count }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $badgeMap[$g->estado] ?? 'secondary' }} rounded-pill">
                                    {{ ucfirst($g->estado) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Sin gimnasios registrados aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Suscripciones a vencer --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Suscripciones a vencer
                </h6>
            </div>
            <div class="card-body p-0">
                @forelse($suscripcionesVencen as $sus)
                @php $gimnasioNombre = $sus->empresa?->gimnasios?->first()?->nombre ?? $sus->empresa?->nombre ?? '—'; @endphp
                <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                    <div>
                        <div class="fw-semibold small">{{ $gimnasioNombre }}</div>
                        <div class="text-muted" style="font-size:.75rem">
                            {{ $sus->plan?->nombre ?? '—' }} &middot; vence {{ $sus->fin?->format('d/m/Y') }}
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark">{{ $sus->diasRestantes() }}d</span>
                </div>
                @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-check-circle fs-2 d-block mb-2 text-success opacity-50"></i>
                    <span class="small">Sin vencimientos próximos</span>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
