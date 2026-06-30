@extends('layouts.app')

@section('title', $service->name . ' — AgendaFácil')

@section('content')
    <a href="{{ route('services.index') }}" class="muted">← Voltar aos serviços</a>

    <h1 class="page-title" style="margin-top:1rem">{{ $service->name }}</h1>
    <p class="page-subtitle">
        {{ $service->description }} · {{ $service->duration_minutes }} min ·
        <span class="price">R$ {{ number_format($service->price, 2, ',', '.') }}</span>
    </p>

    <div class="card">
        <h3 style="font-family:var(--font-display);margin-bottom:1rem">Escolha profissional e data</h3>

        <form method="GET" action="{{ route('appointments.slots') }}" class="stack">
            <input type="hidden" name="service_id" value="{{ $service->id }}">

            <div class="row">
                <div class="form-group" style="flex:1;min-width:220px">
                    <label class="label" for="professional_id">Profissional</label>
                    <select class="select" id="professional_id" name="professional_id" required>
                        @foreach ($professionals as $professional)
                            <option value="{{ $professional->id }}">{{ $professional->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="flex:1;min-width:220px">
                    <label class="label" for="date">Data</label>
                    <input class="input" type="date" id="date" name="date"
                           min="{{ now()->toDateString() }}"
                           value="{{ now()->toDateString() }}" required>
                </div>
            </div>

            <button type="submit" class="btn btn--primary">Ver horários livres</button>
        </form>
    </div>
@endsection
