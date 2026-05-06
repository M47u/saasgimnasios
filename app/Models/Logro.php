<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Logro extends Model
{
    protected $fillable = [
        'codigo', 'nombre', 'descripcion',
        'icono', 'criterio', 'valor_objetivo',
    ];

    public function socioLogros(): HasMany
    {
        return $this->hasMany(SocioLogro::class, 'logro_id');
    }
}
