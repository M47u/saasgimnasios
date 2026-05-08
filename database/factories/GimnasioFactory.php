<?php

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\Gimnasio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Gimnasio>
 */
class GimnasioFactory extends Factory
{
    public function definition(): array
    {
        $nombre = fake()->company() . ' Gym';

        return [
            'empresa_id' => Empresa::factory(),
            'nombre'     => $nombre,
            'slug'       => Str::slug($nombre) . '-' . fake()->unique()->randomNumber(4),
            'estado'     => 'activo',
        ];
    }
}
