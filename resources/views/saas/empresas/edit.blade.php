@extends('layouts.saas')

@section('title', 'Editar empresa — ' . $empresa->nombre)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Editar empresa</h4>
        <p class="text-muted mb-0 small">{{ $empresa->nombre }}</p>
    </div>
    <a href="{{ route('saas.empresas.show', $empresa->id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-building me-2"></i>Datos de la empresa</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('saas.empresas.update', $empresa->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $empresa->nombre) }}" required>
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Razón social</label>
                        <input type="text" name="razon_social" class="form-control @error('razon_social') is-invalid @enderror"
                               value="{{ old('razon_social', $empresa->razon_social) }}"
                               placeholder="Nombre legal completo">
                        @error('razon_social') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $empresa->email) }}">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Teléfono</label>
                            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                                   value="{{ old('telefono', $empresa->telefono) }}">
                            @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">País</label>
                        <select name="pais" class="form-select @error('pais') is-invalid @enderror">
                            <option value="AR" {{ old('pais', $empresa->pais) === 'AR' ? 'selected' : '' }}>Argentina</option>
                            <option value="UY" {{ old('pais', $empresa->pais) === 'UY' ? 'selected' : '' }}>Uruguay</option>
                            <option value="CL" {{ old('pais', $empresa->pais) === 'CL' ? 'selected' : '' }}>Chile</option>
                            <option value="BR" {{ old('pais', $empresa->pais) === 'BR' ? 'selected' : '' }}>Brasil</option>
                            <option value="CO" {{ old('pais', $empresa->pais) === 'CO' ? 'selected' : '' }}>Colombia</option>
                            <option value="MX" {{ old('pais', $empresa->pais) === 'MX' ? 'selected' : '' }}>México</option>
                            <option value="PE" {{ old('pais', $empresa->pais) === 'PE' ? 'selected' : '' }}>Perú</option>
                            <option value="OTHER" {{ ! in_array(old('pais', $empresa->pais), ['AR','UY','CL','BR','CO','MX','PE']) ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('pais') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-saas">
                            <i class="bi bi-check-lg me-1"></i> Guardar cambios
                        </button>
                        <a href="{{ route('saas.empresas.show', $empresa->id) }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
