<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasGimnasioScope;

class MedidaCorporal extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'socio_id', 'gimnasio_id', 'peso_kg', 'altura_cm',
        'cintura_cm', 'cadera_cm', 'pecho_cm', 'brazo_cm', 'muslo_cm',
        'foto_path', 'fuente', 'validado_por_gym', 'observaciones',
    ];

    protected $casts = [
        'validado_por_gym' => 'boolean',
        'peso_kg'          => 'decimal:2',
        'altura_cm'        => 'decimal:2',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function getImcAttribute(): ?float
    {
        if (! $this->peso_kg || ! $this->altura_cm) return null;
        $alturaM = $this->altura_cm / 100;
        return round($this->peso_kg / ($alturaM ** 2), 1);
    }

    public function getCategoriaImcAttribute(): ?string
    {
        return match (true) {
            $this->imc === null   => null,
            $this->imc < 18.5     => 'bajo_peso',
            $this->imc < 25       => 'normal',
            $this->imc < 30       => 'sobrepeso',
            default               => 'obesidad',
        };
    }
}
