<x-layouts.app>
    <div>
        @if(session('success'))
            <div class="mb-4 p-4 text-white rounded-xl flex items-center justify-between" 
                 style="background:linear-gradient(to right,#84cc16,#4ade80)">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="flex flex-wrap justify-between items-center mb-6">
            <div>
                <h5 class="mb-0 font-bold">Gestion des Réclamations</h5>
                <p class="text-size-sm text-slate-400">Gérer les réclamations</p>
            </div>
            <a href="{{ route('reclamations.create') }}" 
               class="inline-block px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-size-xs shadow-md hover:scale-105 transition-all"
               style="background:linear-gradient(to right,#d946ef,#ec4899)">
                <i class="ni ni-fat-add mr-2"></i> Nouvelle Réclamation
            </a>
        </div>

        <!-- Liste des réclamations (User Section) -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <h6 class="mb-4 font-bold">Liste des Réclamations</h6>
                <div class="overflow-x-auto">
                    <table class="w-full text-left table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">ID</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Titre</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Description</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Catégorie</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Priorité</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Statut</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Utilisateur</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reclamations as $reclamation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 border-b">{{ $reclamation->id }}</td>
                                <td class="px-4 py-3 border-b font-semibold">{{ $reclamation->titre }}</td>
                                <td class="px-4 py-3 border-b text-size-sm" title="{{ $reclamation->description }}">
                                    {{ Str::limit($reclamation->description, 40) }}
                                </td>
                                <td class="px-4 py-3 border-b">
                                    <span class="px-3 py-1 rounded-lg font-semibold text-size-xs bg-green-100 text-green-700">
                                        {{ ucfirst($reclamation->categorie) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-b">
                                    <span class="px-3 py-1 rounded-lg font-semibold text-size-xs
                                        {{ $reclamation->priorite == 'haute' ? 'bg-red-100 text-red-700' : ($reclamation->priorite == 'moyenne' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                        {{ ucfirst($reclamation->priorite) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-b">
                                    <span class="px-3 py-1 rounded-lg font-semibold text-size-xs
                                        {{ $reclamation->statut == 'resolue' ? 'bg-green-100 text-green-700' : ($reclamation->statut == 'en_cours' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                        {{ ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-b">{{ $reclamation->user->name }}</td>
                                <td class="px-4 py-3 border-b">
                                    <div class="flex gap-2">
                                        <a href="{{ route('reclamations.show', $reclamation) }}" 
                                           class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-size-xs font-semibold">
                                           Voir
                                        </a>
                                        <a href="{{ route('reclamations.edit', $reclamation) }}" 
                                           class="px-3 py-1 rounded-lg text-white text-size-xs font-semibold
                                                  {{ $reclamation->statut === 'en_attente' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-400 cursor-not-allowed' }}"
                                           {{ $reclamation->statut !== 'en_attente' ? 'aria-disabled=true tabindex=-1' : '' }}>
                                           Modifier
                                        </a>
                                        <form action="{{ route('reclamations.destroy', $reclamation) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1 rounded-lg text-white text-size-xs font-semibold
                                                           {{ $reclamation->statut === 'en_attente' ? 'bg-red-500 hover:bg-red-600' : 'bg-gray-400 cursor-not-allowed' }}"
                                                    @if($reclamation->statut === 'en_attente')
                                                        onclick="return confirm('Supprimer cette réclamation ?');"
                                                    @else
                                                        onclick="return false;"
                                                    @endif>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-slate-400">
                                    <i class="ni ni-chat-round text-6xl mb-4"></i>
                                    <p>Aucune réclamation trouvée</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section Admin -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <h6 class="mb-4 font-bold">Gestion Admin - Réclamations</h6>
                <div class="overflow-x-auto">
                    <table class="w-full text-left table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Utilisateur</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Description</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Catégorie</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Statut</th>
                                <th class="px-4 py-3 text-size-xs font-bold text-slate-700 uppercase border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reclamations as $reclamation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 border-b">{{ $reclamation->user->name }}</td>
                                <td class="px-4 py-3 border-b text-size-sm" title="{{ $reclamation->description }}">
                                    {{ Str::limit($reclamation->description, 40) }}
                                </td>
                                <td class="px-4 py-3 border-b">
                                    <span class="px-3 py-1 rounded-lg font-semibold text-size-xs bg-green-100 text-green-700">
                                        {{ ucfirst($reclamation->categorie) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-b">
                                    <span class="px-3 py-1 rounded-lg font-semibold text-size-xs
                                        {{ $reclamation->statut == 'resolue' ? 'bg-green-100 text-green-700' : ($reclamation->statut == 'en_cours' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                        {{ ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-b">
                                    <div class="flex gap-2">
                                        <form action="{{ route('reclamations.bienRecu', $reclamation) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-size-xs font-semibold">
                                                Bien reçu
                                            </button>
                                        </form>
                                        <button class="px-3 py-1 bg-gray-400 text-white rounded-lg cursor-not-allowed text-size-xs font-semibold" disabled>
                                            Générer une solution
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                    <i class="ni ni-chat-round text-6xl mb-4"></i>
                                    <p>Aucune réclamation trouvée</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $reclamations->links() }}
        </div>
    </div>
</x-layouts.app>
