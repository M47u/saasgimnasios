<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasGimnasioScope;

class Ejercicio extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'gimnasio_id', 'nombre', 'descripcion',
        'grupo_muscular', 'video_url', 'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function rutinaEjercicios(): HasMany
    {
        return $this->hasMany(RutinaEjercicio::class, 'ejercicio_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorGrupo($query, string $grupo)
    {
        return $query->where('grupo_muscular', $grupo);
    }

    public function tieneVideo(): bool
    {
        return ! empty($this->video_url);
    }
}
