@extends('layouts.company')

@section('content')
<div class="space-y-8 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Panel de {{ $company->name }}</h1>
            <p class="text-sm text-gray-500">Bienvenido, <span class="capitalize">{{ strtolower(Auth::user()->name) }}</span></p>
        </div>
        @if ($company->logo)
            <img src="{{ asset('storage/' . $company->logo) }}" 
                 alt="Logo {{ $company->name }}" 
                 class="h-12 rounded shadow-md object-contain">
        @endif
    </div>

    <!-- Tarjetas estadísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Usuarios activos -->
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition-all duration-200 flex items-center gap-4">
            <div class="bg-[var(--primary)] bg-opacity-90 text-white p-3 rounded-xl">
                <!-- Usuario -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" 
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A8.001 8.001 0 0112 16c1.657 0 3.182.506 4.437 1.363M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Usuarios activos</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</h2>
            </div>
        </div>

        <!-- Informes -->
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition-all duration-200 flex items-center gap-4">
            <div class="bg-[var(--primary)] bg-opacity-90 text-white p-3 rounded-xl">
                <!-- Documentos -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17v-2a4 4 0 014-4h4M16 4H8a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Informes</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalReports }}</h2>
            </div>
        </div>

        <!-- Alertas -->
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition-all duration-200 flex items-center gap-4">
            <div class="bg-[var(--primary)] bg-opacity-90 text-white p-3 rounded-xl">
                <!-- Alerta -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" 
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.8-2.9l-6.93-12a2 2 0 00-3.46 0l-6.93 12a2 2 0 001.8 2.9z" /></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Alertas</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalAlerts }}</h2>
            </div>
        </div>
    </div>

    <!-- Actividad semanal -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Actividad semanal</h2>
        <canvas id="activityChart" height="90"></canvas>
    </div>
</div>

<!-- Animación -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.4s ease-in-out;
}
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('activityChart').getContext('2d');
const data = @json($activityData);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: data.map(d => d.day),
        datasets: [{
            label: 'Eventos',
            data: data.map(d => d.count),
            borderColor: '{{ $company->color_primary ?? "#06b6d4" }}',
            backgroundColor: '{{ $company->color_primary ?? "#06b6d4" }}22',
            pointBackgroundColor: '{{ $company->color_primary ?? "#06b6d4" }}',
            tension: 0.4,
            fill: true,
            borderWidth: 2,
            pointRadius: 4
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: "#9ca3af", stepSize: 1 },
                grid: { color: "#f3f4f6" }
            },
            x: {
                ticks: { color: "#9ca3af" },
                grid: { display: false }
            }
        }
    }
});
</script>
@endsection
