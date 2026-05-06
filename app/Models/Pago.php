<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;
use App\Traits\Auditable;

class Pago extends Model
{
    use HasGimnasioScope, Auditable;

    protected $fillable = [
        'gimnasio_id', 'socio_id', 'membresia_id', 'monto', 'metodo',
        'estado', 'mp_payment_id', 'mp_status', 'comprobante_path',
        'observaciones', 'registrado_por', 'pagado_at',
    ];

    protected $casts = [
        'monto'     => 'decimal:2',
        'pagado_at' => 'datetime',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function membresia(): BelongsTo
    {
        return $this->belongsTo(Membresia::class, 'membresia_id');
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'registrado_por');
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopeDelMes($query, int $mes = null, int $anio = null)
    {
        return $query->whereMonth('pagado_at', $mes ?? now()->month)
                     ->whereYear('pagado_at', $anio ?? now()->year);
    }

    public function esDeMercadoPago(): bool
    {
        return $this->metodo === 'mercadopago' && $this->mp_payment_id;
    }
}
