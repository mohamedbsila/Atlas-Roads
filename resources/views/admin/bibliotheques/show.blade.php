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
                    <h5 class="mb-0 font-bold text-slate-700 text-xl">{{ $bibliotheque->name }}</h5>
                    <p class="text-sm text-slate-400 mt-1">{{ $bibliotheque->city }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.bibliotheques.edit', $bibliotheque) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-xs shadow-soft-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                   style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)">
                    <i class="fas fa-edit"></i>
                    <span>Modifier</span>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border border border-slate-100">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 text-white rounded-xl" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
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
                        <div class="flex-shrink-0 p-3 text-white rounded-xl" style="background:linear-gradient(135deg,#06b6d4 0%,#0891b2 100%)">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-500">Disponibles</p>
                            <h5 class="mb-0 font-bold text-2xl">{{ $stats['available_books'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border border border-slate-100">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 text-white rounded-xl" style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)">
                            <i class="fas fa-bookmark text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-500">Empruntés</p>
                            <h5 class="mb-0 font-bold text-2xl">{{ $stats['borrowed_books'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border border border-slate-100">
                <div class="flex-auto p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 text-white rounded-xl" style="background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%)">
                            <i class="fas fa-layer-group text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="mb-0 text-sm font-semibold text-slate-500">Catégories</p>
                            <h5 class="mb-0 font-bold text-2xl">{{ $stats['categories'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <!-- Details -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-bold text-slate-700">
                            <i class="fas fa-info-circle mr-2 text-emerald-500"></i>
                            Informations Détaillées
                        </h6>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="mb-1 text-xs font-semibold text-slate-500 uppercase">Adresse</p>
                                <p class="text-sm font-medium text-slate-700">{{ $bibliotheque->address }}</p>
                            </div>
                            <div>
                                <p class="mb-1 text-xs font-semibold text-slate-500 uppercase">Ville</p>
                                <p class="text-sm font-medium text-slate-700">{{ $bibliotheque->city }}</p>
                            </div>
                            @if($bibliotheque->postal_code)
                            <div>
                                <p class="mb-1 text-xs font-semibold text-slate-500 uppercase">Code Postal</p>
                                <p class="text-sm font-medium text-slate-700">{{ $bibliotheque->postal_code }}</p>
                            </div>
                            @endif
                            @if($bibliotheque->phone)
                            <div>
                                <p class="mb-1 text-xs font-semibold text-slate-500 uppercase">Téléphone</p>
                                <p class="text-sm font-medium text-slate-700">{{ $bibliotheque->phone }}</p>
                            </div>
                            @endif
                            @if($bibliotheque->email)
                            <div>
                                <p class="mb-1 text-xs font-semibold text-slate-500 uppercase">Email</p>
                                <p class="text-sm font-medium text-slate-700">{{ $bibliotheque->email }}</p>
                            </div>
                            @endif
                            @if($bibliotheque->website)
                            <div>
                                <p class="mb-1 text-xs font-semibold text-slate-500 uppercase">Site Web</p>
                                <a href="{{ $bibliotheque->website }}" target="_blank" class="text-sm font-medium text-blue-600 hover:underline">Visiter</a>
                            </div>
                            @endif
                            @if($bibliotheque->opening_time && $bibliotheque->closing_time)
                            <div>
                                <p class="mb-1 text-xs font-semibold text-slate-500 uppercase">Horaires</p>
                                <p class="text-sm font-medium text-slate-700">{{ $bibliotheque->formatted_opening_hours }}</p>
                            </div>
                            @endif
                            @if($bibliotheque->capacity)
                            <div>
                                <p class="mb-1 text-xs font-semibold text-slate-500 uppercase">Capacité</p>
                                <p class="text-sm font-medium text-slate-700">{{ number_format($bibliotheque->capacity) }} livres</p>
                            </div>
                            @endif
                        </div>

                        @if($bibliotheque->description)
                        <div class="mt-4">
                            <p class="mb-1 text-xs font-semibold text-slate-500 uppercase">Description</p>
                            <p class="text-sm text-slate-600">{{ $bibliotheque->description }}</p>
                        </div>
                        @endif

                        @if($bibliotheque->opening_days && count($bibliotheque->opening_days) > 0)
                        <div class="mt-4">
                            <p class="mb-2 text-xs font-semibold text-slate-500 uppercase">Jours d'ouverture</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($bibliotheque->opening_days as $day)
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">{{ $day }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Books -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-bold text-slate-700">
                            <i class="fas fa-book mr-2 text-blue-500"></i>
                            Livres Récents
                        </h6>
                        
                        @if($recentBooks->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentBooks as $book)
                                    <div class="flex items-center p-3 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="h-16 w-12 object-cover rounded-md mr-3">
                                        <div class="flex-1">
                                            <p class="font-semibold text-sm text-slate-900">{{ $book->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $book->author }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $book->is_available ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $book->is_available ? 'Disponible' : 'Emprunté' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-500 text-center py-4">Aucun livre dans cette bibliothèque</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Image -->
                @if($bibliotheque->image)
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-bold text-slate-700">
                            <i class="fas fa-image mr-2 text-pink-500"></i>
                            Photo
                        </h6>
                        <img src="{{ Storage::url($bibliotheque->image) }}" alt="{{ $bibliotheque->name }}" class="w-full rounded-lg shadow-md">
                    </div>
                </div>
                @endif

                <!-- Status -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-bold text-slate-700">
                            <i class="fas fa-toggle-on mr-2 text-emerald-500"></i>
                            Statut
                        </h6>
                        @if($bibliotheque->is_active)
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-emerald-100 text-emerald-800">
                                <i class="fas fa-check-circle mr-2"></i>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-2"></i>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <!-- GPS Coordinates -->
                @if($bibliotheque->latitude && $bibliotheque->longitude)
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-bold text-slate-700">
                            <i class="fas fa-map-marked-alt mr-2 text-purple-500"></i>
                            Coordonnées GPS
                        </h6>
                        <div class="space-y-2">
                            <div>
                                <p class="text-xs font-semibold text-slate-500">Latitude</p>
                                <p class="text-sm font-mono text-slate-700">{{ $bibliotheque->latitude }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-500">Longitude</p>
                                <p class="text-sm font-mono text-slate-700">{{ $bibliotheque->longitude }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>

