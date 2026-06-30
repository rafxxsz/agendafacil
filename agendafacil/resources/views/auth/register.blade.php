@extends('layouts.app')

@section('title', 'Criar conta — AgendaFácil')

@section('content')
    <div class="auth-wrap">
        <h1 class="page-title">Criar conta</h1>
        <p class="page-subtitle">Cadastre-se para começar a agendar.</p>

        <div class="card">
            <form method="POST" action="{{ route('register') }}" class="stack">
                @csrf

                <div class="form-group">
                    <label class="label" for="name">Nome</label>
                    <input class="input" type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label" for="email">E-mail</label>
                    <input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label" for="phone">Telefone (opcional)</label>
                    <input class="input" type="text" id="phone" name="phone" value="{{ old('phone') }}">
                    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label" for="password">Senha</label>
                    <input class="input" type="password" id="password" name="password" required>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label" for="password_confirmation">Confirmar senha</label>
                    <input class="input" type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn--primary">Criar conta</button>
            </form>
        </div>

        <p class="muted" style="margin-top:1rem">
            Já tem conta? <a href="{{ route('login') }}">Entrar</a>
        </p>
    </div>
@endsection
