<x-layouts.app>
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">📋 Gestion des Réservations</h5>
                <p class="text-sm text-slate-400 mt-1">Confirmez ou annulez les réservations des utilisateurs</p>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-100 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        <!-- Filters -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <form action="{{ route('admin.reservations.index') }}" method="GET" class="flex gap-4">
                    <div>
                        <select name="status" class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmées</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulées</option>
                        </select>
                    </div>
                    <div>
                        <input type="date" name="date" value="{{ request('date') }}"
                               class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                    </div>
                    <button type="submit" class="px-4 py-2 text-sm font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                            style="background:linear-gradient(135deg,#06b6d4 0%,#0891b2 100%)">
                        <i class="fas fa-filter mr-1"></i>Filtrer
                    </button>
                </form>
            </div>
        </div>

        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-3">Utilisateur</th>
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
                            @forelse($reservations as $reservation)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <span class="font-medium">{{ $reservation->user->name }}</span>
                                    <span class="block text-xs text-slate-500">{{ $reservation->user->email }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium">{{ $reservation->room->name }}</span>
                                    <span class="block text-xs text-slate-500">{{ $reservation->room->section->name }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $reservation->room->section->bibliotheque->name }}</td>
                                <td class="px-6 py-4">{{ $reservation->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    {{ date('H:i', strtotime($reservation->start_time)) }} - 
                                    {{ date('H:i', strtotime($reservation->end_time)) }}
                                </td>
                                <td class="px-6 py-4 font-semibold">{{ number_format($reservation->total_price, 2) }}€</td>
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
                                    @if($reservation->status == 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.reservations.confirm', $reservation) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Confirmer cette réservation?')"
                                                        class="px-3 py-1.5 text-xs font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                                                        style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                                                    <i class="fas fa-check mr-1"></i>Confirmer
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Annuler cette réservation?')"
                                                        class="px-3 py-1.5 text-xs font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                                                        style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%)">
                                                    <i class="fas fa-times mr-1"></i>Annuler
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($reservation->status == 'confirmed')
                                        <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Annuler cette réservation?')"
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
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                    Aucune réservation trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $reservations->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

