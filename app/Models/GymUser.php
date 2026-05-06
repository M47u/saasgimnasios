<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GymUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'gimnasio_id', 'nombre', 'apellido',
        'email', 'password', 'rol', 'activo',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'activo'    => 'boolean',
        'last_login' => 'datetime',
    ];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function rutinasCreadas(): HasMany
    {
        return $this->hasMany(Rutina::class, 'entrenador_id');
    }

    public function clasesAsignadas(): HasMany
    {
        return $this->hasMany(Clase::class, 'entrenador_id');
    }

    public function ticketRespuestas(): HasMany
    {
        return $this->hasMany(TicketRespuesta::class, 'gym_user_id');
    }

    public function esAdmin(): bool    { return $this->rol === 'admin'; }
    public function esRecep(): bool    { return $this->rol === 'recepcionista'; }
    public function esEntrenador(): bool { return $this->rol === 'entrenador'; }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
}
