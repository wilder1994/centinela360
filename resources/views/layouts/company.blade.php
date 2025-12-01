<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ auth()->user()->company->name ?? 'Panel Empresa' }} | Centinela360</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- 🔹 Livewire styles --}}
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
        .sidebar nav a { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.8rem; color: #e0e8f0; font-weight: 500; font-size: 0.95rem; transition: all 0.25s ease; text-decoration: none; }
        .sidebar nav a:hover, .sidebar nav a.active { background: rgba(255,255,255,0.12); color: #ffffff; border-left: 4px solid var(--primary); }
        .sidebar nav svg { width: 20px; height: 20px; color: #ffffff; }

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

        <nav>
            <a href="{{ route('company.dashboard') }}" class="{{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                Panel de control
            </a>

            <a href="{{ route('company.programming.index') }}" class="{{ request()->routeIs('company.programming.*') ? 'active' : '' }}">
                Programación
            </a>

            <a href="{{ route('company.memorandums.index') }}"
               class="flex items-center gap-3 px-6 py-3 mt-2 {{ request()->routeIs('company.memorandums.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt w-5"></i>
                <span>Memorandos</span>
            </a>

            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M11.7 1.53a.75.75 0 0 1 .6 0l8.25 3.6a.75.75 0 0 1 .45.69v5.19c0 3.84-2.72 7.93-8.47 10.62a.75.75 0 0 1-.66 0C6.12 18.94 3.4 14.85 3.4 11.01V5.82a.75.75 0 0 1 .45-.69l8.25-3.6Z"/></svg>
                Supervisión
            </a>

            <a href="{{ route('company.employees.index') }}"
               class="flex items-center gap-3 px-6 py-3 mt-2 {{ request()->routeIs('company.employees.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16.5 6a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/>
                    <path d="M3.75 20.1a8.25 8.25 0 0 1 16.5 0v.65a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75v-.65Z"/>
                </svg>
                Empleados
            </a>

            <a href="{{ route('company.users.index') }}"
               class="flex items-center gap-3 px-6 py-3 mt-2 {{ request()->routeIs('company.users.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 6a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z"/><path d="M3.75 20.1a8.25 8.25 0 0 1 16.5 0v.65a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75v-.65Z"/></svg>
                Usuarios
            </a>

            <a href="{{ route('company.clients.index') }}"
               class="flex items-center gap-3 px-6 py-3 mt-2 {{ request()->routeIs('company.clients.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3.75 20.1a8.25 8.25 0 0 1 16.5 0v.65a.75.75 0 0 1-.75.75h-15a.75.75 0 0 1-.75-.75v-.65Z"/>
                    <path d="M12 2.25c-4.28 0-7.75 1.57-7.75 3.5v12.5c0 1.93 3.47 3.5 7.75 3.5s7.75-1.57 7.75-3.5V5.75c0-1.93-3.47-3.5-7.75-3.5Z"/>
                </svg>
                Clientes
            </a>

            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3.75A.75.75 0 0 1 3.75 3h16.5a.75.75 0 0 1 .75.75V12h-18V3.75Z"/><path d="M3 13.5h18v2.25a3 3 0 0 1-3 3h-12a3 3 0 0 1-3-3V13.5Z"/></svg>
                Estadísticas
            </a>

            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M10.34 21.25a1.5 1.5 0 0 0 3.32 0h-3.32Z"/><path d="M3 14.25c0-.966.784-1.75 1.75-1.75h.5a1 1 0 0 0 .95-.684 7.5 7.5 0 1 1 13.6 0 1 1 0 0 0 .95.684h.5A1.75 1.75 0 0 1 21 14.25v1a1 1 0 0 1-1 1h-1.5a1 1 0 0 0-.9.563l-.35.737a2.25 2.25 0 0 1-2.03 1.25h-6.44a2.25 2.25 0 0 1-2.03-1.25l-.35-.737a1 1 0 0 0-.9-.563H4a1 1 0 0 1-1-1v-1Z"/></svg>
                Alertas
            </a>

            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M11.25 2.25a.75.75 0 0 1 1.5 0v1.511a6.75 6.75 0 0 1 3.066 1.274l1.066-1.067a.75.75 0 1 1 1.06 1.06l-1.067 1.067A6.75 6.75 0 0 1 20.239 11.25h1.511a.75.75 0 0 1 0 1.5h-1.511a6.75 6.75 0 0 1-1.274 3.066l1.067 1.066a.75.75 0 1 1-1.06 1.06l-1.067-1.067A6.75 6.75 0 0 1 12.75 20.239v1.511a.75.75 0 0 1-1.5 0v-1.511a6.75 6.75 0 0 1-3.066-1.274l-1.066 1.067a.75.75 0 1 1-1.06-1.06l1.067-1.067A6.75 6.75 0 0 1 3.761 12.75H2.25a.75.75 0 0 1 0-1.5h1.511a6.75 6.75 0 0 1 1.274-3.066L3.968 7.117a.75.75 0 1 1 1.06-1.06l1.067 1.067A6.75 6.75 0 0 1 11.25 3.761V2.25Z"/></svg>
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
            {{-- Contenido de componentes Livewire de página (Board, etc.) --}}
            {{ $slot ?? '' }}

            {{-- Contenido de vistas Blade clásicas (index, show, etc.) --}}
            @yield('content')
        </div>
    </main>

    {{-- 🔹 Livewire scripts --}}
    @livewireScripts
    @livewireScriptConfig

    @stack('scripts')
</body>
</html>
