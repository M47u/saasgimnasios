@extends('layouts.saas')

@section('title', 'Planes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Planes</h4>
        <p class="text-muted mb-0 small">Gestión de planes de suscripción disponibles</p>
    </div>
    <a href="{{ route('saas.planes.create') }}" class="btn btn-saas">
        <i class="bi bi-plus-lg me-1"></i> Nuevo plan
    </a>
</div>

<div class="row g-3">
    @forelse($planes as $plan)
    @php
        $esWildcard = count($plan->modulos_habilitados ?? []) === 1 && ($plan->modulos_habilitados[0] ?? '') === '*';
    @endphp
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 {{ ! $plan->activo ? 'opacity-50' : '' }}">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $plan->nombre }}</h6>
                    <code class="small text-muted">{{ $plan->slug }}</code>
                </div>
                <div class="d-flex flex-column align-items-end gap-1">
                    @if($plan->activo)
                        <span class="badge bg-success-subtle text-success border border-success-subtle">Activo</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary border">Inactivo</span>
                    @endif
                    @if($plan->suscripciones_activas > 0)
                        <span class="badge bg-primary-subtle text-primary" style="font-size:.65rem">
                            {{ $plan->suscripciones_activas }} suscrip.
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                {{-- Precios --}}
                <div class="row g-2 mb-3">
                    <div class="col-6 text-center p-2 bg-light rounded">
                        <div class="fw-bold fs-5">${{ number_format($plan->precio_mensual, 0, ',', '.') }}</div>
                        <div class="text-muted" style="font-size:.7rem">/ mes</div>
                    </div>
                    <div class="col-6 text-center p-2 bg-light rounded">
                        <div class="fw-bold fs-5">${{ number_format($plan->precio_anual, 0, ',', '.') }}</div>
                        <div class="text-muted" style="font-size:.7rem">/ año</div>
                    </div>
                </div>

                {{-- Límites --}}
                <ul class="list-unstyled mb-3 small">
                    <li class="d-flex justify-content-between border-bottom py-1">
                        <span class="text-muted">Socios</span>
                        <span class="fw-semibold">{{ $plan->max_socios == 0 ? 'Ilimitados' : $plan->max_socios }}</span>
                    </li>
                    <li class="d-flex justify-content-between border-bottom py-1">
                        <span class="text-muted">Usuarios</span>
                        <span class="fw-semibold">{{ $plan->max_usuarios == 0 ? 'Ilimitados' : $plan->max_usuarios }}</span>
                    </li>
                    <li class="d-flex justify-content-between border-bottom py-1">
                        <span class="text-muted">Sucursales</span>
                        <span class="fw-semibold">{{ $plan->max_sucursales == 0 ? 'Ilimitadas' : $plan->max_sucursales }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-1">
                        <span class="text-muted">IA / mes</span>
                        <span class="fw-semibold">
                            {{ $plan->limite_ia_mensual == 0 ? 'Sin IA' : number_format($plan->limite_ia_mensual) . ' tokens' }}
                        </span>
                    </li>
                </ul>

                {{-- Módulos --}}
                <div class="mb-0">
                    @if($esWildcard)
                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size:.7rem">
                            <i class="bi bi-infinity me-1"></i>Todos los módulos
                        </span>
                    @else
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($plan->modulos_habilitados ?? [] as $mod)
                                <span class="badge bg-light text-secondary border" style="font-size:.65rem">{{ $mod }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-transparent border-top d-flex gap-2 py-2">
                <a href="{{ route('saas.planes.edit', $plan->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
                <form method="POST" action="{{ route('saas.planes.toggle', $plan->id) }}" class="m-0">
                    @csrf
                    <button class="btn btn-sm {{ $plan->activo ? 'btn-outline-warning' : 'btn-outline-success' }}"
                            title="{{ $plan->activo ? 'Desactivar' : 'Activar' }}">
                        <i class="bi bi-{{ $plan->activo ? 'pause-circle' : 'play-circle' }}"></i>
                    </button>
                </form>
                @if($plan->total_suscripciones == 0)
                <form method="POST" action="{{ route('saas.planes.destroy', $plan->id) }}"
                      onsubmit="return confirm('¿Eliminar el plan «{{ $plan->nombre }}»? Esta acción no se puede deshacer.')" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                        <i class="bi bi-trash3"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-tags fs-2 d-block mb-2 opacity-25"></i>
                No hay planes creados.
                <a href="{{ route('saas.planes.create') }}" class="d-block mt-2">Crear el primer plan</a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@endsection
