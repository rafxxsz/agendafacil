<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cria_servico(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.services.store'), [
            'name' => 'Massagem Relaxante',
            'description' => 'Sessão de 50 minutos.',
            'duration_minutes' => 50,
            'price' => 200,
            'active' => 1,
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['name' => 'Massagem Relaxante']);
    }

    public function test_admin_edita_servico(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create(['price' => 100]);

        $response = $this->actingAs($admin)->put(route('admin.services.update', $service), [
            'name' => $service->name,
            'description' => $service->description,
            'duration_minutes' => $service->duration_minutes,
            'price' => 150,
            'active' => 1,
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['id' => $service->id, 'price' => 150]);
    }

    public function test_admin_remove_servico(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.services.destroy', $service));

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_cliente_nao_acessa_crud_de_servicos(): void
    {
        $cliente = User::factory()->create();

        $response = $this->actingAs($cliente)->get(route('admin.services.index'));

        $response->assertForbidden();
    }

    public function test_validacao_rejeita_preco_negativo(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.services.store'), [
            'name' => 'Serviço Inválido',
            'duration_minutes' => 30,
            'price' => -10,
            'active' => 1,
        ]);

        $response->assertSessionHasErrors('price');
        $this->assertDatabaseMissing('services', ['name' => 'Serviço Inválido']);
    }
}
