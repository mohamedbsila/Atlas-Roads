<x-layouts.app>
    <div>
        @if(session('success'))
            <div class="mb-4 p-4 text-white rounded-xl flex items-center justify-between" 
                 style="background:linear-gradient(to right,#10b981,#34d399)">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-wrap justify-between items-center mb-6">
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">📚 Gestion des Bibliothèques</h5>
                <p class="text-sm text-slate-400 mt-1">Gérer toutes les bibliothèques du système</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.bibliotheques.statistics') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-xs shadow-soft-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                   style="background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%)">
                    <i class="fas fa-chart-bar"></i>
                    <span>Statistiques</span>
                </a>
                <a href="{{ route('admin.bibliotheques.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-xs shadow-soft-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                   style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                    <i class="ni ni-fat-add"></i>
                    <span>Nouvelle Bibliothèque</span>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border border border-slate-100">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 text-white rounded-xl" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                            <i class="fas fa-building text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-500">Total Bibliothèques</p>
                            <h5 class="mb-0 font-bold text-2xl">{{ $stats['total'] }}</h5>
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
                            <h5 class="mb-0 font-bold text-2xl">{{ $stats['active'] }}</h5>
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
                            <h5 class="mb-0 font-bold text-2xl">{{ number_format($stats['total_capacity'] ?? 0) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search Section -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h6 class="mb-0 font-semibold text-slate-700">
                        <i class="ni ni-settings-gear-65 mr-2"></i>Recherche & Filtres
                    </h6>
                    <a href="{{ route('admin.bibliotheques.index') }}"
                       class="px-4 py-2 text-xs font-bold text-white uppercase rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                       style="background:linear-gradient(135deg,#06b6d4 0%,#0ea5e9 100%)">
                        <i class="fas fa-sync-alt mr-1"></i> Réinitialiser
                    </a>
                </div>

                <form method="GET" action="{{ route('admin.bibliotheques.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Search Input -->
                        <div class="relative">
                            <label class="block mb-2 text-xs font-semibold text-slate-600 uppercase">
                                <i class="fas fa-search mr-1"></i>Recherche
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Nom, ville, adresse..."
                                   class="w-full px-4 py-2.5 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all duration-200">
                        </div>

                        <!-- City Filter -->
                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-600 uppercase">
                                <i class="fas fa-city mr-1"></i>Ville
                            </label>
                            <select name="city" 
                                    class="w-full px-4 py-2.5 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all duration-200">
                                <option value="">Toutes les villes</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block mb-2 text-xs font-semibold text-slate-600 uppercase">
                                <i class="fas fa-toggle-on mr-1"></i>Statut
                            </label>
                            <select name="status" 
                                    class="w-full px-4 py-2.5 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition-all duration-200">
                                <option value="">Tous</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactives</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2.5 text-xs font-bold text-white uppercase rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                            <i class="fas fa-filter mr-1"></i> Appliquer les filtres
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bibliotheques Table -->
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow-soft-xl rounded-2xl">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                        Bibliothèque
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                        Localisation
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                        Contact
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                                        Livres
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse($bibliotheques as $bibliotheque)
                                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($bibliotheque->image)
                                                    <img src="{{ Storage::url($bibliotheque->image) }}" 
                                                         alt="{{ $bibliotheque->name }}"
                                                         class="h-12 w-12 rounded-lg object-cover mr-3">
                                                @else
                                                    <div class="h-12 w-12 rounded-lg mr-3 flex items-center justify-center text-white"
                                                         style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                                                        <i class="fas fa-building text-xl"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900">{{ $bibliotheque->name }}</div>
                                                    @if($bibliotheque->capacity)
                                                        <div class="text-xs text-slate-500">Capacité: {{ number_format($bibliotheque->capacity) }} livres</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-900">
                                                <i class="fas fa-map-marker-alt text-emerald-500 mr-1"></i>
                                                {{ $bibliotheque->city }}
                                            </div>
                                            <div class="text-xs text-slate-500">{{ $bibliotheque->address }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($bibliotheque->phone)
                                                <div class="text-sm text-slate-900">
                                                    <i class="fas fa-phone text-blue-500 mr-1"></i>
                                                    {{ $bibliotheque->phone }}
                                                </div>
                                            @endif
                                            @if($bibliotheque->email)
                                                <div class="text-xs text-slate-500">
                                                    <i class="fas fa-envelope mr-1"></i>
                                                    {{ $bibliotheque->email }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                <i class="fas fa-book mr-1"></i>
                                                {{ $bibliotheque->books_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($bibliotheque->is_active)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.bibliotheques.show', $bibliotheque) }}" 
                                                   class="px-3 py-1.5 text-xs font-bold text-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                                   style="background:linear-gradient(135deg,#06b6d4 0%,#0891b2 100%)"
                                                   title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.bibliotheques.edit', $bibliotheque) }}" 
                                                   class="px-3 py-1.5 text-xs font-bold text-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                                   style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)"
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.bibliotheques.destroy', $bibliotheque) }}" 
                                                      method="POST" 
                                                      class="inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette bibliothèque?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="px-3 py-1.5 text-xs font-bold text-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                                            style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%)"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-building text-6xl text-slate-300 mb-4"></i>
                                                <p class="text-lg font-semibold text-slate-600">Aucune bibliothèque trouvée</p>
                                                <p class="text-sm text-slate-400 mt-1">Commencez par créer votre première bibliothèque</p>
                                                <a href="{{ route('admin.bibliotheques.create') }}" 
                                                   class="mt-4 inline-flex items-center gap-2 px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-xs shadow-soft-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                                   style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                                                    <i class="ni ni-fat-add"></i>
                                                    <span>Créer une bibliothèque</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($bibliotheques->hasPages())
                <div class="mt-6">
                    {{ $bibliotheques->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>

