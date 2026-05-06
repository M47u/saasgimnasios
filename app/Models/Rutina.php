<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasGimnasioScope;

class Rutina extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'gimnasio_id', 'entrenador_id', 'nombre',
        'descripcion', 'objetivo', 'nivel', 'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function entrenador(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'entrenador_id');
    }

    public function rutinaEjercicios(): HasMany
    {
        return $this->hasMany(RutinaEjercicio::class, 'rutina_id')
                    ->orderBy('orden');
    }

    public function ejercicios(): BelongsToMany
    {
        return $this->belongsToMany(Ejercicio::class, 'rutina_ejercicios')
                    ->withPivot(['orden', 'series', 'repeticiones', 'descanso_seg', 'observaciones'])
                    ->orderByPivot('orden');
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(SocioRutina::class, 'rutina_id');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}
