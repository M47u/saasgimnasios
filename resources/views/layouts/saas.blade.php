<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') — SaaS Gimnasios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --saas-color: #534AB7; --saas-dark: #3d368a; }
        body { background: #f4f5fb; }
        .navbar-saas { background: var(--saas-color); }
        .sidebar {
            width: 240px; min-height: calc(100vh - 56px);
            background: #1e1b3a; position: fixed; top: 56px; left: 0;
            padding-top: 1rem; z-index: 100;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.7); padding: .6rem 1.5rem;
            font-size: .9rem; border-radius: 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: var(--saas-color);
        }
        .sidebar .nav-link i { margin-right: .6rem; width: 18px; }
        .sidebar .nav-section {
            font-size: .7rem; font-weight: 600; letter-spacing: .08em;
            color: rgba(255,255,255,.35); padding: 1rem 1.5rem .3rem;
            text-transform: uppercase;
        }
        .main-content { margin-left: 240px; padding: 2rem; min-height: calc(100vh - 56px); }
        .btn-saas { background: var(--saas-color); color: #fff; }
        .btn-saas:hover { background: var(--saas-dark); color: #fff; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-dark navbar-saas fixed-top px-3 py-0" style="height:56px">
    <span class="navbar-brand fw-bold fs-6">
        <i class="bi bi-hexagon-fill me-2"></i>SaaS Gimnasios — Admin
    </span>
    <div class="d-flex align-items-center gap-3">
        <span class="text-white-50 small">{{ auth('saas')->user()->nombre ?? '' }}</span>
        <form method="POST" action="{{ route('saas.logout') }}" class="m-0">
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
        <a href="{{ route('saas.dashboard') }}"
           class="nav-link {{ request()->routeIs('saas.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
    </nav>

    <span class="nav-section">Gestión</span>
    <nav class="nav flex-column">
        <a href="{{ route('saas.gimnasios.index') }}"
           class="nav-link {{ request()->routeIs('saas.gimnasios*') ? 'active' : '' }}">
            <i class="bi bi-building"></i> Gimnasios
        </a>
        <a href="#" class="nav-link"><i class="bi bi-credit-card"></i> Suscripciones</a>
        <a href="#" class="nav-link"><i class="bi bi-tags"></i> Planes</a>
    </nav>

    <span class="nav-section">Soporte</span>
    <nav class="nav flex-column">
        <a href="#" class="nav-link"><i class="bi bi-headset"></i> Soporte</a>
        <a href="#" class="nav-link"><i class="bi bi-gear"></i> Configuración</a>
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
