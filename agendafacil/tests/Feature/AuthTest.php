<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_se_registra(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Novo Cliente',
            'email' => 'novo@teste.com',
            'phone' => '(11) 99999-9999',
            'password' => 'senha12345',
            'password_confirmation' => 'senha12345',
        ]);

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('users', ['email' => 'novo@teste.com', 'role' => 'cliente']);
        $this->assertAuthenticated();
    }

    public function test_usuario_faz_login(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_com_senha_errada_falha(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'errada',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_usuario_faz_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
