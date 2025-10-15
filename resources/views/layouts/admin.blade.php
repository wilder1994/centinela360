<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Centinela360 | Panel Administrativo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-slate-900 to-blue-900 text-white flex flex-col">
        <div class="px-6 py-5 border-b border-slate-700">
            <h1 class="text-2xl font-bold text-cyan-400">Centinela360</h1>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800' : '' }}">📊 Dashboard</a>
            <a href="{{ route('admin.companies.index') }}" class="block py-2.5 px-4 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('admin.companies.*') ? 'bg-blue-800' : '' }}">🏢 Empresas</a>
            <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-800' : '' }}">👥 Usuarios</a>
            <a href="{{ route('admin.reports.index') }}" class="block py-2.5 px-4 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('admin.reports.*') ? 'bg-blue-800' : '' }}">📑 Reportes</a>
            <a href="{{ route('admin.settings') }}" class="block py-2.5 px-4 rounded-lg hover:bg-blue-800 transition {{ request()->routeIs('admin.settings') ? 'bg-blue-800' : '' }}">⚙️ Configuración</a>
        </nav>

        <div class="px-6 py-4 border-t border-slate-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 bg-blue-700 hover:bg-blue-600 rounded-lg text-sm font-semibold">Cerrar sesión</button>
            </form>
        </div>
    </aside>

    <!-- Contenedor principal -->
    <div class="flex-1 flex flex-col">

        <!-- Header -->
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-700">@yield('title', 'Panel de Control')</h2>
            <span class="text-sm text-gray-500">Bienvenido, {{ Auth::user()->name }}</span>
        </header>

        <!-- Contenido -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 text-center py-3 text-sm text-gray-500">
            © {{ date('Y') }} Centinela360 — Todos los derechos reservados.
        </footer>
    </div>

</body>
</html>
