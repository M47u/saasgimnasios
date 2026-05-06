<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class EjercicioFeedback extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'socio_id', 'rutina_ejercicio_id', 'gimnasio_id',
        'tipo', 'nota', 'revisado', 'revisado_por', 'revisado_at',
    ];

    protected $casts = [
        'revisado'    => 'boolean',
        'revisado_at' => 'datetime',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function rutinaEjercicio(): BelongsTo
    {
        return $this->belongsTo(RutinaEjercicio::class, 'rutina_ejercicio_id');
    }

    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'revisado_por');
    }

    public function marcarRevisado(GymUser $user): void
    {
        $this->update([
            'revisado'    => true,
            'revisado_por' => $user->id,
            'revisado_at' => now(),
        ]);
    }

    public function scopePendientes($query)
    {
        return $query->where('revisado', false);
    }

    public function scopeProblemas($query)
    {
        return $query->whereIn('tipo', ['dolor', 'dificultad']);
    }
}
