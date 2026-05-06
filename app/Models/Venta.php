<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class Venta extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'gimnasio_id', 'producto_id', 'socio_id', 'usuario_id',
        'caja_id', 'cantidad', 'precio_unitario', 'total', 'metodo_pago',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'total'           => 'decimal:2',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'usuario_id');
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

    public function scopeDelMes($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}
