@extends('layouts.app')

@section('title', 'Novo serviço — Admin')

@section('content')
    <a href="{{ route('admin.services.index') }}" class="muted">← Voltar</a>
    <h1 class="page-title" style="margin-top:1rem">Novo serviço</h1>

    <div class="card">
        <form method="POST" action="{{ route('admin.services.store') }}">
            @csrf
            @include('admin.services._form')
            <button type="submit" class="btn btn--primary">Criar serviço</button>
        </form>
    </div>
@endsection
