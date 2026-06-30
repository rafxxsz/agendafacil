@extends('layouts.app')

@section('title', 'Editar serviço — Admin')

@section('content')
    <a href="{{ route('admin.services.index') }}" class="muted">← Voltar</a>
    <h1 class="page-title" style="margin-top:1rem">Editar serviço</h1>

    <div class="card">
        <form method="POST" action="{{ route('admin.services.update', $service) }}">
            @csrf
            @method('PUT')
            @include('admin.services._form')
            <button type="submit" class="btn btn--primary">Salvar alterações</button>
        </form>
    </div>
@endsection
