<x-layouts.base>
    @include('layouts.navbars.guest.nav')
    
    <div class="container mx-auto px-4 py-8 mt-20">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">📅 Mes Réservations</h5>
                <p class="text-sm text-slate-400 mt-1">Gérez vos réservations de salles d'étude</p>
            </div>
            <a href="{{ route('rooms.search') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200"
               style="background:linear-gradient(135deg,#06b6d4 0%,#0891b2 100%)">
                <i class="fas fa-search"></i>
                Rechercher des salles
            </a>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-100 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif

        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                @if($reservations->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-6xl text-slate-300 mb-4"></i>
                        <p class="text-slate-500 text-lg mb-4">Vous n'avez aucune réservation</p>
                        <a href="{{ route('rooms.search') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                           style="background:linear-gradient(135deg,#06b6d4 0%,#0891b2 100%)">
                            <i class="fas fa-search"></i>
                            Rechercher des salles
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-700">
                            <thead class="text-xs uppercase bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3">Salle</th>
                                    <th class="px-6 py-3">Bibliothèque</th>
                                    <th class="px-6 py-3">Date</th>
                                    <th class="px-6 py-3">Horaire</th>
                                    <th class="px-6 py-3">Prix</th>
                                    <th class="px-6 py-3">Statut</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservations as $reservation)
                                <tr class="border-b hover:bg-slate-50">
                                    <td class="px-6 py-4 font-medium">
                                        {{ $reservation->room->name }}
                                        <span class="block text-xs text-slate-500">
                                            {{ $reservation->room->section->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $reservation->room->section->bibliotheque->name }}
                                        <span class="block text-xs text-slate-500">
                                            {{ $reservation->room->section->bibliotheque->city }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $reservation->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ date('H:i', strtotime($reservation->start_time)) }} - 
                                        {{ date('H:i', strtotime($reservation->end_time)) }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold">
                                        {{ number_format($reservation->total_price, 2) }}€
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($reservation->status == 'pending')
                                            <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                                <i class="fas fa-clock mr-1"></i>En attente
                                            </span>
                                        @elseif($reservation->status == 'confirmed')
                                            <span class="px-3 py-1 text-xs font-semibold text-emerald-800 bg-emerald-100 rounded-full">
                                                <i class="fas fa-check mr-1"></i>Confirmée
                                            </span>
                                        @else
                                            <span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                                <i class="fas fa-times mr-1"></i>Annulée
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($reservation->status != 'cancelled' && $reservation->isUpcoming())
                                            <form action="{{ route('room-reservations.cancel', $reservation) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation?')"
                                                        class="px-3 py-1.5 text-xs font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                                                        style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%)">
                                                    <i class="fas fa-ban mr-1"></i>Annuler
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $reservations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.base>

