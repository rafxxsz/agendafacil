<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AgendaFácil')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="app-header">
        <div class="app-header__inner">
            <a href="{{ route('services.index') }}" class="brand">
                <span class="brand__dot"></span> AgendaFácil
            </a>
            <nav class="nav">
                @auth
                    <a href="{{ route('services.index') }}">Serviços</a>
                    <a href="{{ route('appointments.index') }}">Meus agendamentos</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.appointments.index') }}">Agenda</a>
                        <a href="{{ route('admin.services.index') }}">Gerir serviços</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn--ghost btn--sm">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Entrar</a>
                    <a href="{{ route('register') }}" class="btn btn--primary btn--sm">Criar conta</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="main">
        @if (session('success'))
            <div class="alert alert--success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert--error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>
