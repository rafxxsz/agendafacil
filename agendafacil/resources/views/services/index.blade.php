@extends('layouts.app')

@section('title', 'Serviços — AgendaFácil')

@section('content')
    <h1 class="page-title">Serviços disponíveis</h1>
    <p class="page-subtitle">Escolha um serviço para ver os horários livres e agendar.</p>

    @if ($services->isEmpty())
        <div class="empty-state">Nenhum serviço disponível no momento.</div>
    @else
        <div class="grid grid--3">
            @foreach ($services as $service)
                <div class="card service-card">
                    <h3>{{ $service->name }}</h3>
                    <p class="muted">{{ $service->description }}</p>
                    <div class="service-card__meta">
                        <span>{{ $service->duration_minutes }} min</span>
                        <span class="price">R$ {{ number_format($service->price, 2, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('services.show', $service) }}" class="btn btn--primary btn--sm" style="margin-top:1rem;width:100%">
                        Agendar
                    </a>
                </div>
            @endforeach
        </div>

        <div class="pagination">{{ $services->links() }}</div>
    @endif
@endsection
