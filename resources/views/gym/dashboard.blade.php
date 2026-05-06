@extends('layouts.gym')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Dashboard</h4>
        <p class="text-muted mb-0">{{ now()->locale('es')->isoFormat('dddd D [de] MMMM, YYYY') }}</p>
    </div>
</div>

{{-- ── Alerta socios sin asistencia ─────────────────────────────────────── --}}
@if($sociosSinAsistencia > 0)
<div class="alert d-flex align-items-center gap-3 mb-4"
     style="background:#fff3e0; border-color:#fb8c00; color:#6d3a00" role="alert">
    <i class="bi bi-person-exclamation fs-4" style="color:#fb8c00"></i>
    <div>
        <strong>{{ $sociosSinAsistencia }} {{ $sociosSinAsistencia === 1 ? 'socio no concurrió' : 'socios no concurrieron' }}</strong>
        al gimnasio en los últimos 15 días.
        Considerá contactarlos para retenerlos.
    </div>
</div>
@endif

{{-- ── Fila 1: 4 métricas principales ──────────────────────────────────── --}}
<div class="row g-3 mb-3">

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0" style="background:rgba(29,158,117,.12)">
                    <i class="bi bi-people-fill fs-3" style="color:#1D9E75"></i>
                </div>
                <div>
                    <div class="text-muted small">Socios activos</div>
                    <div class="fs-2 fw-bold lh-1">{{ $sociosActivos }}</div>
                    <div class="text-muted" style="font-size:.72rem">
                        +{{ $nuevosSociosMes }} este mes
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0 bg-info-subtle">
                    <i class="bi bi-door-open-fill fs-3 text-info"></i>
                </div>
                <div>
                    <div class="text-muted small">Asistencias hoy</div>
                    <div class="fs-2 fw-bold lh-1">{{ $asistenciasHoy }}</div>
                    @if($clasesHoy > 0)
                    <div class="text-muted" style="font-size:.72rem">
                        {{ $clasesHoy }} {{ $clasesHoy === 1 ? 'clase' : 'clases' }} programadas
                    </div>
                    @else
                    <div class="text-muted" style="font-size:.72rem">sin clases programadas</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0 bg-success-subtle">
                    <i class="bi bi-cash-stack fs-3 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Cobros hoy</div>
                    <div class="fs-2 fw-bold lh-1">${{ number_format($pagosHoy, 0, ',', '.') }}</div>
                    <div class="text-muted" style="font-size:.72rem">pagos aprobados</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0" style="background:rgba(83,74,183,.1)">
                    <i class="bi bi-graph-up-arrow fs-3" style="color:#534AB7"></i>
                </div>
                <div>
                    <div class="text-muted small">Ingresos del mes</div>
                    <div class="fs-2 fw-bold lh-1">${{ number_format($ingresosMes, 0, ',', '.') }}</div>
                    <div class="text-muted" style="font-size:.72rem">{{ now()->locale('es')->monthName }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Fila 2: 3 métricas secundarias ───────────────────────────────────── --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 {{ $sociosVencidos > 0 ? 'border-start border-4 border-danger' : '' }}">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0 bg-danger-subtle">
                    <i class="bi bi-calendar-x fs-4 text-danger"></i>
                </div>
                <div>
                    <div class="text-muted small">Membresías vencidas</div>
                    <div class="fs-3 fw-bold lh-1 {{ $sociosVencidos > 0 ? 'text-danger' : '' }}">
                        {{ $sociosVencidos }}
                    </div>
                    <div class="text-muted" style="font-size:.72rem">socios sin renovar</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 {{ $membresiasPorVencer->count() > 0 ? 'border-start border-4 border-warning' : '' }}">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0 bg-warning-subtle">
                    <i class="bi bi-clock-history fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Vencen en 7 días</div>
                    <div class="fs-3 fw-bold lh-1 {{ $membresiasPorVencer->count() > 0 ? 'text-warning' : '' }}">
                        {{ $membresiasPorVencer->count() }}
                    </div>
                    <div class="text-muted" style="font-size:.72rem">membresías próximas</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 flex-shrink-0 bg-primary-subtle">
                    <i class="bi bi-person-plus fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Nuevos este mes</div>
                    <div class="fs-3 fw-bold lh-1 text-primary">{{ $nuevosSociosMes }}</div>
                    <div class="text-muted" style="font-size:.72rem">socios ingresados</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Tablas ────────────────────────────────────────────────────────────── --}}
<div class="row g-4">

    {{-- Asistencias de hoy --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-door-open me-2" style="color:#1D9E75"></i>Asistencias de hoy
                </h6>
                <span class="badge rounded-pill" style="background:#1D9E75">{{ $asistenciasHoy }}</span>
            </div>
            <div class="card-body p-0">
                @if($asistenciasHoyDetalle->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-door-closed fs-2 d-block mb-2 opacity-25"></i>
                        <span class="small">Ningún socio ingresó hoy todavía</span>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Socio</th>
                                    <th class="text-end pe-4">Hora ingreso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asistenciasHoyDetalle as $asistencia)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                 style="width:32px;height:32px;background:rgba(29,158,117,.12);color:#1D9E75;font-size:.8rem;font-weight:600">
                                                {{ mb_strtoupper(mb_substr($asistencia->socio?->nombre ?? '?', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold small">
                                                    {{ $asistencia->socio?->nombre_completo ?? '—' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-light text-secondary border fw-semibold">
                                            {{ $asistencia->ingreso->format('H:i') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Membresías por vencer --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Membresías por vencer
                </h6>
                @if($membresiasPorVencer->count() > 0)
                <span class="badge bg-warning text-dark rounded-pill">{{ $membresiasPorVencer->count() }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                @if($membresiasPorVencer->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-check-circle fs-2 d-block mb-2 text-success opacity-50"></i>
                        <span class="small">Sin membresías próximas a vencer</span>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Socio</th>
                                    <th>Plan</th>
                                    <th class="text-end pe-4">Vence</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($membresiasPorVencer as $membresia)
                                @php $dias = $membresia->diasRestantes(); @endphp
                                <tr>
                                    <td class="ps-4 fw-semibold small">
                                        {{ $membresia->socio?->nombre_completo ?? '—' }}
                                    </td>
                                    <td class="text-muted small">
                                        {{ $membresia->plan?->nombre ?? '—' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="badge {{ $dias <= 2 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill">
                                            {{ $dias === 0 ? 'hoy' : ($dias === 1 ? 'mañana' : "en {$dias}d") }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
