<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ auth()->user()->company->name ?? 'Panel Empresa' }} | Centinela360</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $company = auth()->user()->company;
        $primary = $company->color_primary ?? '#007bff';
        $secondary = $company->color_secondary ?? '#001f3f';
        $logo = $company->logo ? asset('storage/' . $company->logo) : asset('images/default-logo.png');
    @endphp

    <style>
        :root {
            --primary: {{ $primary }};
            --secondary: {{ $secondary }};
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1f2937;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 270px;
            background: linear-gradient(160deg, var(--secondary), #000000);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .brand img {
            max-height: 55px;
            filter: drop-shadow(0 0 3px rgba(255,255,255,0.3));
        }

        .sidebar nav {
            flex-grow: 1;
            padding: 1.5rem 0;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.8rem;
            color: #e0e8f0;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.25s ease;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background: rgba(255,255,255,0.12);
            color: #ffffff;
            border-left: 4px solid var(--primary);
        }

        .sidebar nav i {
            font-size: 1.2rem;
        }

        .sidebar .footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.75rem;
            text-align: center;
            opacity: 0.7;
        }

        /* === MAIN AREA === */
        .main {
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .topbar h1 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #374151;
        }

        .topbar .user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .topbar form button {
            font-size: 0.875rem;
            color: #ef4444;
            margin-left: 8px;
        }

        .content {
            padding: 2rem;
            background-color: #f8fafc;
            flex: 1;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }
        }
    </style>

    <script src="https://kit.fontawesome.com/a2e0e6a7f2.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ $logo }}" alt="Logo {{ $company->name }}">
        </div>
        <nav>
            <a href="{{ route('company.dashboard') }}" class="{{ request()->routeIs('company.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> Panel de control</a>
            <a href="#"><i class="fas fa-users"></i> Usuarios</a>
            <a href="#"><i class="fas fa-file-alt"></i> Informes</a>
            <a href="#"><i class="fas fa-bell"></i> Alertas</a>
            <a href="#"><i class="fas fa-cogs"></i> Configuración</a>
        </nav>
        <div class="footer">
            © {{ date('Y') }} {{ $company->name ?? 'Empresa' }}
        </div>
    </aside>

    <!-- Main -->
    <main class="main">
        <div class="topbar">
            <h1>{{ $company->name ?? 'Panel Empresa' }}</h1>
            <div class="user">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <span>{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="hover:underline">Cerrar sesión</button>
                </form>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </main>
</body>
</html>
