<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | Centinela360</title>

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

        /* Fondo animado */
        .background {
            position: fixed;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, rgba(0, 15, 40, 0.9), rgba(0, 60, 120, 0.7)),
                        url('{{ asset("images/securityfondo.jpg") }}') center/cover no-repeat;
            z-index: -1;
        }

        .particles {
            position: absolute;
            width: 100%; height: 100%;
            top: 0; left: 0;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(0, 200, 255, 0.8);
            border-radius: 50%;
            width: 5px;
            height: 5px;
            animation: move 12s infinite ease-in-out;
        }

        @keyframes move {
            0% { transform: translate(0, 0); opacity: 0.8; }
            50% { transform: translate(50px, -60px); opacity: 1; }
            100% { transform: translate(0, 0); opacity: 0.8; }
        }

        /* Contenedor del login */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative;
            z-index: 1;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 25px rgba(0, 200, 255, 0.1);
            animation: fadeIn 1.2s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-box h2 {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 30px;
            background: linear-gradient(90deg, #00f6ff, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-box label {
            color: #d1e8ff;
            font-size: 0.9rem;
            font-weight: 500;
        }

        input[type="email"], input[type="password"] {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            color: white;
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            outline: none;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            box-shadow: 0 0 10px rgba(0, 200, 255, 0.4);
        }

        .btn-login {
            background: #00bfff;
            color: white;
            padding: 12px 0;
            width: 100%;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .btn-login:hover {
            background: #0099cc;
            box-shadow: 0 0 15px rgba(0, 200, 255, 0.6);
        }

        .extra-links {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }

        .extra-links a {
            color: #00f6ff;
            text-decoration: none;
        }

        .extra-links a:hover {
            text-decoration: underline;
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
                <div class="particle"
                    style="left: {{ $left }}%; top: {{ $top }}%; animation-delay: {{ $delay }}s;"></div>
            @endfor
        </div>
    </div>

    <div class="login-container">
        <div class="login-box">
            <h2>Bienvenido a Centinela360</h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email">Correo Electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mt-2 mb-4">
                    <input id="remember_me" type="checkbox" name="remember" class="mr-2 text-cyan-400 border-gray-300 rounded focus:ring-cyan-500">
                    <label for="remember_me" class="text-sm text-gray-300">Recordarme</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login">Iniciar Sesión</button>

                <div class="extra-links">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a><br>
                    @endif
                    <a href="{{ route('register') }}">Crear una cuenta nueva</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
