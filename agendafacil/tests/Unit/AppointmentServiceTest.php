<?php

namespace Tests\Unit;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Professional;
use App\Models\Service;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AppointmentServiceTest extends TestCase
{
    use RefreshDatabase;

    private AppointmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AppointmentService();
    }

    public function test_calcula_horario_final_pela_duracao_do_servico(): void
    {
        $user = User::factory()->create();
        $professional = Professional::factory()->create();
        $service = Service::factory()->create(['duration_minutes' => 45]);

        $start = Carbon::tomorrow()->setTime(10, 0);

        $appointment = $this->service->book($user->id, $service, $professional, $start);

        $this->assertEquals('10:45', $appointment->end_at->format('H:i'));
    }

    public function test_impede_agendamento_em_horario_passado(): void
    {
        $user = User::factory()->create();
        $professional = Professional::factory()->create();
        $service = Service::factory()->create();

        $start = Carbon::yesterday()->setTime(10, 0);

        $this->expectException(ValidationException::class);

        $this->service->book($user->id, $service, $professional, $start);
    }

    public function test_impede_conflito_de_horario_para_o_mesmo_profissional(): void
    {
        $user = User::factory()->create();
        $professional = Professional::factory()->create();
        $service = Service::factory()->create(['duration_minutes' => 60]);

        $start = Carbon::tomorrow()->setTime(10, 0);

        // Primeiro agendamento ocupa 10:00-11:00
        $this->service->book($user->id, $service, $professional, $start);

        // Segundo agendamento às 10:30 deve colidir
        $this->expectException(ValidationException::class);

        $this->service->book($user->id, $service, $professional, $start->copy()->addMinutes(30));
    }

    public function test_permite_mesmo_horario_para_profissionais_diferentes(): void
    {
        $user = User::factory()->create();
        $professionalA = Professional::factory()->create();
        $professionalB = Professional::factory()->create();
        $service = Service::factory()->create(['duration_minutes' => 60]);

        $start = Carbon::tomorrow()->setTime(10, 0);

        $this->service->book($user->id, $service, $professionalA, $start);
        $second = $this->service->book($user->id, $service, $professionalB, $start);

        $this->assertDatabaseCount('appointments', 2);
        $this->assertEquals('agendado', $second->status);
    }

    public function test_gera_apenas_slots_livres(): void
    {
        $professional = Professional::factory()->create();
        $service = Service::factory()->create(['duration_minutes' => 60]);

        // Próxima segunda-feira para garantir disponibilidade
        $date = Carbon::parse('next monday')->startOfDay();

        Availability::factory()->create([
            'professional_id' => $professional->id,
            'weekday' => $date->dayOfWeek,
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);

        // Ocupa o slot das 10:00-11:00
        Appointment::factory()->create([
            'professional_id' => $professional->id,
            'service_id' => $service->id,
            'start_at' => $date->copy()->setTime(10, 0),
            'end_at' => $date->copy()->setTime(11, 0),
        ]);

        $slots = $this->service->availableSlots($professional, $service, $date);
        $labels = $slots->pluck('label')->all();

        // 09:00 e 11:00 livres; 10:00 ocupado
        $this->assertContains('09:00', $labels);
        $this->assertContains('11:00', $labels);
        $this->assertNotContains('10:00', $labels);
    }
}
