<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class Asistencia extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'gimnasio_id', 'socio_id',
        'metodo_registro', 'registrado_por', 'ingreso',
    ];

    protected $casts = [
        'ingreso' => 'datetime',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'registrado_por');
    }

    public function scopeDeHoy($query)
    {
        return $query->whereDate('ingreso', today());
    }

    public function scopeDelMes($query)
    {
        return $query->whereMonth('ingreso', now()->month)
                     ->whereYear('ingreso', now()->year);
    }
}
