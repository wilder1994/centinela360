@extends('layouts.company')

@section('content')
<div class="space-y-8 animate-fadeIn">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Panel de {{ $company->name }}</h1>
            <p class="text-sm text-gray-500">
                Bienvenido,
                <span class="capitalize">{{ strtolower(Auth::user()->name) }}</span>
            </p>
        </div>

        @if ($company && $company->logo)
            <img src="{{ asset('storage/' . $company->logo) }}"
                 alt="Logo {{ $company->name }}"
                 class="h-12 rounded shadow-md object-contain">
        @endif
    </div>

    <!-- Tarjetas de métricas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition-all duration-200 flex items-center gap-4">
            <div class="icon-chip icon-safe">
                <x-icon name="users" class="w-6 h-6" />
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Usuarios activos</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</h2>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition-all duration-200 flex items-center gap-4">
            <div class="icon-chip icon-safe">
                <x-icon name="file" class="w-6 h-6" />
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Informes</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalReports }}</h2>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition-all duration-200 flex items-center gap-4">
            <div class="icon-chip icon-safe">
                <x-icon name="bell" class="w-6 h-6" />
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-semibold">Alertas</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $totalAlerts }}</h2>
            </div>
        </div>
    </div>

    <!-- Gráfico de actividad semanal -->
    <div class="bg-white p-6 rounded-xl shadow mt-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Actividad semanal</h2>
        <canvas id="activityChart" height="90"></canvas>
    </div>

    <!-- Estilo simple para la animación -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.4s ease-in-out;
        }
    </style>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('activityChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
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
});
</script>
@endpush
