@extends('layouts.app')

@section('title', 'Rapports - Hôtel Manager')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Rapports et Statistiques</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Analysez les performances de votre hôtel</p>
        </div>

        <!-- Date Filter -->
        <div class="bg-white dark:bg-slate-800 shadow rounded-lg mb-8">
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('reports') }}" class="flex flex-col sm:flex-row gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de début</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date de fin</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md font-medium">Filtrer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Reservations -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Réservations Totales</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalReservations }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmed Reservations -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Réservations Confirmées</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $confirmedReservations }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Revenus Totaux</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">€{{ number_format($totalRevenue, 2, ',', ' ') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Occupancy Rate -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Taux d'Occupation</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0 }}%</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Detailed Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Évolution des Revenus (6 derniers mois)</h3>
                </div>
                <div class="p-6">
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Room Type Statistics -->
            <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Statistiques par Type de Chambre</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($roomTypeStats as $stat)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600 dark:text-gray-300">{{ $stat['type'] }}</span>
                                <span class="text-gray-900 dark:text-white">{{ $stat['occupied'] }}/{{ $stat['total'] }} ({{ $stat['occupancy_rate'] }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-primary h-2 rounded-full" style="width: {{ $stat['occupancy_rate'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Rooms -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-1 0h-1m-4 0H7m4 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m9-10V5"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Chambres Totales</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalRooms }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Rooms -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Chambres Disponibles</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $availableRooms }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Clients -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Clients Totaux</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalClients }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Clients -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Nouveaux Clients</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $newClients }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const monthlyRevenue = @json($monthlyRevenue);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyRevenue.map(item => item.month),
            datasets: [{
                label: 'Revenus (€)',
                data: monthlyRevenue.map(item => item.revenue),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '€' + value.toLocaleString('fr-FR');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
