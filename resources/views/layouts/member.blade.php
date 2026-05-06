<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mi Panel') — Mi Gimnasio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --member-color: #993C1D; --member-dark: #7a2f16; }
        body { background: #fdf6f3; }
        .navbar-member { background: var(--member-color); }
        .sidebar {
            width: 220px; min-height: calc(100vh - 56px);
            background: #2b1208; position: fixed; top: 56px; left: 0;
            padding-top: 1rem; z-index: 100;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.7); padding: .6rem 1.4rem;
            font-size: .9rem; border-radius: 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: var(--member-color);
        }
        .sidebar .nav-link i { margin-right: .6rem; width: 18px; }
        .sidebar .nav-section {
            font-size: .7rem; font-weight: 600; letter-spacing: .08em;
            color: rgba(255,255,255,.35); padding: 1rem 1.4rem .3rem;
            text-transform: uppercase;
        }
        .main-content { margin-left: 220px; padding: 2rem; min-height: calc(100vh - 56px); }
        .btn-member { background: var(--member-color); color: #fff; }
        .btn-member:hover { background: var(--member-dark); color: #fff; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-dark navbar-member fixed-top px-3 py-0" style="height:56px">
    <span class="navbar-brand fw-bold fs-6">
        <i class="bi bi-fire me-2"></i>Mi Gimnasio
    </span>
    <div class="d-flex align-items-center gap-3">
        @php $socio = auth('member')->user()?->socio; @endphp
        <span class="text-white-50 small">
            {{ $socio?->nombre_completo ?? auth('member')->user()?->email ?? '' }}
        </span>
        <form method="POST" action="{{ route('member.logout') }}" class="m-0">
            @csrf
            <button class="btn btn-sm btn-outline-light">
                <i class="bi bi-box-arrow-right"></i> Salir
            </button>
        </form>
    </div>
</nav>

{{-- Sidebar --}}
<div class="sidebar">
    <span class="nav-section">Mi espacio</span>
    <nav class="nav flex-column">
        <a href="{{ route('member.dashboard') }}"
           class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house"></i> Inicio
        </a>
        <a href="#" class="nav-link"><i class="bi bi-card-checklist"></i> Mi membresía</a>
        <a href="#" class="nav-link"><i class="bi bi-activity"></i> Rutina</a>
        <a href="#" class="nav-link"><i class="bi bi-egg-fried"></i> Nutrición</a>
    </nav>

    <span class="nav-section">Seguimiento</span>
    <nav class="nav flex-column">
        <a href="#" class="nav-link"><i class="bi bi-door-open"></i> Asistencias</a>
        <a href="#" class="nav-link"><i class="bi bi-calendar-check"></i> Reservas</a>
        <a href="#" class="nav-link"><i class="bi bi-graph-up"></i> Evolución</a>
        <a href="#" class="nav-link"><i class="bi bi-robot"></i> IA</a>
    </nav>
</div>

{{-- Contenido --}}
<main class="main-content" style="margin-top:56px">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
