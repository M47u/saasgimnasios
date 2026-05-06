<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketSoporte extends Model
{
    protected $fillable = [
        'gimnasio_id', 'gym_user_id', 'asunto',
        'descripcion', 'estado', 'prioridad', 'asignado_a',
    ];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function gymUser(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'gym_user_id');
    }

    public function asignadoA(): BelongsTo
    {
        return $this->belongsTo(SaasUser::class, 'asignado_a');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(TicketRespuesta::class, 'ticket_id');
    }

    public function estaAbierto(): bool
    {
        return in_array($this->estado, ['abierto', 'en_proceso']);
    }

    public function scopeAbiertos($query)
    {
        return $query->whereIn('estado', ['abierto', 'en_proceso']);
    }

    public function scopePorPrioridad($query, string $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }
}
