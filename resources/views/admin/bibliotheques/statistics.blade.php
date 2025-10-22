<x-layouts.app>
    <div>
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.bibliotheques.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 mr-4 text-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                   style="background:linear-gradient(135deg,#64748b 0%,#475569 100%)">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h5 class="mb-0 font-bold text-slate-700 text-xl">📊 Statistiques des Bibliothèques</h5>
                    <p class="text-sm text-slate-400 mt-1">Vue d'ensemble et analyses</p>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border border border-slate-100">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 text-white rounded-xl" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-500">Bibliothèques</p>
                            <h5 class="mb-0 font-bold text-2xl">{{ $stats['total_bibliotheques'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border border border-slate-100">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 text-white rounded-xl" style="background:linear-gradient(135deg,#06b6d4 0%,#0891b2 100%)">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-500">Actives</p>
                            <h5 class="mb-0 font-bold text-2xl">{{ $stats['active_bibliotheques'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border border border-slate-100">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 text-white rounded-xl" style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-500">Total Livres</p>
                            <h5 class="mb-0 font-bold text-2xl">{{ $stats['total_books'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border border border-slate-100">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 text-white rounded-xl" style="background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%)">
                            <i class="fas fa-warehouse text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-500">Capacité Totale</p>
                            <h5 class="mb-0 font-bold text-2xl">{{ number_format($stats['total_capacity']) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Books per Bibliotheque -->
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <h6 class="mb-4 font-bold text-slate-700">
                        <i class="fas fa-chart-bar mr-2 text-emerald-500"></i>
                        Livres par Bibliothèque
                    </h6>
                    <div class="space-y-3">
                        @foreach($stats['books_by_bibliotheque'] as $bib)
                            @php
                                $percentage = $bib['capacity'] > 0 ? ($bib['books'] / $bib['capacity']) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-semibold text-slate-700">{{ $bib['name'] }}</span>
                                    <span class="text-sm text-slate-600">{{ $bib['books'] }} / {{ $bib['capacity'] ?? 'N/A' }}</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $percentage > 80 ? 'bg-red-500' : ($percentage > 50 ? 'bg-amber-500' : 'bg-emerald-500') }}" 
                                         style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cities Distribution -->
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <h6 class="mb-4 font-bold text-slate-700">
                        <i class="fas fa-city mr-2 text-blue-500"></i>
                        Répartition par Ville
                    </h6>
                    <div class="space-y-3">
                        @foreach($stats['cities'] as $city)
                            @php
                                $cityPercentage = ($city->count / $stats['total_bibliotheques']) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-semibold text-slate-700">{{ $city->city }}</span>
                                    <span class="text-sm text-slate-600">{{ $city->count }} ({{ number_format($cityPercentage, 1) }}%)</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5">
                                    <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $cityPercentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="mt-6">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <h6 class="mb-4 font-bold text-slate-700">
                        <i class="fas fa-table mr-2 text-purple-500"></i>
                        Détails Complets
                    </h6>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Bibliothèque</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Ville</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Livres</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Capacité</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Taux d'occupation</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @foreach($bibliotheques as $bibliotheque)
                                    @php
                                        $occupancy = $bibliotheque->capacity > 0 ? ($bibliotheque->books_count / $bibliotheque->capacity) * 100 : 0;
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-slate-900">{{ $bibliotheque->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-slate-700">{{ $bibliotheque->city }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                {{ $bibliotheque->books_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="text-sm text-slate-700">{{ $bibliotheque->capacity ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($bibliotheque->capacity)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                    {{ $occupancy > 80 ? 'bg-red-100 text-red-800' : ($occupancy > 50 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                                    {{ number_format($occupancy, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-sm text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($bibliotheque->is_active)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

