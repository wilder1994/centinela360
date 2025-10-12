<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer Contraseña | Centinela360</title>

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
            animation: move 12s infinite ease-in-out;
        }

        @keyframes move {
            0% { transform: translate(0, 0); opacity: 0.8; }
            50% { transform: translate(50px, -60px); opacity: 1; }
            100% { transform: translate(0, 0); opacity: 0.8; }
        }

        /* Contenedor principal */
        .reset-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            position: relative;
            z-index: 1;
        }

        .reset-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 0 25px rgba(0, 200, 255, 0.1);
            animation: fadeIn 1.2s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reset-box h2 {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            background: linear-gradient(90deg, #00f6ff, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        label {
            color: #d1e8ff;
            font-size: 0.9rem;
            font-weight: 500;
        }

        input {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            color: white;
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            outline: none;
        }

        input:focus {
            box-shadow: 0 0 10px rgba(0, 200, 255, 0.4);
        }

        .btn-reset {
            background: #00bfff;
            color: white;
            padding: 12px 0;
            width: 100%;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .btn-reset:hover {
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
                <div class="particle" style="left: {{ $left }}%; top: {{ $top }}%; animation-delay: {{ $delay }}s;"></div>
            @endfor
        </div>
    </div>

    <div class="reset-container">
        <div class="reset-box">
            <h2>Restablecer contraseña</h2>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Token oculto -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <label for="email">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Nueva contraseña -->
                <div>
                    <label for="password">Nueva contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirmar contraseña -->
                <div>
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit" class="btn-reset">Actualizar Contraseña</button>

                <div class="extra-links mt-4">
                    <a href="{{ route('login') }}">Volver al inicio de sesión</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
