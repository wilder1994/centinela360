<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ auth()->user()->company->name ?? 'Panel Empresa' }} | Centinela360</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire styles --}}
    @livewireStyles

    @php
        $company = auth()->user()->company;
        $primary = $company->color_primary ?? '#007bff';
        $secondary = $company->color_secondary ?? '#001f3f';
        $logo = $company->logo ? asset('storage/' . $company->logo) : asset('images/default-logo.png');
    @endphp

    <style>
        :root { --primary: {{ $primary }}; --secondary: {{ $secondary }}; }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1f2937; }

        .sidebar { width: 270px; background: linear-gradient(160deg, var(--secondary), #000000); color: white; height: 100vh; position: fixed; left: 0; top: 0; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2); transition: all 0.3s ease; }
        .sidebar .brand { display: flex; align-items: center; justify-content: center; padding: 1rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar .brand img { max-height: 55px; filter: drop-shadow(0 0 3px rgba(255,255,255,0.3)); }
        .sidebar nav { flex-grow: 1; padding: 1.5rem 0; }
        .sidebar nav a { display: flex; align-items: center; gap: 0.85rem; padding: 1rem 1.8rem; color: #e0e8f0; font-weight: 500; font-size: 0.95rem; transition: all 0.25s ease; text-decoration: none; }
        .sidebar nav a:hover, .sidebar nav a.active { background: rgba(255,255,255,0.12); color: #ffffff; border-left: 4px solid var(--primary); }

        .sidebar .footer { padding: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1); font-size: 0.75rem; text-align: center; opacity: 0.7; }
        .main { margin-left: 270px; min-height: 100vh; display: flex; flex-direction: column; transition: all 0.3s ease; }
        .topbar { background: white; border-bottom: 1px solid #e5e7eb; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 10; box-shadow: 0 1px 4px rgba(0,0,0,0.04); }
        .content { padding: 2rem; background-color: #f8fafc; flex: 1; }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ $logo }}" alt="Logo {{ $company->name }}">
        </div>

        {{-- Iconos: definidos en resources/views/components/icons y consumidos con <x-icon name="..."> (los contenedores icon-* evitan que --primary/--secondary alteren el color del SVG) --}}
        <nav>
            <a href="{{ route('company.dashboard') }}" class="{{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                <span class="icon-ghost icon-safe"><x-icon name="dashboard" /></span>
                Panel de control
            </a>

            <a href="{{ route('company.programming.index') }}" class="{{ request()->routeIs('company.programming.*') ? 'active' : '' }}">
                <span class="icon-ghost icon-safe"><x-icon name="calendar" /></span>
                Programacion
            </a>

            <a href="{{ route('company.memorandums.index') }}"
               class="mt-2 {{ request()->routeIs('company.memorandums.*') ? 'active' : '' }}">
                <span class="icon-ghost icon-safe"><x-icon name="memo" /></span>
                <span>Memorandos</span>
            </a>

            <a href="#">
                <span class="icon-ghost icon-safe"><x-icon name="shield" /></span>
                Supervisión
            </a>

            <a href="{{ route('company.employees.index') }}"
               class="mt-2 {{ request()->routeIs('company.employees.*') ? 'active' : '' }}">
                <span class="icon-ghost icon-safe"><x-icon name="employees" /></span>
                Empleados
            </a>

            <a href="{{ route('company.users.index') }}"
               class="mt-2 {{ request()->routeIs('company.users.*') ? 'active' : '' }}">
                <span class="icon-ghost icon-safe"><x-icon name="users" /></span>
                Usuarios
            </a>

            <a href="{{ route('company.clients.index') }}"
               class="mt-2 {{ request()->routeIs('company.clients.*') ? 'active' : '' }}">
                <span class="icon-ghost icon-safe"><x-icon name="clients" /></span>
                Clientes
            </a>

            <a href="#">
                <span class="icon-ghost icon-safe"><x-icon name="stats" /></span>
                Estadísticas
            </a>

            <a href="#">
                <span class="icon-ghost icon-safe"><x-icon name="bell" /></span>
                Alertas
            </a>

            <a href="#">
                <span class="icon-ghost icon-safe"><x-icon name="settings" /></span>
                Configuración
            </a>
        </nav>

        <div class="footer">© {{ date('Y') }} {{ $company->name ?? 'Empresa' }}</div>
    </aside>

    <main class="main">
        <div class="topbar">
            <h1>{{ $company->name ?? 'Panel Empresa' }}</h1>
            <div class="user">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 0)) }}</div>
                <span>{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}"> @csrf <button class="hover:underline">Cerrar sesión</button></form>
            </div>
        </div>

        <div class="content">
            {{-- Contenido Livewire --}}
            {{ $slot ?? '' }}

            {{-- Contenido Blade clásico --}}
            @yield('content')
        </div>
    </main>

    {{-- Livewire scripts --}}
    @livewireScripts
    @livewireScriptConfig

    @stack('scripts')
</body>
</html>
