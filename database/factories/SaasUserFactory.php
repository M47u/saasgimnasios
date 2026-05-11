<?php

namespace Database\Factories;

use App\Models\SaasUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<SaasUser>
 */
class SaasUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre'  => fake()->name(),
            'email'   => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'rol'     => 'super_admin',
            'activo'  => true,
        ];
    }
}
