<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmar Contraseña | Centinela360</title>

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

        /* Fondo con imagen y gradiente */
        .background {
            position: fixed;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, rgba(0, 20, 50, 0.9), rgba(0, 80, 140, 0.7)),
                        url('{{ asset("images/securityfondo.jpg") }}') center/cover no-repeat;
            z-index: -1;
        }

        /* Partículas flotantes */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(0, 200, 255, 0.8);
            border-radius: 50%;
            width: 6px;
            height: 6px;
            animation: move 10s infinite ease-in-out;
        }

        @keyframes move {
            0% { transform: translateY(0); opacity: 0.8; }
            50% { transform: translateY(-40px); opacity: 1; }
            100% { transform: translateY(0); opacity: 0.8; }
        }

        /* Caja principal */
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative;
            z-index: 1;
        }

        .card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 0 25px rgba(0, 200, 255, 0.1);
            animation: fadeIn 1.2s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 110px;
            height: auto;
            margin: 0 auto 25px;
            filter: drop-shadow(0 0 10px rgba(0, 255, 255, 0.4));
        }

        h2 {
            font-size: 1.6rem;
            font-weight: 700;
            background: linear-gradient(90deg, #00f6ff, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        p {
            font-size: 1rem;
            color: #d1e8ff;
            margin-bottom: 25px;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 8px;
            color: white;
            outline: none;
            transition: 0.3s;
        }

        input[type="password"]:focus {
            border-color: #00bfff;
            box-shadow: 0 0 10px rgba(0, 200, 255, 0.4);
        }

        .btn {
            margin-top: 25px;
            background: #00bfff;
            color: white;
            padding: 12px 0;
            width: 100%;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #0099cc;
            box-shadow: 0 0 15px rgba(0, 200, 255, 0.6);
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="particles">
            @for ($i = 0; $i < 25; $i++)
                @php
                    $left = rand(0, 100);
                    $top = rand(0, 100);
                    $delay = rand(0, 10);
                @endphp
                <div class="particle" style="left: {{ $left }}%; top: {{ $top }}%; animation-delay: {{ $delay }}s;"></div>
            @endfor
        </div>
    </div>

    <div class="container">
        <div class="card">
            <img src="{{ asset('images/securitylogo.jpg') }}" alt="Logo Centinela360" class="logo">
            <h2>Confirmar Contraseña</h2>
            <p>Por favor, confirma tu contraseña antes de continuar en esta zona segura.</p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Ingresa tu contraseña">

                <button type="submit" class="btn">Confirmar</button>
            </form>
        </div>
    </div>
</body>
</html>
