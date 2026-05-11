<?php

namespace Tests\Feature\Saas;

use App\Models\Gimnasio;
use App\Models\SaasUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GimnasioEliminacionTest extends TestCase
{
    use RefreshDatabase;

    private function saasUser(): SaasUser
    {
        return SaasUser::factory()->create();
    }

    private function gimnasio(string $estado): Gimnasio
    {
        return Gimnasio::factory()->create(['estado' => $estado]);
    }

    // ── Listado principal excluye cancelados ────────────────────────────────

    public function test_index_no_muestra_gimnasios_cancelados(): void
    {
        $activo    = $this->gimnasio('activo');
        $cancelado = $this->gimnasio('cancelado');

        $this->actingAs($this->saasUser(), 'saas')
            ->get(route('saas.gimnasios.index'))
            ->assertSee($activo->nombre)
            ->assertDontSee($cancelado->nombre);
    }

    // ── Vista de eliminados ─────────────────────────────────────────────────

    public function test_vista_eliminados_muestra_solo_cancelados(): void
    {
        $activo    = $this->gimnasio('activo');
        $cancelado = $this->gimnasio('cancelado');

        $this->actingAs($this->saasUser(), 'saas')
            ->get(route('saas.gimnasios.eliminados'))
            ->assertOk()
            ->assertSee($cancelado->nombre)
            ->assertDontSee($activo->nombre);
    }

    // ── Cancelar ────────────────────────────────────────────────────────────

    public function test_no_se_puede_cancelar_un_gimnasio_activo(): void
    {
        $gimnasio = $this->gimnasio('activo');

        $this->actingAs($this->saasUser(), 'saas')
            ->post(route('saas.gimnasios.cancelar', $gimnasio->id))
            ->assertRedirect();

        $this->assertDatabaseHas('gimnasios', [
            'id'     => $gimnasio->id,
            'estado' => 'activo',
        ]);
    }

    public function test_no_se_puede_cancelar_un_gimnasio_en_trial(): void
    {
        $gimnasio = $this->gimnasio('trial');

        $this->actingAs($this->saasUser(), 'saas')
            ->post(route('saas.gimnasios.cancelar', $gimnasio->id))
            ->assertRedirect();

        $this->assertDatabaseHas('gimnasios', [
            'id'     => $gimnasio->id,
            'estado' => 'trial',
        ]);
    }

    public function test_un_gimnasio_suspendido_puede_cancelarse(): void
    {
        $gimnasio = $this->gimnasio('suspendido');

        $this->actingAs($this->saasUser(), 'saas')
            ->post(route('saas.gimnasios.cancelar', $gimnasio->id))
            ->assertRedirect(route('saas.gimnasios.index'));

        $this->assertDatabaseHas('gimnasios', [
            'id'     => $gimnasio->id,
            'estado' => 'cancelado',
        ]);
    }

    // ── Restaurar ───────────────────────────────────────────────────────────

    public function test_restaurar_vuelve_a_estado_suspendido(): void
    {
        $gimnasio = $this->gimnasio('cancelado');

        $this->actingAs($this->saasUser(), 'saas')
            ->post(route('saas.gimnasios.restaurar', $gimnasio->id))
            ->assertRedirect(route('saas.gimnasios.show', $gimnasio->id));

        $this->assertDatabaseHas('gimnasios', [
            'id'     => $gimnasio->id,
            'estado' => 'suspendido',
        ]);
    }

    public function test_restaurar_un_operativo_devuelve_404(): void
    {
        $gimnasio = $this->gimnasio('activo');

        $this->actingAs($this->saasUser(), 'saas')
            ->post(route('saas.gimnasios.restaurar', $gimnasio->id))
            ->assertNotFound();
    }

    // ── Acceso sin autenticar ───────────────────────────────────────────────

    public function test_redirige_a_login_si_no_autenticado(): void
    {
        $gimnasio = $this->gimnasio('suspendido');

        $this->get(route('saas.gimnasios.eliminados'))->assertRedirect(route('saas.login'));
        $this->post(route('saas.gimnasios.cancelar', $gimnasio->id))->assertRedirect(route('saas.login'));
        $this->post(route('saas.gimnasios.restaurar', $gimnasio->id))->assertRedirect(route('saas.login'));
    }
}
