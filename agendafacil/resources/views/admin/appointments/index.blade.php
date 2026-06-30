@extends('layouts.app')

@section('title', 'Agenda — Admin')

@section('content')
    <h1 class="page-title">Agenda administrativa</h1>
    <p class="page-subtitle">Todos os agendamentos do sistema.</p>

    <div class="card">
        <form method="GET" action="{{ route('admin.appointments.index') }}" class="row" style="margin-bottom:1.5rem">
            <label class="label" for="status" style="margin:0">Filtrar:</label>
            <select class="select" id="status" name="status" style="max-width:200px" onchange="this.form.submit()">
                <option value="">Todos os status</option>
                <option value="agendado" @selected(request('status') === 'agendado')>Agendado</option>
                <option value="cancelado" @selected(request('status') === 'cancelado')>Cancelado</option>
                <option value="concluido" @selected(request('status') === 'concluido')>Concluído</option>
            </select>
        </form>

        @if ($appointments->isEmpty())
            <div class="empty-state">Nenhum agendamento encontrado.</div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Serviço</th>
                        <th>Profissional</th>
                        <th>Data e hora</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->user->name }}</td>
                            <td>{{ $appointment->service->name }}</td>
                            <td>{{ $appointment->professional->name }}</td>
                            <td>{{ $appointment->start_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge badge--{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">{{ $appointments->links() }}</div>
        @endif
    </div>
@endsection
