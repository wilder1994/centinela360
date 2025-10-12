<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verificar Correo | Centinela360</title>

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

        /* Fondo con gradiente animado y textura digital */
        .background {
            position: fixed;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, rgba(0, 20, 50, 0.9), rgba(0, 80, 140, 0.7)),
                        url('{{ asset("images/securityfondo.jpg") }}') center/cover no-repeat;
            z-index: -1;
        }

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
            width: 5px;
            height: 5px;
            animation: move 10s infinite ease-in-out;
        }

        @keyframes move {
            0% { transform: translate(0, 0); opacity: 0.8; }
            50% { transform: translate(50px, -60px); opacity: 1; }
            100% { transform: translate(0, 0); opacity: 0.8; }
        }

        /* Caja principal */
        .verify-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative;
            z-index: 1;
        }

        .verify-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 480px;
            text-align: center;
            box-shadow: 0 0 25px rgba(0, 200, 255, 0.1);
            animation: fadeIn 1.2s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .verify-box img {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 0 10px rgba(0, 255, 255, 0.4));
        }

        .verify-box h2 {
            font-size: 1.7rem;
            font-weight: 700;
            background: linear-gradient(90deg, #00f6ff, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }

        .verify-box p {
            font-size: 1rem;
            color: #d1e8ff;
            margin-bottom: 25px;
        }

        .btn {
            background: #00bfff;
            color: white;
            padding: 12px 0;
            width: 100%;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
            text-transform: uppercase;
            border: none;
        }

        .btn:hover {
            background: #0099cc;
            box-shadow: 0 0 15px rgba(0, 200, 255, 0.6);
        }

        .logout {
            margin-top: 20px;
            color: #a0cfff;
            font-size: 0.9rem;
        }

        .logout button {
            background: transparent;
            border: none;
            color: #00f6ff;
            cursor: pointer;
            font-weight: 500;
        }

        .logout button:hover {
            text-decoration: underline;
        }

        .alert {
            background: rgba(0, 200, 100, 0.2);
            border-left: 4px solid #00f6ff;
            color: #9ef7ff;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
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

    <div class="verify-container">
        <div class="verify-box">
            <img src="{{ asset('images/securitylogo.jpg') }}" alt="Logo Centinela360">
            <h2>Verificación de correo</h2>

            <p>Gracias por registrarte. Por favor verifica tu dirección de correo electrónico haciendo clic en el enlace que te hemos enviado.</p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert">
                    Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn">Reenviar correo de verificación</button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="logout">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
