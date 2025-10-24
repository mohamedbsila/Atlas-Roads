<x-layouts.app>
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">🚪 Détails de la Salle</h5>
                <p class="text-sm text-slate-400 mt-1">{{ $room->name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.rooms.edit', $room) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                   style="background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%)">
                    <i class="fas fa-edit"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.rooms.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 font-bold text-slate-700 bg-white border border-slate-300 rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-100 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-tl from-purple-600 to-pink-400">
                            <i class="fas fa-calendar-check text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Total Réservations</p>
                            <h6 class="text-2xl font-bold text-slate-700">{{ $stats['total_reservations'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-tl from-emerald-600 to-teal-400">
                            <i class="fas fa-check-circle text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Confirmées</p>
                            <h6 class="text-2xl font-bold text-slate-700">{{ $stats['confirmed_reservations'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-tl from-cyan-600 to-blue-400">
                            <i class="fas fa-euro-sign text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-400">Revenu Total</p>
                            <h6 class="text-2xl font-bold text-slate-700">{{ number_format($stats['total_revenue'], 2) }}€</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <h6 class="mb-4 font-bold text-slate-700">Informations Générales</h6>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Nom:</span>
                            <span class="font-semibold text-slate-700">{{ $room->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Section:</span>
                            <span class="font-semibold text-slate-700">{{ $room->section->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Bibliothèque:</span>
                            <span class="font-semibold text-slate-700">{{ $room->section->bibliotheque->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Type:</span>
                            <span class="font-semibold text-slate-700">{{ ucfirst($room->type) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Capacité:</span>
                            <span class="font-semibold text-slate-700">{{ $room->capacity }} personnes</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Prix/Heure:</span>
                            <span class="font-semibold text-slate-700">{{ $room->price_per_hour }}€</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Statut:</span>
                            @if($room->is_active)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded text-xs">Actif</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Inactif</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <h6 class="mb-4 font-bold text-slate-700">Équipements & Description</h6>
                    <div class="space-y-3">
                        <div>
                            <span class="text-slate-600 block mb-2">Équipements:</span>
                            <div class="flex gap-3">
                                @if($room->has_pc)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs flex items-center gap-1">
                                        <i class="fas fa-desktop"></i> PC
                                    </span>
                                @endif
                                @if($room->has_wifi)
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs flex items-center gap-1">
                                        <i class="fas fa-wifi"></i> WiFi
                                    </span>
                                @endif
                                @if(!$room->has_pc && !$room->has_wifi)
                                    <span class="text-slate-400 text-sm">Aucun équipement</span>
                                @endif
                            </div>
                        </div>
                        @if($room->description)
                        <div>
                            <span class="text-slate-600 block mb-2">Description:</span>
                            <p class="text-slate-700 text-sm">{{ $room->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border mt-6">
            <div class="p-6">
                <h6 class="mb-4 font-bold text-slate-700">Réservations Récentes</h6>
                @if($room->reservations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs uppercase bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3">Utilisateur</th>
                                    <th class="px-6 py-3">Date</th>
                                    <th class="px-6 py-3">Heure</th>
                                    <th class="px-6 py-3">Durée</th>
                                    <th class="px-6 py-3">Prix</th>
                                    <th class="px-6 py-3">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($room->reservations->take(10) as $reservation)
                                <tr class="border-b hover:bg-slate-50">
                                    <td class="px-6 py-4">{{ $reservation->user->name }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</td>
                                    <td class="px-6 py-4">{{ $reservation->duration }} h</td>
                                    <td class="px-6 py-4 font-semibold">{{ number_format($reservation->total_price, 2) }}€</td>
                                    <td class="px-6 py-4">
                                        @if($reservation->status === 'confirmed')
                                            <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded text-xs">Confirmé</span>
                                        @elseif($reservation->status === 'cancelled')
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Annulé</span>
                                        @else
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ ucfirst($reservation->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-slate-400 text-center py-8">Aucune réservation pour cette salle</p>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>

