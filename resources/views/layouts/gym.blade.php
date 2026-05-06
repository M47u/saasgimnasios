<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Gimnasio') — {{ auth('gym')->user()?->gimnasio?->nombre ?? 'Gimnasio' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --gym-color: #1D9E75; --gym-dark: #157a5a; }
        body { background: #f0faf6; }
        .navbar-gym { background: var(--gym-color); }
        .sidebar {
            width: 240px; min-height: calc(100vh - 56px);
            background: #0d2b21; position: fixed; top: 56px; left: 0;
            padding-top: 1rem; z-index: 100;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.7); padding: .6rem 1.5rem;
            font-size: .9rem; border-radius: 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: var(--gym-color);
        }
        .sidebar .nav-link i { margin-right: .6rem; width: 18px; }
        .sidebar .nav-section {
            font-size: .7rem; font-weight: 600; letter-spacing: .08em;
            color: rgba(255,255,255,.35); padding: 1rem 1.5rem .3rem;
            text-transform: uppercase;
        }
        .main-content { margin-left: 240px; padding: 2rem; min-height: calc(100vh - 56px); }
        .btn-gym { background: var(--gym-color); color: #fff; }
        .btn-gym:hover { background: var(--gym-dark); color: #fff; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-dark navbar-gym fixed-top px-3 py-0" style="height:56px">
    <span class="navbar-brand fw-bold fs-6">
        <i class="bi bi-lightning-charge-fill me-2"></i>
        {{ auth('gym')->user()?->gimnasio?->nombre ?? 'Panel Gimnasio' }}
    </span>
    <div class="d-flex align-items-center gap-3">
        <span class="text-white-50 small">
            {{ auth('gym')->user()?->nombre_completo ?? '' }}
        </span>
        <form method="POST" action="{{ route('gym.logout') }}" class="m-0">
            @csrf
            <button class="btn btn-sm btn-outline-light">
                <i class="bi bi-box-arrow-right"></i> Salir
            </button>
        </form>
    </div>
</nav>

{{-- Sidebar --}}
<div class="sidebar">
    <span class="nav-section">Principal</span>
    <nav class="nav flex-column">
        <a href="{{ route('gym.dashboard') }}"
           class="nav-link {{ request()->routeIs('gym.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
    </nav>

    <span class="nav-section">Socios</span>
    <nav class="nav flex-column">
        <a href="#" class="nav-link"><i class="bi bi-people"></i> Socios</a>
        <a href="#" class="nav-link"><i class="bi bi-card-checklist"></i> Membresías</a>
        <a href="#" class="nav-link"><i class="bi bi-cash-stack"></i> Pagos</a>
        <a href="#" class="nav-link"><i class="bi bi-door-open"></i> Asistencias</a>
    </nav>

    <span class="nav-section">Operación</span>
    <nav class="nav flex-column">
        <a href="#" class="nav-link"><i class="bi bi-safe2"></i> Caja</a>
        <a href="#" class="nav-link"><i class="bi bi-calendar-event"></i> Clases</a>
        <a href="#" class="nav-link"><i class="bi bi-box-seam"></i> Productos</a>
    </nav>

    <span class="nav-section">Entrenamiento</span>
    <nav class="nav flex-column">
        <a href="#" class="nav-link"><i class="bi bi-activity"></i> Rutinas</a>
        <a href="#" class="nav-link"><i class="bi bi-bar-chart"></i> Reportes</a>
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
