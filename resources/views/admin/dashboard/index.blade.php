@extends('layouts.app')

@section('content')
<div class="space-y-8">

    <!-- Título principal -->
    <h1 class="text-3xl font-bold text-gray-800">Panel de control</h1>

    <!-- Tarjetas resumen -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-semibold">Empresas</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $totalCompanies }}</h2>
                </div>
                <div class="p-3 bg-cyan-100 text-cyan-600 rounded-lg">
                    <i class="fas fa-building text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-semibold">Usuarios</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</h2>
                </div>
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-lg">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-semibold">Informes</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $totalReports }}</h2>
                </div>
                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-semibold">Alertas</p>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $totalAlerts }}</h2>
                </div>
                <div class="p-3 bg-red-100 text-red-600 rounded-lg">
                    <i class="fas fa-bell text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico + Actividad reciente -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Gráfico de actividad -->
        <div class="bg-white p-6 rounded-xl shadow col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Actividad del sistema</h2>
            <canvas id="activityChart" height="120"></canvas>
        </div>

        <!-- Actividad reciente -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Actividad reciente</h2>
            <ul class="space-y-3 text-sm text-gray-600">
                @foreach($recentCompanies as $company)
                    <li class="flex items-center space-x-2">
                        <span class="text-green-500">●</span>
                        <span>Nueva empresa registrada: <strong>{{ $company->name }}</strong></span>
                        <span class="text-gray-400 text-xs">{{ $company->created_at->diffForHumans() }}</span>
                    </li>
                @endforeach

                @foreach($recentUsers as $user)
                    <li class="flex items-center space-x-2">
                        <span class="text-indigo-500">●</span>
                        <span>Nuevo usuario: <strong>{{ $user->name }}</strong></span>
                        <span class="text-gray-400 text-xs">{{ $user->created_at->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('activityChart').getContext('2d');
    const chartData = @json($activityData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(d => d.day),
            datasets: [{
                label: 'Actividad',
                data: chartData.map(d => d.count),
                fill: true,
                backgroundColor: 'rgba(6, 182, 212, 0.2)',
                borderColor: '#06b6d4',
                tension: 0.4,
                pointBackgroundColor: '#06b6d4'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection
