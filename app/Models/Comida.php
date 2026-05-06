<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comida extends Model
{
    protected $fillable = [
        'plan_id', 'tipo', 'orden', 'nombre', 'descripcion',
        'calorias', 'proteinas_g', 'carbohidratos_g', 'grasas_g',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PlanNutricional::class, 'plan_id');
    }

    public function alimentos(): HasMany
    {
        return $this->hasMany(Alimento::class, 'comida_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(PlanNutricionalComentario::class, 'comida_id');
    }
}
