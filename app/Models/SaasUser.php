<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaasUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['nombre', 'email', 'password', 'rol', 'activo'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'activo'            => 'boolean',
        'last_login'        => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function ticketRespuestas(): HasMany
    {
        return $this->hasMany(TicketRespuesta::class, 'saas_user_id');
    }

    public function suscripcionesRegistradas(): HasMany
    {
        return $this->hasMany(Suscripcion::class, 'registrado_por');
    }
}
