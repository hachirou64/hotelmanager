@extends('layouts.app')

@section('title', 'Tableau de Bord - Hôtel Manager')

@section('content')
            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Bienvenue sur votre Tableau de Bord</h1>
                <p class="text-gray-600 dark:text-gray-300">Voici un aperçu de l'activité de votre hôtel aujourd'hui.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                <!-- Total Rooms -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                <!-- Occupied Rooms -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Chambres Occupées</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $occupiedRooms }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Reservations -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Réservations Aujourd'hui</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $todayReservations }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Today -->
                <div class="bg-white dark:bg-slate-800 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="shrink-0">
                                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Revenus Aujourd'hui</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-white">€{{ number_format($todayRevenue, 2, ',', ' ') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities & Quick Actions -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 md:gap-8">
                <!-- Recent Reservations -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Réservations Récentes</h3>
                        <div class="space-y-3">
                            @forelse($recentReservations as $reservation)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $reservation->client->nom ?? 'N/A' }} {{ $reservation->client->prenom ?? '' }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $reservation->room->numero_chambre ?? 'N/A' }} - {{ $reservation->date_debut->diffInDays($reservation->date_fin) }} nuits</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reservation->statut === 'confirmée' ? 'bg-green-100 text-green-800' : ($reservation->statut === 'en cours' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($reservation->statut) }}
                                </span>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Aucune réservation récente</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-slate-800 shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Actions Rapides</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <a href="{{ route('reservations') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition duration-200">
                                <svg class="w-8 h-8 text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Nouvelle Réservation</span>
                            </a>
                            <a href="{{ route('clients') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition duration-200">
                                <svg class="w-8 h-8 text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Nouveau Client</span>
                            </a>
                            <a href="{{ route('billing') }}" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition duration-200">
                                <svg class="w-8 h-8 text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Nouvelle Facture</span>
                            </a>
                            <a href="#" class="flex flex-col items-center p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition duration-200">
                                <svg class="w-8 h-8 text-primary mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Rapports</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
@endsection
