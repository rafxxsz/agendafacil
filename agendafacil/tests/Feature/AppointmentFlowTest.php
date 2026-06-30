<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Professional;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_cria_agendamento(): void
    {
        $user = User::factory()->create();
        $professional = Professional::factory()->create();
        $service = Service::factory()->create(['duration_minutes' => 30]);

        $start = Carbon::tomorrow()->setTime(10, 0);

        $response = $this->actingAs($user)->post(route('appointments.store'), [
            'service_id' => $service->id,
            'professional_id' => $professional->id,
            'start_at' => $start->toDateTimeString(),
        ]);

        $response->assertRedirect(route('appointments.index'));
        $this->assertDatabaseHas('appointments', [
            'user_id' => $user->id,
            'service_id' => $service->id,
            'status' => 'agendado',
        ]);
    }

    public function test_bloqueia_agendamento_em_horario_ocupado(): void
    {
        $user = User::factory()->create();
        $professional = Professional::factory()->create();
        $service = Service::factory()->create(['duration_minutes' => 60]);

        $start = Carbon::tomorrow()->setTime(10, 0);

        Appointment::factory()->create([
            'professional_id' => $professional->id,
            'service_id' => $service->id,
            'start_at' => $start,
            'end_at' => $start->copy()->addHour(),
        ]);

        $response = $this->actingAs($user)->post(route('appointments.store'), [
            'service_id' => $service->id,
            'professional_id' => $professional->id,
            'start_at' => $start->copy()->addMinutes(30)->toDateTimeString(),
        ]);

        $response->assertSessionHasErrors('start_at');
        $this->assertDatabaseCount('appointments', 1);
    }

    public function test_usuario_ve_apenas_os_proprios_agendamentos(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $professional = Professional::factory()->create();
        $service = Service::factory()->create();

        Appointment::factory()->create([
            'user_id' => $bob->id,
            'service_id' => $service->id,
            'professional_id' => $professional->id,
        ]);

        $response = $this->actingAs($alice)->get(route('appointments.index'));

        $response->assertOk();
        // Alice não tem agendamentos -> nenhum registro de Bob aparece
        $this->assertCount(0, $response->viewData('appointments'));
    }

    public function test_usuario_nao_cancela_agendamento_de_outro(): void
    {
        $alice = User::factory()->create();
        $bob = User::factory()->create();
        $professional = Professional::factory()->create();
        $service = Service::factory()->create();

        $appointment = Appointment::factory()->create([
            'user_id' => $bob->id,
            'service_id' => $service->id,
            'professional_id' => $professional->id,
        ]);

        $response = $this->actingAs($alice)
            ->patch(route('appointments.cancel', $appointment));

        $response->assertForbidden();
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'agendado',
        ]);
    }

    public function test_usuario_cancela_proprio_agendamento_futuro(): void
    {
        $user = User::factory()->create();
        $professional = Professional::factory()->create();
        $service = Service::factory()->create();

        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'professional_id' => $professional->id,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('appointments.cancel', $appointment));

        $response->assertRedirect();
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'cancelado',
        ]);
    }

    public function test_visitante_nao_acessa_agendamentos(): void
    {
        $response = $this->get(route('appointments.index'));

        $response->assertRedirect(route('login'));
    }
}
