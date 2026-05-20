@extends('layouts.saas')

@section('title', 'Nuevo plan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Nuevo plan</h4>
        <p class="text-muted mb-0 small">Completá los datos para crear un nuevo plan de suscripción</p>
    </div>
    <a href="{{ route('saas.planes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<form method="POST" action="{{ route('saas.planes.store') }}">
    @csrf
    @include('saas.planes._form')
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-saas">
            <i class="bi bi-check-lg me-1"></i> Crear plan
        </button>
        <a href="{{ route('saas.planes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>

@endsection
