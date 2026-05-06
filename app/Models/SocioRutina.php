<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class SocioRutina extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'socio_id', 'rutina_id', 'gimnasio_id',
        'asignado_por', 'asignada_el', 'fin', 'estado',
    ];

    protected $casts = [
        'asignada_el' => 'date',
        'fin'         => 'date',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function rutina(): BelongsTo
    {
        return $this->belongsTo(Rutina::class, 'rutina_id');
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function asignadoPor(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'asignado_por');
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }
}
