<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Professional;
use App\Models\Service;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller
{
    public function __construct(private readonly AppointmentService $appointments)
    {
    }

    /**
     * Retorna os horários livres para serviço + profissional + data.
     * Usado tanto na tela de escolha quanto via fetch (JSON).
     */
    public function slots(Request $request): View
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'professional_id' => ['required', 'exists:professionals,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $service = Service::findOrFail($data['service_id']);
        $professional = Professional::findOrFail($data['professional_id']);
        $date = Carbon::parse($data['date'])->startOfDay();

        $slots = $this->appointments->availableSlots($professional, $service, $date);

        return view('appointments.slots', compact('service', 'professional', 'date', 'slots'));
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $service = Service::findOrFail($request->service_id);
        $professional = Professional::findOrFail($request->professional_id);
        $start = Carbon::parse($request->start_at);

        $this->appointments->book(
            $request->user()->id,
            $service,
            $professional,
            $start
        );

        return redirect()->route('appointments.index')
            ->with('success', 'Agendamento realizado com sucesso!');
    }

    public function index(Request $request): View
    {
        $appointments = $request->user()->appointments()
            ->with(['service', 'professional'])
            ->orderByDesc('start_at')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        Gate::authorize('cancel', $appointment);

        $appointment->update(['status' => 'cancelado']);

        return back()->with('success', 'Agendamento cancelado.');
    }
}
