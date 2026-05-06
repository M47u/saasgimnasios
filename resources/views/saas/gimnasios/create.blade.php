@extends('layouts.saas')

@section('title', 'Nuevo gimnasio')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Nuevo gimnasio</h4>
        <p class="text-muted mb-0">Registrá un nuevo gimnasio en la plataforma</p>
    </div>
    <a href="{{ route('saas.gimnasios.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<form method="POST" action="{{ route('saas.gimnasios.store') }}">
@csrf

<div class="row g-4">

    {{-- Datos principales --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-building me-2 text-saas"></i>Datos del gimnasio</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre') }}" placeholder="Ej: CrossFit Mendoza">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="contacto@gimnasio.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono') }}" placeholder="+54 261 000-0000">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                               value="{{ old('direccion') }}" placeholder="Calle, número, piso…">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ciudad</label>
                        <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror"
                               value="{{ old('ciudad') }}" placeholder="Mendoza">
                        @error('ciudad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Provincia</label>
                        <input type="text" name="provincia" class="form-control @error('provincia') is-invalid @enderror"
                               value="{{ old('provincia') }}" placeholder="Mendoza">
                        @error('provincia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                        <div class="col-12">
                            <hr class="my-3">
                        </div>
                    
                        <div class="col-12">
                            <label class="form-label fw-semibold">Email del administrador <span class="text-danger">*</span></label>
                            <input type="email" name="email_admin" class="form-control @error('email_admin') is-invalid @enderror"
                                   value="{{ old('email_admin') }}" placeholder="admin@gimnasio.com" required>
                            @error('email_admin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <p class="text-muted small mt-2 mb-0">
                                Correo para el usuario administrador del gimnasio. Se generará automáticamente una contraseña temporal.
                            </p>
                        </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Empresa y plan --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-briefcase me-2 text-saas"></i>Empresa</h6>
            </div>
            <div class="card-body">
                <label class="form-label fw-semibold">Empresa existente</label>
                <select name="empresa_id" class="form-select @error('empresa_id') is-invalid @enderror">
                    <option value="">— Crear empresa automáticamente —</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}" {{ old('empresa_id') == $empresa->id ? 'selected' : '' }}>
                            {{ $empresa->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('empresa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <p class="text-muted small mt-2 mb-0">
                    Si no elegís una empresa, se creará una automáticamente con el nombre del gimnasio.
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-tags me-2 text-saas"></i>Plan SaaS</h6>
            </div>
            <div class="card-body">
                <label class="form-label fw-semibold">Plan inicial</label>
                <select name="plan_id" class="form-select @error('plan_id') is-invalid @enderror">
                    <option value="">— Sin plan por ahora —</option>
                    @foreach($planes as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->nombre }} — ${{ number_format($plan->precio_mensual, 0, ',', '.') }}/mes
                        </option>
                    @endforeach
                </select>
                @error('plan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <p class="text-muted small mt-2 mb-0">
                    Se creará una suscripción en estado <strong>trial</strong> por 30 días.
                </p>
            </div>
        </div>
    </div>

</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-saas px-4">
        <i class="bi bi-check-lg me-1"></i> Crear gimnasio
    </button>
    <a href="{{ route('saas.gimnasios.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
</div>

</form>
@endsection
