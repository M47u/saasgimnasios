<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasGimnasioScope;
use App\Traits\Auditable;
use Carbon\Carbon;

class Membresia extends Model
{
    use HasGimnasioScope, Auditable;

    protected $fillable = [
        'socio_id', 'plan_id', 'gimnasio_id', 'inicio', 'fin',
        'dias_congelados', 'congelada_desde', 'estado', 'registrado_por',
    ];

    protected $casts = [
        'inicio'          => 'date',
        'fin'             => 'date',
        'congelada_desde' => 'date',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanMembresia::class, 'plan_id');
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'registrado_por');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'membresia_id');
    }

    public function diasRestantes(): int
    {
        return max(0, Carbon::today()->diffInDays($this->fin, false));
    }

    public function proximaAVencer(int $dias = 7): bool
    {
        return $this->estado === 'activa' && $this->diasRestantes() <= $dias;
    }

    public function congelar(): void
    {
        $this->update([
            'estado'          => 'congelada',
            'congelada_desde' => today(),
        ]);
    }

    public function descongelar(): void
    {
        $diasCongelados = Carbon::parse($this->congelada_desde)->diffInDays(today());
        $this->update([
            'estado'          => 'activa',
            'fin'             => $this->fin->addDays($diasCongelados),
            'dias_congelados' => $this->dias_congelados + $diasCongelados,
            'congelada_desde' => null,
        ]);
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', 'activa')->where('fin', '<', today());
    }

    public function scopeProximasAVencer($query, int $dias = 7)
    {
        return $query->where('estado', 'activa')
                     ->whereBetween('fin', [today(), today()->addDays($dias)]);
    }
}
