<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasGimnasioScope;

class Clase extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'gimnasio_id', 'entrenador_id', 'nombre', 'descripcion',
        'cupo_maximo', 'duracion_min', 'hora_inicio',
        'recurrencia', 'dias_semana', 'activo',
    ];

    protected $casts = [
        'dias_semana' => 'array',
        'activo'      => 'boolean',
        'hora_inicio' => 'datetime:H:i',
    ];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function entrenador(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'entrenador_id');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'clase_id');
    }

    public function reservasDeHoy(): HasMany
    {
        return $this->hasMany(Reserva::class, 'clase_id')
                    ->whereDate('fecha', today())
                    ->where('estado', '!=', 'cancelada');
    }

    public function cuposDisponibles(\Carbon\Carbon $fecha): int
    {
        $ocupados = $this->reservas()
            ->whereDate('fecha', $fecha)
            ->whereIn('estado', ['reservada', 'asistio'])
            ->count();

        return max(0, $this->cupo_maximo - $ocupados);
    }

    public function tieneDisponibilidad(\Carbon\Carbon $fecha): bool
    {
        return $this->cuposDisponibles($fecha) > 0;
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}
