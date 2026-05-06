<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasGimnasioScope;

class PlanNutricional extends Model
{
    use HasGimnasioScope;

    protected $fillable = [
        'gimnasio_id', 'socio_id', 'generado_por', 'editado_por',
        'titulo', 'observaciones', 'calorias_totales', 'proteinas_g',
        'carbohidratos_g', 'grasas_g', 'agua_diaria_ml',
        'prompt_contexto', 'estado', 'snapshot',
        'generado_at', 'activado_at',
    ];

    protected $casts = [
        'prompt_contexto' => 'array',
        'snapshot'        => 'array',
        'generado_at'     => 'datetime',
        'activado_at'     => 'datetime',
    ];

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_id');
    }

    public function gimnasio(): BelongsTo
    {
        return $this->belongsTo(Gimnasio::class, 'gimnasio_id');
    }

    public function generadoPor(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'generado_por');
    }

    public function editadoPor(): BelongsTo
    {
        return $this->belongsTo(GymUser::class, 'editado_por');
    }

    public function comidas(): HasMany
    {
        return $this->hasMany(Comida::class, 'plan_id')->orderBy('orden');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(PlanNutricionalComentario::class, 'plan_id');
    }

    public function comentariosPendientes(): HasMany
    {
        return $this->hasMany(PlanNutricionalComentario::class, 'plan_id')
                    ->where('revisado', false);
    }

    public function activar(GymUser $user): void
    {
        static::where('socio_id', $this->socio_id)
              ->where('estado', 'activo')
              ->where('id', '!=', $this->id)
              ->update(['estado' => 'archivado']);

        $this->update([
            'estado'       => 'activo',
            'editado_por'  => $user->id,
            'activado_at'  => now(),
            'snapshot'     => $this->armarSnapshot(),
        ]);
    }

    public static function contextoParaIa(Socio $socio): array
    {
        $medida = $socio->ultimaMedida;

        return [
            'nombre'             => $socio->nombre,
            'edad'               => $socio->edad,
            'peso_kg'            => $medida?->peso_kg,
            'altura_cm'          => $medida?->altura_cm,
            'imc'                => $medida?->imc,
            'objetivo'           => $socio->objetivo,
            'nivel'              => $socio->nivel,
            'frecuencia_semanal' => $socio->frecuencia_semanal,
            'restricciones'      => $socio->restricciones_alimentarias ?? 'ninguna',
        ];
    }

    private function armarSnapshot(): array
    {
        return [
            'titulo'           => $this->titulo,
            'calorias_totales' => $this->calorias_totales,
            'proteinas_g'      => $this->proteinas_g,
            'carbohidratos_g'  => $this->carbohidratos_g,
            'grasas_g'         => $this->grasas_g,
            'agua_diaria_ml'   => $this->agua_diaria_ml,
            'comidas'          => $this->comidas->map(fn ($c) => [
                'tipo'      => $c->tipo,
                'nombre'    => $c->nombre,
                'calorias'  => $c->calorias,
                'alimentos' => $c->alimentos->map(fn ($a) => [
                    'nombre'   => $a->nombre,
                    'cantidad' => $a->cantidad,
                    'unidad'   => $a->unidad,
                ])->toArray(),
            ])->toArray(),
        ];
    }

    public function scopeActivo($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeBorradores($query)
    {
        return $query->where('estado', 'borrador');
    }
}
