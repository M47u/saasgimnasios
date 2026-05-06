<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasGimnasioScope;
use App\Traits\Auditable;

class Producto extends Model
{
    use HasGimnasioScope, Auditable;

    protected $fillable = [
        'gimnasio_id', 'nombre', 'categoria', 'precio',
        'stock', 'stock_minimo', 'descripcion', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio' => 'decimal:2',
    ];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class, 'producto_id');
    }

    public function tieneBajoStock(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function ajustarStock(int $cantidad, string $tipo = 'salida'): void
    {
        if ($tipo === 'entrada') {
            $this->increment('stock', $cantidad);
        } else {
            $this->decrement('stock', $cantidad);
        }
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeBajoStock($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }
}
