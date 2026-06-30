@extends('layouts.app')

@section('title', 'Meus agendamentos — AgendaFácil')

@section('content')
    <h1 class="page-title">Meus agendamentos</h1>
    <p class="page-subtitle">Consulte e cancele seus agendamentos futuros.</p>

    <div class="card">
        @if ($appointments->isEmpty())
            <div class="empty-state">
                Você ainda não tem agendamentos.<br>
                <a href="{{ route('services.index') }}">Agendar um serviço</a>
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Serviço</th>
                        <th>Profissional</th>
                        <th>Data e hora</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->service->name }}</td>
                            <td>{{ $appointment->professional->name }}</td>
                            <td>{{ $appointment->start_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge badge--{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span></td>
                            <td>
                                @if ($appointment->isCancellable())
                                    <form method="POST" action="{{ route('appointments.cancel', $appointment) }}"
                                          onsubmit="return confirm('Cancelar este agendamento?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn--danger btn--sm">Cancelar</button>
                                    </form>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">{{ $appointments->links() }}</div>
        @endif
    </div>
@endsection
