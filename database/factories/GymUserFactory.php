<?php

namespace Database\Factories;

use App\Models\Empresa;
use App\Models\Gimnasio;
use App\Models\GymUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<GymUser>
 */
class GymUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gimnasio_id'          => Gimnasio::factory(),
            'nombre'               => fake()->firstName(),
            'apellido'             => fake()->lastName(),
            'email'                => fake()->unique()->safeEmail(),
            'password'             => Hash::make('password'),
            'rol'                  => 'admin',
            'activo'               => true,
            'must_change_password' => false,
        ];
    }

    public function withTempPassword(): static
    {
        return $this->state(['must_change_password' => true]);
    }
}
