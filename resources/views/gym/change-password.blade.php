@extends('layouts.gym')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">

        @if(auth('gym')->user()->must_change_password)
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-shield-lock-fill fs-5"></i>
            <div>
                <strong>Cambio de contraseña obligatorio.</strong>
                Debes establecer una contraseña personal antes de continuar.
            </div>
        </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header border-0 pb-0" style="background:transparent">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-key-fill me-2" style="color:var(--gym-color)"></i>
                    Cambiar contraseña
                </h5>
                <p class="text-muted small mt-1 mb-0">
                    La nueva contraseña debe tener al menos 8 caracteres, mayúsculas,
                    minúsculas, números y un símbolo especial.
                </p>
            </div>

            <div class="card-body pt-3">
                <form method="POST" action="{{ route('gym.password.update') }}" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Contraseña actual --}}
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-medium">
                            Contraseña actual
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                class="form-control border-start-0 @error('current_password') is-invalid @enderror"
                                autocomplete="current-password"
                                required
                            >
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Nueva contraseña --}}
                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">
                            Nueva contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control border-start-0 @error('password') is-invalid @enderror"
                                autocomplete="new-password"
                                required
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="password-hint" class="form-text text-muted small mt-1">
                            Mínimo 8 caracteres · mayúsculas y minúsculas · números · símbolo (!@#$…)
                        </div>
                    </div>

                    {{-- Confirmar nueva contraseña --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-medium">
                            Confirmar nueva contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control border-start-0"
                                autocomplete="new-password"
                                required
                            >
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-gym fw-semibold py-2">
                            <i class="bi bi-check2-circle me-1"></i> Guardar nueva contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
