<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empresa extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'razon_social', 'email', 'telefono', 'pais'];

    public function gimnasios(): HasMany
    {
        return $this->hasMany(Gimnasio::class, 'empresa_id');
    }

    public function suscripciones(): HasMany
    {
        return $this->hasMany(Suscripcion::class, 'empresa_id');
    }

    public function suscripcionActiva(): HasOne
    {
        return $this->hasOne(Suscripcion::class, 'empresa_id')
                    ->whereIn('estado', ['activa', 'trial'])
                    ->latestOfMany();
    }

    public function plan(): ?SaasPlan
    {
        return $this->suscripcionActiva?->plan;
    }

    public function totalSocios(): int
    {
        return $this->gimnasios()->withCount('socios')->get()->sum('socios_count');
    }
}
