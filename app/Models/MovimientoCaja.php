<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class MovimientoCaja extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'caja_id', 'gimnasio_id', 'tipo', 'monto',
        'concepto', 'referencia_id', 'referencia_tipo', 'registrado_por',
    ];

    protected $casts = ['monto' => 'decimal:2'];

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'registrado_por');
    }

    public function scopeIngresos($query)
    {
        return $query->where('tipo', 'ingreso');
    }

    public function scopeEgresos($query)
    {
        return $query->where('tipo', 'egreso');
    }
}
