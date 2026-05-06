<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alimento extends Model
{
    protected $fillable = [
        'comida_id', 'nombre', 'cantidad', 'unidad', 'calorias',
    ];

    public function comida(): BelongsTo
    {
        return $this->belongsTo(Comida::class, 'comida_id');
    }
}
