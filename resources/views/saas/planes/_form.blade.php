@php
    $plan    = $plan ?? null;
    $esNuevo = is_null($plan);
    $esWildcard = ! $esNuevo && count($plan->modulos_habilitados ?? []) === 1 && ($plan->modulos_habilitados[0] ?? '') === '*';
@endphp

<div class="row g-4">

    {{-- Datos básicos --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2"></i>Datos del plan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $plan?->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Precio mensual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="precio_mensual" class="form-control @error('precio_mensual') is-invalid @enderror"
                                   step="0.01" min="0"
                                   value="{{ old('precio_mensual', $plan?->precio_mensual) }}" required>
                            @error('precio_mensual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Precio anual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" name="precio_anual" class="form-control @error('precio_anual') is-invalid @enderror"
                                   step="0.01" min="0"
                                   value="{{ old('precio_anual', $plan?->precio_anual) }}" required>
                            @error('precio_anual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo"
                           {{ old('activo', $plan?->activo ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label small fw-semibold" for="activo">Plan activo (visible para asignar)</label>
                </div>
            </div>
        </div>
    </div>

    {{-- Límites --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-sliders me-2"></i>Límites <span class="text-muted fw-normal small">(0 = ilimitado)</span></h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Máx. socios</label>
                        <input type="number" name="max_socios" class="form-control @error('max_socios') is-invalid @enderror"
                               min="0" value="{{ old('max_socios', $plan?->max_socios ?? 0) }}" required>
                        @error('max_socios') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Máx. usuarios</label>
                        <input type="number" name="max_usuarios" class="form-control @error('max_usuarios') is-invalid @enderror"
                               min="0" value="{{ old('max_usuarios', $plan?->max_usuarios ?? 0) }}" required>
                        @error('max_usuarios') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Máx. sucursales</label>
                        <input type="number" name="max_sucursales" class="form-control @error('max_sucursales') is-invalid @enderror"
                               min="0" value="{{ old('max_sucursales', $plan?->max_sucursales ?? 0) }}" required>
                        @error('max_sucursales') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Límite IA / mes</label>
                        <div class="input-group">
                            <input type="number" name="limite_ia_mensual" class="form-control @error('limite_ia_mensual') is-invalid @enderror"
                                   min="0" value="{{ old('limite_ia_mensual', $plan?->limite_ia_mensual ?? 0) }}" required>
                            <span class="input-group-text small">tokens</span>
                            @error('limite_ia_mensual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Módulos --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-grid me-2"></i>Módulos habilitados</h6>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" name="todos_modulos" value="1"
                           id="todos_modulos"
                           {{ old('todos_modulos', $esWildcard) ? 'checked' : '' }}
                           onchange="toggleModulos(this.checked)">
                    <label class="form-check-label small fw-semibold" for="todos_modulos">
                        Todos los módulos
                    </label>
                </div>
            </div>
            <div class="card-body" id="modulos-grid">
                <div class="row g-2">
                    @foreach($modulos as $mod)
                    @php
                        $checked = old('todos_modulos', $esWildcard)
                            ? false
                            : in_array($mod, old('modulos_habilitados', $plan?->modulos_habilitados ?? []));
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="form-check">
                            <input class="form-check-input modulo-check" type="checkbox"
                                   name="modulos_habilitados[]" value="{{ $mod }}"
                                   id="mod_{{ $mod }}"
                                   {{ $checked ? 'checked' : '' }}
                                   {{ old('todos_modulos', $esWildcard) ? 'disabled' : '' }}>
                            <label class="form-check-label small" for="mod_{{ $mod }}">{{ $mod }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function toggleModulos(allEnabled) {
    document.querySelectorAll('.modulo-check').forEach(cb => {
        cb.disabled = allEnabled;
        if (allEnabled) cb.checked = false;
    });
}
</script>
@endpush
