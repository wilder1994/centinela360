@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Título principal -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Panel de Control</h1>
        <span class="text-sm text-gray-500">Bienvenido, {{ Auth::user()->name }}</span>
    </div>

    <!-- Tarjetas resumen -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-600 text-sm uppercase font-semibold">Empresas</h2>
                    <p class="text-2xl font-bold text-gray-800 mt-2">12</p>
                </div>
                <div class="bg-cyan-100 p-3 rounded-full">
                    🏢
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-600 text-sm uppercase font-semibold">Usuarios</h2>
                    <p class="text-2xl font-bold text-gray-800 mt-2">58</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    👥
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-600 text-sm uppercase font-semibold">Reportes</h2>
                    <p class="text-2xl font-bold text-gray-800 mt-2">32</p>
                </div>
                <div class="bg-indigo-100 p-3 rounded-full">
                    📊
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-gray-600 text-sm uppercase font-semibold">Alertas</h2>
                    <p class="text-2xl font-bold text-gray-800 mt-2">5</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    🚨
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de gráfica y actividad -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">

        <!-- Gráfico -->
        <div class="col-span-2 bg-white rounded-xl p-6 shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Actividad del sistema</h3>
            <canvas id="activityChart" height="100"></canvas>
        </div>

        <!-- Actividad reciente -->
        <div class="bg-white rounded-xl p-6 shadow-md border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Actividad reciente</h3>
            <ul class="space-y-3 text-gray-600 text-sm">
                <li>✅ Nueva empresa registrada <span class="text-gray-400">· hace 2h</span></li>
                <li>👤 Usuario “Jhon Doe” inició sesión <span class="text-gray-400">· hace 3h</span></li>
                <li>📁 Reporte generado por “Empresa Alpha” <span class="text-gray-400">· hace 5h</span></li>
                <li>⚙️ Se actualizó configuración del sistema <span class="text-gray-400">· ayer</span></li>
            </ul>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('activityChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Accesos al sistema',
                data: [12, 19, 8, 15, 22, 14, 10],
                borderColor: '#00bfff',
                backgroundColor: 'rgba(0, 200, 255, 0.2)',
                tension: 0.3,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
