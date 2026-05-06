<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';

    protected $fillable = [
        'empresa_id', 'plan_id', 'ciclo', 'inicio', 'fin',
        'trial_ends_at', 'estado', 'monto_pagado',
        'comprobante', 'registrado_por', 'notas',
    ];

    protected $casts = [
        'inicio'        => 'date',
        'fin'           => 'date',
        'trial_ends_at' => 'date',
        'monto_pagado'  => 'decimal:2',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SaasPlan::class, 'plan_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(SaasUser::class, 'registrado_por');
    }

    public function estaEnTrial(): bool
    {
        return $this->estado === 'trial'
            && $this->trial_ends_at
            && $this->trial_ends_at->isFuture();
    }

    public function estaActiva(): bool
    {
        return in_array($this->estado, ['activa', 'trial'])
            && $this->fin->isFuture();
    }

    public function diasRestantes(): int
    {
        return max(0, Carbon::today()->diffInDays($this->fin, false));
    }

    public function proximaAVencer(int $dias = 7): bool
    {
        return $this->estaActiva() && $this->diasRestantes() <= $dias;
    }
}
