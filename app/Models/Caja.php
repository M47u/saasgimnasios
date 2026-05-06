<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasGimnasioScope;

class Caja extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'gimnasio_id', 'usuario_id', 'monto_apertura',
        'monto_cierre', 'estado', 'apertura', 'cierre', 'observaciones',
    ];

    protected $casts = [
        'apertura'       => 'datetime',
        'cierre'         => 'datetime',
        'monto_apertura' => 'decimal:2',
        'monto_cierre'   => 'decimal:2',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'usuario_id');
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoCaja::class, 'caja_id');
    }

    public function totalIngresos(): float
    {
        return (float) $this->movimientos()->where('tipo', 'ingreso')->sum('monto');
    }

    public function totalEgresos(): float
    {
        return (float) $this->movimientos()->where('tipo', 'egreso')->sum('monto');
    }

    public function saldo(): float
    {
        return $this->monto_apertura + $this->totalIngresos() - $this->totalEgresos();
    }

    public function cerrar(float $montoCierre): void
    {
        $this->update([
            'estado'       => 'cerrada',
            'monto_cierre' => $montoCierre,
            'cierre'       => now(),
        ]);
    }

    public function scopeAbierta($query)
    {
        return $query->where('estado', 'abierta');
    }
}
