<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ auth()->user()->company->name ?? 'Panel Empresa' }} | Centinela360</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Estilos de Livewire --}}
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
        .sidebar .brand { display: flex; flex-direction: column; align-items: flex-start; justify-content: center; gap: 0.85rem; padding: 1.25rem 1rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar .brand-row { display: flex; align-items: center; gap: 0.75rem; width: 100%; justify-content: space-between; }
        .sidebar .brand-left { display: flex; align-items: center; gap: 0.75rem; }
        .sidebar .brand img { width: 56px; height: 56px; object-fit: contain; border-radius: 0; border: none; box-shadow: none; filter: none; background: transparent; }
        .sidebar .company-name { font-weight: 700; font-size: 1rem; line-height: 1.25; color: #f8fafc; }
        .sidebar .menu-wrapper { position: relative; }
        .sidebar .menu-icon { display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 10px; color: #f8fafc; background: rgba(255, 255, 255, 0.06); cursor: pointer; transition: background 0.2s ease, border-color 0.2s ease; }
        .sidebar .menu-icon:hover { background: rgba(255, 255, 255, 0.12); border-color: rgba(255, 255, 255, 0.35); }
        .sidebar .menu-dropdown { display: none; position: absolute; right: 0; top: 110%; min-width: 140px; background: rgba(0,0,0,0.9); border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; padding: 0.5rem; box-shadow: 0 8px 20px rgba(0,0,0,0.35); z-index: 20; }
        .sidebar .menu-dropdown form button { width: 100%; text-align: left; color: #f8fafc; padding: 0.5rem; border-radius: 6px; transition: background 0.15s ease; }
        .sidebar .menu-dropdown form button:hover { background: rgba(255,255,255,0.1); }
        .sidebar .menu-wrapper:hover .menu-dropdown,
        .sidebar .menu-wrapper:focus-within .menu-dropdown { display: block; }
        .sidebar .user-auth { width: 100%; color: #e0e8f0; font-size: 0.9rem; line-height: 1.4; }
        .sidebar .user-auth-content { display: flex; align-items: center; gap: 0.65rem; }
        .sidebar .user-avatar-img { width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: none; background: transparent; display: inline-block; }
        .sidebar .user-avatar-fallback { width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(255, 255, 255, 0.12); color: #f8fafc; font-weight: 700; border: none; }
        .sidebar .user-auth-names { display: flex; flex-direction: column; line-height: 1.2; }
        .sidebar nav { flex-grow: 1; padding: 1.5rem 0; overflow-y: auto; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.35) transparent; }
        .sidebar nav::-webkit-scrollbar { width: 6px; }
        .sidebar nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.25); border-radius: 999px; }
        .sidebar nav a { display: flex; align-items: center; gap: 0.85rem; padding: 1rem 1.8rem; color: #e0e8f0; font-weight: 500; font-size: 0.95rem; transition: all 0.25s ease; text-decoration: none; }
        .sidebar nav a:hover, .sidebar nav a.active { background: rgba(255,255,255,0.12); color: #ffffff; border-left: 4px solid var(--primary); }
        .sidebar .icon-ghost, .sidebar .icon-safe { background: none !important; padding: 0 !important; border: none !important; border-radius: 0 !important; box-shadow: none !important; }
        .sidebar .icon-ghost svg, .sidebar .icon-safe svg { width: 36px; height: 36px; }

        .sidebar .footer { padding: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1); font-size: 0.75rem; text-align: center; opacity: 0.7; }
        .main { margin-left: 270px; min-height: 100vh; display: flex; flex-direction: column; transition: all 0.3s ease; }
        .content { padding: 2rem; background-color: #f8fafc; flex: 1; }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-row">
                <div class="brand-left">
                    <img src="{{ $logo }}" alt="Logo {{ $company->name }}">
                    <div class="company-name">{{ $company->name ?? 'Empresa' }}</div>
                </div>
                <div class="menu-wrapper">
                    <button type="button" class="menu-icon" aria-label="Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="18" height="18">
                            <path d="M3 6.25C3 5.56 3.56 5 4.25 5h11.5c.69 0 1.25.56 1.25 1.25S16.44 7.5 15.75 7.5H4.25C3.56 7.5 3 6.94 3 6.25Zm0 3.75c0-.69.56-1.25 1.25-1.25h11.5c.69 0 1.25.56 1.25 1.25S16.44 11 15.75 11H4.25C3.56 11 3 10.44 3 9.75Zm0 3.5c0-.69.56-1.25 1.25-1.25h11.5c.69 0 1.25.56 1.25 1.25S16.44 14.5 15.75 14.5H4.25C3.56 14.5 3 13.94 3 13.25Z"/>
                        </svg>
                    </button>
                    <div class="menu-dropdown" role="menu">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" role="menuitem">Cerrar sesion</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="user-auth">
                @php
                    $user = Auth::user();
                    $userPhoto = $user->photo ?? null;
                    $userInitial = strtoupper(substr($user->name ?? 'U', 0, 1));
                @endphp
                <div class="user-auth-content">
                    @if ($userPhoto)
                        <img src="{{ asset('storage/' . $userPhoto) }}" alt="Avatar de {{ $user->name }}" class="user-avatar-img">
                    @else
                        <div class="user-avatar-fallback" aria-hidden="true">{{ $userInitial }}</div>
                    @endif
                    <div class="user-auth-names">
                        <div class="font-semibold">{{ $user->name }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Iconos definidos en resources/views/components/icons y usados con <x-icon name="..."> --}}
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
                Supervision
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
                Estadisticas
            </a>

            <a href="#">
                <span class="icon-ghost icon-safe"><x-icon name="bell" /></span>
                Alertas
            </a>

            <a href="#">
                <span class="icon-ghost icon-safe"><x-icon name="settings" /></span>
                Configuracion
            </a>
        </nav>

        <div class="footer">(c) {{ date('Y') }} {{ $company->name ?? 'Empresa' }}</div>
    </aside>

    <main class="main">
        <div class="content">
            {{-- Contenido Livewire --}}
            {{ $slot ?? '' }}

            {{-- Contenido Blade clasico --}}
            @yield('content')
        </div>
    </main>

    {{-- Scripts Livewire --}}
    @livewireScripts
    @livewireScriptConfig

    @stack('scripts')
</body>
</html>
