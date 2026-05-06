<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasGimnasioScope;

class PlanMembresia extends Model
{
    use HasGimnasioScope;

    protected $table = 'planes_membresia';

    protected $fillable = [
        'gimnasio_id', 'nombre', 'precio', 'duracion_dias',
        'incluye_clases', 'dias_acceso_semana', 'descripcion', 'activo',
    ];

    protected $casts = [
        'incluye_clases' => 'boolean',
        'activo'         => 'boolean',
        'precio'         => 'decimal:2',
    ];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function membresias(): HasMany
    {
        return $this->hasMany(Membresia::class, 'plan_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
