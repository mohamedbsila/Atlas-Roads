<x-layouts.app>
    <div>
        <div class="mb-6">
            <a href="{{ route('reclamations.index') }}" 
               class="inline-block px-4 py-2 mb-4 text-size-xs font-bold text-slate-400 hover:text-slate-700">
                <i class="ni ni-bold-left mr-1"></i> Retour à la liste
            </a>
            <h5 class="mb-0 font-bold">Détails de la Réclamation</h5>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="w-full lg:w-8/12 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-bold text-lg">{{ $reclamation->titre }}</h6>

                <div class="space-y-3 text-slate-700">
                    <p><strong>Description :</strong> {{ $reclamation->description }}</p>
                    <p><strong>Catégorie :</strong> {{ $reclamation->categorie }}</p>
                    <p><strong>Priorité :</strong> {{ ucfirst($reclamation->priorite) }}</p>
                    <p><strong>Statut :</strong> {{ ucfirst(str_replace('_',' ', $reclamation->statut)) }}</p>
                    <p><strong>Utilisateur :</strong> {{ $reclamation->user->name }}</p>
                    <p><strong>Créée le :</strong> {{ $reclamation->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <div class="flex gap-2">
                        <a href="{{ route('reclamations.edit', $reclamation) }}" 
                           class="px-6 py-3 rounded-lg text-white font-bold text-size-xs
                                  {{ $reclamation->statut === 'en_attente' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-400 cursor-not-allowed' }}"
                           {{ $reclamation->statut !== 'en_attente' ? 'aria-disabled=true tabindex=-1' : '' }}>
                           Modifier
                        </a>

                        <form action="{{ route('reclamations.destroy', $reclamation) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="px-6 py-3 rounded-lg text-white font-bold text-size-xs
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
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
