@extends('layouts.saas')

@section('title', 'Editar — ' . $gimnasio->nombre)

@section('content')
@php
$badgeMap = ['activa' => 'success', 'trial' => 'warning text-dark', 'vencida' => 'danger', 'suspendida' => 'secondary'];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Editar gimnasio</h4>
        <p class="text-muted mb-0">{{ $gimnasio->nombre }}</p>
    </div>
    <a href="{{ route('saas.gimnasios.show', $gimnasio->id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

{{-- ── FORM DATOS GENERALES (PUT) ─────────────────────────────────────────── --}}
<form method="POST" action="{{ route('saas.gimnasios.update', $gimnasio->id) }}">
@csrf
@method('PUT')

<div class="row g-4">

    {{-- Datos principales --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-building me-2"></i>Datos del gimnasio</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre"
                               class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $gimnasio->nombre) }}">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $gimnasio->email) }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono"
                               class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono', $gimnasio->telefono) }}">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" name="direccion"
                               class="form-control @error('direccion') is-invalid @enderror"
                               value="{{ old('direccion', $gimnasio->direccion) }}">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ciudad</label>
                        <input type="text" name="ciudad"
                               class="form-control @error('ciudad') is-invalid @enderror"
                               value="{{ old('ciudad', $gimnasio->ciudad) }}">
                        @error('ciudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Provincia</label>
                        <input type="text" name="provincia"
                               class="form-control @error('provincia') is-invalid @enderror"
                               value="{{ old('provincia', $gimnasio->provincia) }}">
                        @error('provincia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Empresa --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-briefcase me-2"></i>Empresa</h6>
            </div>
            <div class="card-body">
                <label class="form-label fw-semibold">Empresa</label>
                <select name="empresa_id" class="form-select @error('empresa_id') is-invalid @enderror">
                    <option value="">— Sin empresa —</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}"
                            {{ old('empresa_id', $gimnasio->empresa_id) == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('empresa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

</div>

<div class="d-flex gap-2 mt-4 mb-5">
    <button type="submit" class="btn btn-saas px-4">
        <i class="bi bi-check-lg me-1"></i> Guardar cambios
    </button>
    <a href="{{ route('saas.gimnasios.show', $gimnasio->id) }}" class="btn btn-outline-secondary px-4">Cancelar</a>
</div>

</form>

{{-- ── SECCIÓN SUSCRIPCIÓN (form propio POST) ──────────────────────────────── --}}
<hr class="my-2">
<h6 class="text-muted text-uppercase fw-semibold mb-3" style="font-size:.75rem;letter-spacing:.08em">
    <i class="bi bi-credit-card me-1"></i> Suscripción SaaS
</h6>

@if($suscripcion)
{{-- ── Tiene suscripción activa ─────────────────────────────────────── --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">Suscripción activa</h6>
        <button class="btn btn-sm btn-outline-primary" type="button"
                data-bs-toggle="collapse" data-bs-target="#cambiarPlanForm" aria-expanded="false">
            <i class="bi bi-arrow-repeat me-1"></i> Cambiar plan
        </button>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <div class="text-muted small mb-1">Plan actual</div>
                <div class="fw-bold fs-6">{{ $suscripcion->plan?->nombre ?? '—' }}</div>
            </div>
            <div class="col-md-2">
                <div class="text-muted small mb-1">Estado</div>
                <span class="badge bg-{{ $badgeMap[$suscripcion->estado] ?? 'secondary' }} rounded-pill">
                    {{ ucfirst($suscripcion->estado) }}
                </span>
            </div>
            <div class="col-md-2">
                <div class="text-muted small mb-1">Inicio</div>
                <div class="small">{{ $suscripcion->inicio?->format('d/m/Y') ?? '—' }}</div>
            </div>
            <div class="col-md-2">
                <div class="text-muted small mb-1">Vencimiento</div>
                <div class="small">{{ $suscripcion->fin?->format('d/m/Y') ?? '—' }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small mb-1">Días restantes</div>
                @php $dias = $suscripcion->diasRestantes(); @endphp
                <div class="fw-bold {{ $dias <= 7 ? 'text-danger' : ($dias <= 30 ? 'text-warning' : 'text-success') }}">
                    {{ $dias }} días
                </div>
            </div>
        </div>

        {{-- Cambiar plan (collapse) --}}
        <div class="collapse mt-4" id="cambiarPlanForm">
            <div class="border rounded-3 p-4 bg-light">
                <p class="text-muted small mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Al cambiar el plan, la suscripción actual quedará marcada como <strong>vencida</strong>
                    y se creará una nueva.
                </p>
                <form method="POST" action="{{ route('saas.gimnasios.suscripcion', $gimnasio->id) }}">
                    @csrf
                    <input type="hidden" name="cambiar_plan" value="1">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Nuevo plan <span class="text-danger">*</span></label>
                            <select name="plan_id" class="form-select" required>
                                <option value="">— Seleccionar plan —</option>
                                @foreach($planes as $plan)
                                <option value="{{ $plan->id }}"
                                    {{ $suscripcion->plan_id == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->nombre }}
                                    — ${{ number_format($plan->precio_mensual, 0, ',', '.') }}/mes
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Ciclo</label>
                            <select name="ciclo" class="form-select">
                                <option value="mensual" {{ $suscripcion->ciclo === 'mensual' ? 'selected' : '' }}>Mensual</option>
                                <option value="anual"   {{ $suscripcion->ciclo === 'anual'   ? 'selected' : '' }}>Anual (365 días)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100"
                                    onclick="return confirm('¿Confirmar el cambio de plan?')">
                                <i class="bi bi-arrow-repeat me-1"></i> Confirmar cambio
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@else
{{-- ── Sin suscripción ──────────────────────────────────────────────── --}}
<div class="card border-0 shadow-sm border-start border-4 border-warning">
    <div class="card-header bg-warning-subtle border-bottom py-3">
        <h6 class="mb-0 fw-semibold text-warning-emphasis">
            <i class="bi bi-exclamation-triangle me-2"></i>Sin suscripción asignada
        </h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('saas.gimnasios.suscripcion', $gimnasio->id) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Plan <span class="text-danger">*</span></label>
                    <select name="plan_id"
                            class="form-select @error('plan_id') is-invalid @enderror" required>
                        <option value="">— Seleccionar plan —</option>
                        @foreach($planes as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->nombre }}
                            — ${{ number_format($plan->precio_mensual, 0, ',', '.') }}/mes
                        </option>
                        @endforeach
                    </select>
                    @error('plan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Ciclo</label>
                    <select name="ciclo" class="form-select">
                        <option value="mensual" {{ old('ciclo') === 'mensual' ? 'selected' : '' }}>Mensual</option>
                        <option value="anual"   {{ old('ciclo') === 'anual'   ? 'selected' : '' }}>Anual</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Monto pagado ($)</label>
                    <input type="number" name="monto_pagado" class="form-control"
                           step="0.01" min="0" placeholder="0.00"
                           value="{{ old('monto_pagado') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Comprobante</label>
                    <input type="text" name="comprobante" class="form-control"
                           placeholder="Nro. o referencia"
                           value="{{ old('comprobante') }}">
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="es_trial" value="1"
                               class="form-check-input" id="esTrial"
                               {{ old('es_trial') ? 'checked' : '' }}>
                        <label class="form-check-label" for="esTrial">
                            Activar período de prueba (30 días) —
                            estado <strong>trial</strong>, sin cobro registrado
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-saas">
                        <i class="bi bi-check-lg me-1"></i> Asignar suscripción
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
