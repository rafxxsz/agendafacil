@extends('layouts.app')

@section('title', 'Entrar — AgendaFácil')

@section('content')
    <div class="auth-wrap">
        <h1 class="page-title">Entrar</h1>
        <p class="page-subtitle">Acesse sua conta para agendar serviços.</p>

        <div class="card">
            <form method="POST" action="{{ route('login') }}" class="stack">
                @csrf

                <div class="form-group">
                    <label class="label" for="email">E-mail</label>
                    <input class="input" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="label" for="password">Senha</label>
                    <input class="input" type="password" id="password" name="password" required>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <label class="row" style="font-size:.9rem">
                    <input type="checkbox" name="remember"> Manter conectado
                </label>

                <button type="submit" class="btn btn--primary">Entrar</button>
            </form>
        </div>

        <p class="muted" style="margin-top:1rem">
            Ainda não tem conta? <a href="{{ route('register') }}">Criar conta</a>
        </p>
    </div>
@endsection
