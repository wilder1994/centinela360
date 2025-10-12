<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Centinela360') }}</title>

    <!-- Tailwind & App -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f5f7fa;
        }

        /* --- Sidebar --- */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #001f3f, #004080);
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .logo {
            text-align: center;
            padding: 1.5rem 0;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #00f6ff, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            color: #d1e8ff;
            padding: 0.9rem 1.5rem;
            transition: all 0.3s ease;
        }

        .sidebar nav a:hover,
        .sidebar nav a.active {
            background: rgba(0, 200, 255, 0.15);
            color: #00e0ff;
            border-left: 3px solid #00e0ff;
        }

        /* --- Main Layout --- */
        .main {
            margin-left: 250px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        .main.collapsed {
            margin-left: 80px;
        }

        /* --- Topbar --- */
        .topbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .topbar button {
            background: none;
            border: none;
            color: #007bff;
            font-size: 1.4rem;
            cursor: pointer;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 500;
        }

        /* --- Content Area --- */
        .content {
            padding: 2rem;
            background-color: #f5f7fa;
            flex: 1;
        }

        /* --- Responsive --- */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 20;
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="antialiased">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div>
            <div class="logo">Centinela360</div>
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
                <a href="{{ route('admin.companies.index') }}" class="{{ request()->routeIs('admin.companies.index') ? 'active' : '' }}">🏢 Empresas</a>
                <a href="#">👥 Usuarios</a>
                <a href="#">📊 Reportes</a>
                <a href="#">⚙️ Configuración</a>
            </nav>
        </div>
        <div class="p-4 text-center text-sm text-gray-300">
            © {{ date('Y') }} Centinela360
        </div>
    </div>

    <!-- Main Content -->
    <div id="main" class="main">
        <div class="topbar">
            <button id="toggleSidebar">☰</button>
            <div class="user-info">
                <span>{{ Auth::user()->name ?? 'Usuario' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 ml-2 text-sm hover:underline">Cerrar sesión</button>
                </form>
            </div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main');
        const toggleBtn = document.getElementById('toggleSidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
