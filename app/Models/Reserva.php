<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class Reserva extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'clase_id', 'socio_id', 'gimnasio_id', 'fecha', 'estado',
    ];

    protected $casts = ['fecha' => 'date'];

    public function clase(): BelongsTo
    {
        return $this->belongsTo(Clase::class, 'clase_id');
    }

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function cancelar(): void
    {
        $this->update(['estado' => 'cancelada']);
    }

    public function scopeActivas($query)
    {
        return $query->whereIn('estado', ['reservada', 'asistio']);
    }
}
