<x-layouts.app>
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">🚪 Gestion des Salles</h5>
                <p class="text-sm text-slate-400 mt-1">Gérez les salles d'étude</p>
            </div>
            <a href="{{ route('admin.rooms.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
               style="background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%)">
                <i class="fas fa-plus"></i>
                Nouvelle Salle
            </a>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-100 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-3">Nom</th>
                                <th class="px-6 py-3">Section</th>
                                <th class="px-6 py-3">Type</th>
                                <th class="px-6 py-3">Capacité</th>
                                <th class="px-6 py-3">Équipements</th>
                                <th class="px-6 py-3">Prix/h</th>
                                <th class="px-6 py-3">Statut</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium">{{ $room->name }}</td>
                                <td class="px-6 py-4">
                                    {{ $room->section->name }}
                                    <span class="block text-xs text-slate-500">{{ $room->section->bibliotheque->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded
                                        {{ $room->style === 'individual' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $room->style === 'group' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $room->style === 'conference' ? 'bg-orange-100 text-orange-800' : '' }}">
                                        {{ ucfirst($room->style) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $room->capacity }} places</td>
                                <td class="px-6 py-4">
                                    @if($room->has_pc)<i class="fas fa-desktop text-blue-600" title="PC"></i>@endif
                                    @if($room->has_wifi)<i class="fas fa-wifi text-purple-600 ml-1" title="WiFi"></i>@endif
                                </td>
                                <td class="px-6 py-4 font-semibold">{{ $room->price_per_hour }}€</td>
                                <td class="px-6 py-4">
                                    @if($room->is_active)
                                        <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded text-xs">Actif</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Inactif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.rooms.show', $room) }}" class="text-cyan-600 hover:text-cyan-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.rooms.edit', $room) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Supprimer cette salle?')" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                    Aucune salle créée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

