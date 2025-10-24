<x-layouts.base>
    <x-client-navbar />
    
    <div class="container mx-auto px-4 py-8 mt-4">
        <div class="mb-6">
            <h5 class="mb-0 font-bold text-slate-700 text-xl">🔍 Rechercher une Salle d'Étude</h5>
            <p class="text-sm text-slate-400 mt-1">Trouvez la salle parfaite avec l'IA</p>
        </div>

        <!-- Search Filters -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <form action="{{ route('rooms.search') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- Style -->
                        <div>
                            <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Type de salle</label>
                            <select name="style" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                                <option value="">Tous</option>
                                <option value="individual" {{ request('style') === 'individual' ? 'selected' : '' }}>Individuel</option>
                                <option value="group" {{ request('style') === 'group' ? 'selected' : '' }}>Groupe</option>
                                <option value="conference" {{ request('style') === 'conference' ? 'selected' : '' }}>Conférence</option>
                            </select>
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Capacité minimum</label>
                            <input type="number" name="capacity" value="{{ request('capacity') }}" min="1" 
                                   class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all"
                                   placeholder="Ex: 4">
                        </div>

                        <!-- Max Price -->
                        <div>
                            <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Prix max/heure (€)</label>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" step="0.01"
                                   class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all"
                                   placeholder="Ex: 20">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- Date -->
                        <div>
                            <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}"
                                   class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Heure de début</label>
                            <input type="time" name="start_time" value="{{ request('start_time') }}"
                                   class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                        </div>

                        <!-- End Time -->
                        <div>
                            <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Heure de fin</label>
                            <input type="time" name="end_time" value="{{ request('end_time') }}"
                                   class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                        </div>
                    </div>

                    <div class="flex gap-2 items-center mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="has_pc" value="1" {{ request('has_pc') ? 'checked' : '' }}
                                   class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700">PC disponible</span>
                        </label>
                        <label class="flex items-center ml-4">
                            <input type="checkbox" name="has_wifi" value="1" {{ request('has_wifi') ? 'checked' : '' }}
                                   class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                            <span class="ml-2 text-sm text-gray-700">WiFi</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="px-6 py-3 font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                            style="background:linear-gradient(135deg,#06b6d4 0%,#0891b2 100%)">
                        <i class="fas fa-search mr-2"></i>Rechercher
                    </button>
                </form>
            </div>
        </div>

        <!-- Results -->
        @if(isset($rooms))
            <div class="mb-4 text-sm text-slate-600">
                <i class="fas fa-robot text-cyan-500 mr-2"></i>
                {{ $rooms->count() }} salle(s) trouvée(s) - Classées par IA
            </div>

            @if($rooms->isEmpty())
                <div class="text-center py-12 bg-white rounded-2xl shadow-soft-xl">
                    <i class="fas fa-search text-6xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500 text-lg">Aucune salle disponible avec ces critères</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($rooms as $room)
                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border hover:shadow-lg transition-all">
                        @if(isset($room->ai_score))
                        <div class="absolute top-4 right-4 bg-gradient-to-r from-cyan-500 to-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                            <i class="fas fa-star mr-1"></i>Score: {{ $room->ai_score }}
                        </div>
                        @endif
                        
                        <div class="p-6">
                            <h6 class="mb-2 font-bold text-slate-700 text-lg">{{ $room->name }}</h6>
                            <p class="text-sm text-slate-500 mb-3">
                                <i class="fas fa-layer-group mr-1"></i>{{ $room->section->name }} - 
                                {{ $room->section->bibliotheque->name }}
                            </p>
                            
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="px-2 py-1 text-xs bg-slate-100 rounded">
                                    <i class="fas fa-users mr-1"></i>{{ $room->capacity }} places
                                </span>
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">
                                    {{ ucfirst($room->style) }}
                                </span>
                                @if($room->has_pc)
                                <span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-800 rounded">
                                    <i class="fas fa-desktop mr-1"></i>PC
                                </span>
                                @endif
                                @if($room->has_wifi)
                                <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">
                                    <i class="fas fa-wifi mr-1"></i>WiFi
                                </span>
                                @endif
                            </div>

                            @if(isset($room->distance))
                            <p class="text-sm text-slate-500 mb-3">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $room->distance }} km
                            </p>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-emerald-600">
                                    {{ $room->price_per_hour }}€<span class="text-sm text-slate-500">/h</span>
                                </span>
                                <a href="{{ route('rooms.show', $room) }}" 
                                   class="px-4 py-2 text-sm font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                                   style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                                    Réserver
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</x-layouts.base>

