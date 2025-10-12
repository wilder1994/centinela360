<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Centinela360: Tecnología e Inteligencia para la Seguridad Empresarial. Plataforma moderna para empresas de seguridad privada.">
    <title>Centinela360</title>

    <!-- Tailwind & Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            height: 100vh;
            font-family: 'Figtree', sans-serif;
            color: white;
            overflow: hidden;
            position: relative;
        }

        /* 🌌 Fondo con gradiente + imagen */
        .hero {
            position: relative;
            background: linear-gradient(120deg,
                rgba(0, 20, 60, 0.85),
                rgba(0, 90, 160, 0.6)
            ),
            url('{{ asset("images/securityfondo.jpg") }}') center/cover no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* ✨ Barra de navegación superior */
        nav {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.25);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 5;
        }

        nav .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, #00f6ff, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        nav .links a {
            color: white;
            margin-left: 25px;
            font-weight: 500;
            text-decoration: none;
            transition: 0.3s;
        }

        nav .links a:hover {
            color: #00f6ff;
        }

        /* 💫 Partículas animadas */
        .particles {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(0, 200, 255, 0.8);
            border-radius: 50%;
            width: 5px; height: 5px;
            animation: move 12s infinite ease-in-out;
        }

        @keyframes move {
            0% { transform: translate(0, 0); opacity: 0.8; }
            50% { transform: translate(50px, -60px); opacity: 1; }
            100% { transform: translate(0, 0); opacity: 0.8; }
        }

        /* ✍️ Contenido central */
        .content {
            position: relative;
            z-index: 1;
            max-width: 700px;
            padding: 20px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 1.5s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 140px;
            height: auto;
            margin: 0 auto 25px;
            filter: drop-shadow(0 0 10px rgba(0, 255, 255, 0.4));
        }

        h1 {
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #00f6ff, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .typed-text {
            font-size: 1.3rem;
            font-weight: 400;
            border-right: 2px solid #00f6ff;
            white-space: nowrap;
            overflow: hidden;
            width: 0;
            margin: 0 auto;
            animation: typing 4s steps(50, end) forwards, blink 1s step-end infinite;
        }

        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }

        @keyframes blink {
            50% { border-color: transparent; }
        }

        .buttons {
            margin-top: 40px;
        }

        .btn {
            background: #00bfff;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s ease;
            text-decoration: none;
        }

        .btn:hover {
            background: #008ecc;
            box-shadow: 0 0 15px rgba(0, 200, 255, 0.6);
        }

        /* 📱 Responsive */
        @media (max-width: 768px) {
            h1 { font-size: 2.3rem; }
            .typed-text { font-size: 1rem; }
            .btn { padding: 10px 24px; }
            nav { padding: 12px 20px; }
            nav .links a { margin-left: 15px; font-size: 0.9rem; }
        }
    </style>
</head>
<body>
    <div class="hero">
        <nav>
            <div class="logo-text">Centinela360</div>
            <div class="links">
                <a href="#">Inicio</a>
                <a href="#">Nosotros</a>
                <a href="{{ route('login') }}">Ingresar</a>
            </div>
        </nav>

        <div class="particles">
            @for ($i = 0; $i < 25; $i++)
                @php
                    $left = rand(0, 100);
                    $top = rand(0, 100);
                    $delay = rand(0, 10);
                @endphp
                <div class="particle"
                    data-left="{{ $left }}"
                    data-top="{{ $top }}"
                    data-delay="{{ $delay }}"></div>
            @endfor
        </div>

        <script>
            document.querySelectorAll('.particle').forEach(p => {
                p.style.left = p.dataset.left + '%';
                p.style.top = p.dataset.top + '%';
                p.style.animationDelay = p.dataset.delay + 's';
            });
        </script>

        <div class="content">
            <img src="{{ asset('images/securitylogo.jpg') }}" alt="Logo" class="logo">
            <h1>Centinela360</h1>
            <p class="typed-text">Tecnología e Inteligencia para la Seguridad Empresarial</p>

            <div class="buttons">
                <a href="{{ route('login') }}" class="btn">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn ml-4 bg-transparent border border-cyan-400 hover:bg-cyan-600">Registrarse</a>
            </div>
        </div>
    </div>
</body>
</html>
