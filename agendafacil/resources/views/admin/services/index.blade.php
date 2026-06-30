@extends('layouts.app')

@section('title', 'Gerir serviços — Admin')

@section('content')
    <div class="row">
        <div>
            <h1 class="page-title">Gerir serviços</h1>
            <p class="page-subtitle" style="margin:0">Crie, edite e remova os serviços oferecidos.</p>
        </div>
        <div class="spacer"></div>
        <a href="{{ route('admin.services.create') }}" class="btn btn--primary">Novo serviço</a>
    </div>

    <div class="card" style="margin-top:1.5rem">
        @if ($services->isEmpty())
            <div class="empty-state">
                Nenhum serviço cadastrado.<br>
                <a href="{{ route('admin.services.create') }}">Criar o primeiro serviço</a>
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Duração</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->duration_minutes }} min</td>
                            <td>R$ {{ number_format($service->price, 2, ',', '.') }}</td>
                            <td>
                                @if ($service->active)
                                    <span class="badge badge--concluido">Ativo</span>
                                @else
                                    <span class="badge badge--cancelado">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <div class="row">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn--ghost btn--sm">Editar</a>
                                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                                          onsubmit="return confirm('Remover este serviço?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--danger btn--sm">Remover</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">{{ $services->links() }}</div>
        @endif
    </div>
@endsection
