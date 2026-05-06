<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Gimnasio extends Model
{
    use Auditable;

    protected $fillable = [
        'empresa_id', 'nombre', 'slug', 'direccion', 'ciudad',
        'provincia', 'pais', 'telefono', 'email', 'logo_path',
        'horarios', 'config_notificaciones', 'mp_access_token',
        'politicas', 'estado',
    ];

    protected $casts = [
        'horarios'              => 'array',
        'config_notificaciones' => 'array',
        'mp_access_token'       => 'encrypted',
    ];

    protected $hidden = ['mp_access_token'];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(GymUser::class, 'gimnasio_id');
    }

    public function socios(): HasMany
    {
        return $this->hasMany(Socio::class, 'gimnasio_id');
    }

    public function planesMembresia(): HasMany
    {
        return $this->hasMany(PlanMembresia::class, 'gimnasio_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'gimnasio_id');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'gimnasio_id');
    }

    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class, 'gimnasio_id');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'gimnasio_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(TicketSoporte::class, 'gimnasio_id');
    }

    public function suscripcionActiva(): ?Suscripcion
    {
        return $this->empresa?->suscripcionActiva;
    }

    public function plan(): ?SaasPlan
    {
        return $this->suscripcionActiva()?->plan;
    }

    public function limiteSociosAlcanzado(): bool
    {
        $plan = $this->plan();
        if (! $plan || $plan->esIlimitado('max_socios')) return false;
        return $this->socios()->where('estado', 'activo')->count() >= $plan->max_socios;
    }

    public function limiteUsuariosAlcanzado(): bool
    {
        $plan = $this->plan();
        if (! $plan || $plan->esIlimitado('max_usuarios')) return false;
        return $this->usuarios()->where('activo', true)->count() >= $plan->max_usuarios;
    }

    public function consumoIaMes(): int
    {
        return IaConversacion::where('gimnasio_id', $this->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('tokens_usados');
    }
}
