<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RutinaEjercicio extends Model
{
    protected $fillable = [
        'rutina_id', 'ejercicio_id', 'orden',
        'series', 'repeticiones', 'descanso_seg', 'observaciones',
    ];

    public function rutina(): BelongsTo
    {
        return $this->belongsTo(Rutina::class, 'rutina_id');
    }

    public function ejercicio(): BelongsTo
    {
        return $this->belongsTo(Ejercicio::class, 'ejercicio_id');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(EjercicioFeedback::class, 'rutina_ejercicio_id');
    }

    public function feedbacksPendientes(): HasMany
    {
        return $this->hasMany(EjercicioFeedback::class, 'rutina_ejercicio_id')
                    ->where('revisado', false);
    }
}
