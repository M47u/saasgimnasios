<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class Notificacion extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'gimnasio_id', 'socio_id', 'tipo', 'titulo',
        'mensaje', 'leida', 'email_enviado', 'enviada_at',
    ];

    protected $casts = [
        'leida'         => 'boolean',
        'email_enviado' => 'boolean',
        'enviada_at'    => 'datetime',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function marcarLeida(): void
    {
        $this->update(['leida' => true]);
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopePendientesDeEmail($query)
    {
        return $query->where('email_enviado', false);
    }
}
