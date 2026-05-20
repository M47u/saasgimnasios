@extends('layouts.saas')

@section('title', 'Editar plan — ' . $plan->nombre)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Editar plan: {{ $plan->nombre }}</h4>
        <p class="text-muted mb-0 small">
            Los cambios de precio no afectan suscripciones ya registradas
            @if(($plan->suscripciones_activas ?? 0) > 0)
                &nbsp;·&nbsp;
                <span class="text-warning fw-semibold">{{ $plan->suscripciones_activas }} suscripción(es) activa(s)</span>
            @endif
        </p>
    </div>
    <a href="{{ route('saas.planes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<form method="POST" action="{{ route('saas.planes.update', $plan->id) }}">
    @csrf
    @method('PUT')
    @include('saas.planes._form')
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-saas">
            <i class="bi bi-check-lg me-1"></i> Guardar cambios
        </button>
        <a href="{{ route('saas.planes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

@endsection
