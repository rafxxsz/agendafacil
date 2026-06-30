@extends('layouts.app')

@section('title', 'Horários — AgendaFácil')

@section('content')
    <a href="{{ route('services.show', $service) }}" class="muted">← Trocar profissional ou data</a>

    <h1 class="page-title" style="margin-top:1rem">Horários livres</h1>
    <p class="page-subtitle">
        {{ $service->name }} com {{ $professional->name }} —
        {{ $date->translatedFormat('d \d\e F \d\e Y') }}
    </p>

    <div class="card">
        @if ($slots->isEmpty())
            <div class="empty-state">
                Nenhum horário livre nesta data.<br>
                Tente outra data ou outro profissional.
            </div>
        @else
            <p class="muted">Selecione um horário para confirmar o agendamento:</p>
            <div class="slots">
                @foreach ($slots as $slot)
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        <input type="hidden" name="professional_id" value="{{ $professional->id }}">
                        <input type="hidden" name="start_at" value="{{ $slot['start']->toDateTimeString() }}">
                        <button type="submit" class="slot">{{ $slot['label'] }}</button>
                    </form>
                @endforeach
            </div>
        @endif

        @error('start_at') <p class="form-error">{{ $message }}</p> @enderror
    </div>
@endsection
