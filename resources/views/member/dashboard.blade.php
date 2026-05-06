@extends('layouts.member')

@section('title', 'Inicio')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Inicio</h4>
        <p class="text-muted mb-0">Tu panel personal</p>
    </div>
</div>

<div class="alert d-flex align-items-center gap-3 py-3"
     style="background:#fdf0ec; border-color:#993C1D; color:#5c2210" role="alert">
    <i class="bi bi-fire fs-4" style="color:#993C1D"></i>
    <div>
        <strong>Bienvenido a tu panel</strong><br>
        <span class="small">Seguí tu progreso, tu rutina y tu plan nutricional desde acá.</span>
    </div>
</div>
@endsection
