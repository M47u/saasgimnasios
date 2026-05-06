<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaasPlan extends Model
{
    protected $fillable = [
        'nombre', 'slug', 'precio_mensual', 'precio_anual',
        'max_socios', 'max_usuarios', 'max_sucursales',
        'limite_ia_mensual', 'modulos_habilitados', 'activo',
    ];

    protected $casts = [
        'modulos_habilitados' => 'array',
        'activo'              => 'boolean',
        'precio_mensual'      => 'decimal:2',
        'precio_anual'        => 'decimal:2',
    ];

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class, 'plan_id');
    }

    public function tieneModulo(string $slug): bool
    {
        return in_array($slug, $this->modulos_habilitados ?? []);
    }

    public function esIlimitado(string $campo): bool
    {
        return $this->{$campo} === 0;
    }
}
