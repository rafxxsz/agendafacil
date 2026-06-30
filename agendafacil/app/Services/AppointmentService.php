<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Professional;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AppointmentService
{
    /**
     * Calcula os horários livres de um profissional para um serviço numa data.
     * Gera slots a partir das janelas de disponibilidade (Availability) do dia
     * da semana, com passo igual à duração do serviço, e remove os que colidem
     * com agendamentos já existentes ou que estão no passado.
     *
     * @return Collection<int, array{start: Carbon, end: Carbon, label: string}>
     */
    public function availableSlots(Professional $professional, Service $service, Carbon $date): Collection
    {
        $weekday = $date->dayOfWeek;
        $duration = $service->duration_minutes;

        $windows = $professional->availabilities()
            ->where('weekday', $weekday)
            ->orderBy('start_time')
            ->get();

        $existing = $professional->appointments()
            ->where('status', '!=', 'cancelado')
            ->whereDate('start_at', $date->toDateString())
            ->get(['start_at', 'end_at']);

        $slots = collect();

        foreach ($windows as $window) {
            $cursor = $this->combine($date, $window->start_time);
            $windowEnd = $this->combine($date, $window->end_time);

            while ($cursor->copy()->addMinutes($duration)->lte($windowEnd)) {
                $slotStart = $cursor->copy();
                $slotEnd = $cursor->copy()->addMinutes($duration);

                $isPast = $slotStart->isPast();

                $collides = $existing->contains(function ($appointment) use ($slotStart, $slotEnd) {
                    return $appointment->start_at < $slotEnd && $appointment->end_at > $slotStart;
                });

                if (! $isPast && ! $collides) {
                    $slots->push([
                        'start' => $slotStart,
                        'end' => $slotEnd,
                        'label' => $slotStart->format('H:i'),
                    ]);
                }

                $cursor->addMinutes($duration);
            }
        }

        return $slots;
    }

    /**
     * Cria um agendamento aplicando todas as regras de negócio.
     * Lança ValidationException quando alguma regra é violada.
     */
    public function book(int $userId, Service $service, Professional $professional, Carbon $start): Appointment
    {
        if ($start->isPast()) {
            throw ValidationException::withMessages([
                'start_at' => 'Não é possível agendar em um horário passado.',
            ]);
        }

        $end = $start->copy()->addMinutes($service->duration_minutes);

        $hasConflict = Appointment::query()
            ->conflicting($professional->id, $start, $end)
            ->exists();

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'start_at' => 'Este horário já está ocupado para o profissional escolhido.',
            ]);
        }

        return Appointment::create([
            'user_id' => $userId,
            'service_id' => $service->id,
            'professional_id' => $professional->id,
            'start_at' => $start,
            'end_at' => $end,
            'status' => 'agendado',
        ]);
    }

    private function combine(Carbon $date, string $time): Carbon
    {
        [$hour, $minute] = array_pad(explode(':', $time), 2, 0);

        return $date->copy()->setTime((int) $hour, (int) $minute, 0);
    }
}
