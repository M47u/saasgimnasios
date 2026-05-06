<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class PlanNutricionalComentario extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'plan_id', 'comida_id', 'socio_id', 'gimnasio_id',
        'texto', 'revisado', 'revisado_por', 'revisado_at',
    ];

    protected $casts = [
        'revisado'    => 'boolean',
        'revisado_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanNutricional::class, 'plan_id');
    }

    public function comida(): BelongsTo
    {
        return $this->belongsTo(Comida::class, 'comida_id');
    }

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'revisado_por');
    }

    public function esSobreElPlan(): bool
    {
        return is_null($this->comida_id);
    }

    public function marcarRevisado(GymUser $user): void
    {
        $this->update([
            'revisado'     => true,
            'revisado_por' => $user->id,
            'revisado_at'  => now(),
        ]);
    }

    public function scopePendientes($query)
    {
        return $query->where('revisado', false);
    }
}
