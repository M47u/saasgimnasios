<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\HasGimnasioScope;
use App\Traits\Auditable;
use Carbon\Carbon;

class Socio extends Model
{
    use HasGimnasioScope, Auditable;

    protected $fillable = [
        'gimnasio_id', 'nombre', 'apellido', 'email', 'telefono',
        'fecha_nacimiento', 'dni', 'foto_path', 'objetivo', 'nivel',
        'frecuencia_semanal', 'restricciones_alimentarias',
        'racha_actual', 'racha_ultima_asistencia', 'observaciones', 'estado',
    ];

    protected $casts = [
        'fecha_nacimiento'       => 'date',
        'racha_ultima_asistencia' => 'date',
    ];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function memberUser(): HasOne
    {
        return $this->hasOne(MemberUser::class, 'socio_id');
    }

    public function membresias(): HasMany
    {
        return $this->hasMany(Membresia::class, 'socio_id');
    }

    public function membresiaActiva(): HasOne
    {
        return $this->hasOne(Membresia::class, 'socio_id')
                    ->where('estado', 'activa')
                    ->latestOfMany('fin');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'socio_id');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'socio_id');
    }

    public function rutinas(): HasMany
    {
        return $this->hasMany(SocioRutina::class, 'socio_id');
    }

    public function rutinaActiva(): HasOne
    {
        return $this->hasOne(SocioRutina::class, 'socio_id')
                    ->where('estado', 'activa')
                    ->latestOfMany();
    }

    public function medidas(): HasMany
    {
        return $this->hasMany(MedidaCorporal::class, 'socio_id');
    }

    public function ultimaMedida(): HasOne
    {
        return $this->hasOne(MedidaCorporal::class, 'socio_id')->latestOfMany();
    }

    public function logros(): HasMany
    {
        return $this->hasMany(SocioLogro::class, 'socio_id');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'socio_id');
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'socio_id');
    }

    public function planesNutricionales(): HasMany
    {
        return $this->hasMany(PlanNutricional::class, 'socio_id');
    }

    public function planNutricionalActivo(): HasOne
    {
        return $this->hasOne(PlanNutricional::class, 'socio_id')
                    ->where('estado', 'activo')
                    ->latestOfMany();
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento?->age;
    }

    public function tieneMembresiaActiva(): bool
    {
        return $this->membresiaActiva()->exists();
    }

    public function actualizarRacha(): void
    {
        $hoy = Carbon::today();

        if (! $this->racha_ultima_asistencia) {
            $this->update(['racha_actual' => 1, 'racha_ultima_asistencia' => $hoy]);
            return;
        }

        $ultima = Carbon::parse($this->racha_ultima_asistencia);

        if ($ultima->isToday()) {
            return;
        }

        if ($ultima->isYesterday()) {
            $this->increment('racha_actual');
            $this->update(['racha_ultima_asistencia' => $hoy]);
        } else {
            $this->update(['racha_actual' => 1, 'racha_ultima_asistencia' => $hoy]);
        }
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeConMembresiaVencida($query)
    {
        return $query->whereHas('membresias', function ($q) {
            $q->where('estado', 'activa')->where('fin', '<', today());
        });
    }

    public function scopeInactivos($query, int $dias = 30)
    {
        return $query->whereDoesntHave('asistencias', function ($q) use ($dias) {
            $q->where('ingreso', '>=', now()->subDays($dias));
        });
    }
}
