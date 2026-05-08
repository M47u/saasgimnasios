<?php

namespace Tests\Feature\Gym;

use App\Models\GymUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GymPasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ────────────────────────────────────────────────────────────

    private function makeUser(array $overrides = []): GymUser
    {
        return GymUser::factory()->create(array_merge([
            'password' => Hash::make('OldPass1!'),
        ], $overrides));
    }

    // ── Login redirect ──────────────────────────────────────────────────────

    public function test_login_con_must_change_password_redirige_a_change_password(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $response = $this->post(route('gym.login.post'), [
            'email'    => $user->email,
            'password' => 'OldPass1!',
        ]);

        $response->assertRedirect(route('gym.password.change'));
    }

    public function test_login_sin_must_change_password_redirige_a_dashboard(): void
    {
        $user = $this->makeUser(['must_change_password' => false]);

        $response = $this->post(route('gym.login.post'), [
            'email'    => $user->email,
            'password' => 'OldPass1!',
        ]);

        $response->assertRedirect(route('gym.dashboard'));
    }

    // ── Bloqueo por middleware ──────────────────────────────────────────────

    public function test_no_puede_acceder_a_dashboard_si_no_cambio_contrasena(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $response = $this->actingAs($user, 'gym')
            ->get(route('gym.dashboard'));

        $response->assertRedirect(route('gym.password.change'));
    }

    public function test_puede_acceder_a_dashboard_despues_de_cambiar_contrasena(): void
    {
        $user = $this->makeUser(['must_change_password' => false]);

        $response = $this->actingAs($user, 'gym')
            ->get(route('gym.dashboard'));

        $response->assertOk();
    }

    public function test_puede_acceder_a_change_password_con_must_change_password_true(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $response = $this->actingAs($user, 'gym')
            ->get(route('gym.password.change'));

        $response->assertOk();
    }

    // ── Cambio exitoso ──────────────────────────────────────────────────────

    public function test_cambio_exitoso_actualiza_hash_y_desactiva_must_change_password(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $response = $this->actingAs($user, 'gym')
            ->put(route('gym.password.update'), [
                'current_password'      => 'OldPass1!',
                'password'              => 'NewPass99@',
                'password_confirmation' => 'NewPass99@',
            ]);

        $response->assertRedirect(route('gym.dashboard'));

        $user->refresh();
        $this->assertFalse($user->must_change_password);
        $this->assertTrue(Hash::check('NewPass99@', $user->password));
    }

    // ── Fallos de validación ────────────────────────────────────────────────

    public function test_falla_si_contrasena_actual_incorrecta(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $response = $this->actingAs($user, 'gym')
            ->put(route('gym.password.update'), [
                'current_password'      => 'WrongPass1!',
                'password'              => 'NewPass99@',
                'password_confirmation' => 'NewPass99@',
            ]);

        $response->assertSessionHasErrors('current_password');

        $user->refresh();
        $this->assertTrue($user->must_change_password);
        $this->assertTrue(Hash::check('OldPass1!', $user->password));
    }

    public function test_falla_si_nueva_contrasena_no_cumple_reglas(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $response = $this->actingAs($user, 'gym')
            ->put(route('gym.password.update'), [
                'current_password'      => 'OldPass1!',
                'password'              => 'simple',
                'password_confirmation' => 'simple',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_falla_si_confirmacion_no_coincide(): void
    {
        $user = $this->makeUser(['must_change_password' => true]);

        $response = $this->actingAs($user, 'gym')
            ->put(route('gym.password.update'), [
                'current_password'      => 'OldPass1!',
                'password'              => 'NewPass99@',
                'password_confirmation' => 'DifferentPass99@',
            ]);

        $response->assertSessionHasErrors('password');
    }

    // ── Acceso sin autenticar ───────────────────────────────────────────────

    public function test_redirige_a_login_si_no_autenticado(): void
    {
        $this->get(route('gym.password.change'))
            ->assertRedirect(route('gym.login'));

        $this->put(route('gym.password.update'), [])
            ->assertRedirect(route('gym.login'));
    }
}
