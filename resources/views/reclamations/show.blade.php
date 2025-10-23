<<<<<<< HEAD
<x-layouts.base>
<style>
    .gradient-text {
        background: linear-gradient(135deg, #d946ef, #ec4899, #f97316);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .shimmer-effect {
        background: linear-gradient(90deg, 
            rgba(255,255,255,0) 0%, 
            rgba(255,255,255,0.5) 50%, 
            rgba(255,255,255,0) 100%);
        background-size: 1000px 100%;
        animation: shimmer 2s infinite;
    }

    .floating-icon {
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
</style>

<div class="p-6 bg-white rounded shadow">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-4">
        <div>
            <h5 class="text-lg font-bold">📋 Reclamation Details</h5>
            <p class="text-sm text-gray-500 mt-2 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                View and manage this reclamation
            </p>
        </div>
    </div>

    <!-- Reclamation Card -->
    <div class="border rounded px-3 py-2">
        <!-- Decorative gradient header -->
        <div class="absolute top-0 left-0 right-0 h-2" style="background:linear-gradient(90deg,#d946ef,#ec4899,#f97316,#06b6d4,#3b82f6)"></div>
        
        <div class="border-b border-gray-200 px-3 py-2 relative">
            <div class="absolute right-8 top-6 floating-icon text-5xl opacity-10">
                📋
            </div>
            <h5 class="text-base font-semibold mb-2">{{ $reclamation->titre }}</h5>
            
            <!-- Status Badge -->
            @php
                $statusStyles = [
                    'en_attente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    'en_cours' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'traitée' => 'bg-green-100 text-green-800 border-green-200',
                    'resolue' => 'bg-green-100 text-green-800 border-green-200',
                    'rejetee' => 'bg-red-100 text-red-800 border-red-200'
                ];
                $statusIcons = [
                    'en_attente' => '⏳',
                    'en_cours' => '🔄',
                    'traitée' => '✅',
                    'resolue' => '✅',
                    'rejetee' => '❌'
                ];
            @endphp
            <div class="inline-block mt-2">
                <span class="px-2 py-1 text-sm rounded border {{ $statusStyles[$reclamation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusIcons[$reclamation->statut] ?? '' }} {{ ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                </span>
            </div>
        </div>

        <div class="px-3 py-2">
            <!-- Reclamation Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-4">
                    <div class="border rounded px-3 py-2">
                        <p class="text-sm text-gray-500 mb-1 flex items-center">
                            <i class="fas fa-user-circle text-purple-500 mr-2"></i> Created By
                        </p>
                        <p class="text-sm">{{ $reclamation->user->name }}</p>
                    </div>

                    <div class="border rounded px-3 py-2">
                        <p class="text-sm text-gray-500 mb-1 flex items-center">
                            <i class="fas fa-calendar-alt text-blue-500 mr-2"></i> Created At
                        </p>
                        <p class="text-sm">{{ $reclamation->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>

                    <div class="border rounded px-3 py-2">
                        <p class="text-sm text-gray-500 mb-1 flex items-center">
                            <i class="fas fa-flag text-orange-500 mr-2"></i> Priority
                        </p>
                        @php
                            $priorityStyles = [
                                'haute' => 'bg-red-100 text-red-800 border-red-200',
                                'moyenne' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'basse' => 'bg-green-100 text-green-800 border-green-200'
                            ];
                            $priorityIcons = [
                                'haute' => '🔥 High',
                                'moyenne' => '⭐ Medium',
                                'basse' => '✨ Low'
                            ];
                        @endphp
                        <span class="inline-block px-2 py-1 text-sm rounded border {{ $priorityStyles[$reclamation->priorite] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $priorityIcons[$reclamation->priorite] ?? ucfirst($reclamation->priorite) }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="border rounded px-3 py-2">
                        <p class="text-sm text-gray-500 mb-1 flex items-center">
                            <i class="fas fa-folder text-indigo-500 mr-2"></i> Category
                        </p>
                        <p class="text-sm capitalize">{{ $reclamation->categorie }}</p>
                    </div>

                    <div class="border rounded px-3 py-2">
                        <p class="text-sm text-gray-500 mb-1 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Last Updated
                        </p>
                        <p class="text-sm">{{ $reclamation->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-4">
                <h6 class="text-sm text-gray-500 mb-2 flex items-center">
                    <i class="fas fa-align-left text-pink-500 mr-2"></i>
                    Description
                </h6>
                <div class="w-full border rounded px-3 py-2">
                    <p class="text-sm">
                        {{ $reclamation->description }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            
        </div>
    </div>

    <!-- Section des solutions -->
    <div class="border rounded px-3 py-2 mt-4">
        <div class="border-b border-gray-200 px-3 py-2">
            <h5 class="text-base font-semibold">
                <i class="fas fa-lightbulb mr-2"></i>
                Solution
            </h5>
        </div>

        <div class="px-3 py-2">
            @if($reclamation->solution)
                <!-- Afficher la solution existante -->
                <div class="border rounded px-3 py-2">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-sm whitespace-pre-line">{{ $reclamation->solution->contenu }}</p>
                            <div class="mt-2 text-sm text-gray-500">
                                <i class="far fa-user-circle mr-1"></i>
                                {{ $reclamation->solution->admin->name }}
                                <span class="mx-2">•</span>
                                <i class="far fa-clock mr-1"></i>
                                {{ $reclamation->solution->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        @can('update', $reclamation->solution)
                            <div class="flex space-x-2">
                                <a href="{{ route('solutions.edit', [$reclamation, $reclamation->solution]) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('solutions.destroy', [$reclamation, $reclamation->solution]) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette solution ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            @else
                @can('create', App\Models\Solution::class)
                    <form action="{{ route('solutions.store', $reclamation) }}" method="POST" class="space-y-4" id="solutionForm">
                        @csrf
                        <div>
                            <label class="block mb-2 text-sm text-gray-500 flex items-center">
                                <i class="fas fa-robot mr-2 text-purple-500"></i>
                                Solution générée par IA (adaptée à votre réclamation)
                            </label>
                            <textarea name="contenu" id="aiSolutionTextarea" class="w-full border rounded px-3 py-2 text-sm text-gray-700" rows="8">{{ $aiSolution }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Cette solution a été générée automatiquement en analysant le contenu de votre réclamation.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center">
                                <i class="fas fa-check mr-2"></i> Confirmer cette solution
                            </button>
                            <button type="button" id="regenerateBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center">
                                <i class="fas fa-sync-alt mr-2"></i> <span id="regenerateText">Regénérer une solution</span>
                            </button>
                            <a href="{{ route('solutions.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 flex items-center">
                                <i class="fas fa-times mr-2"></i> Annuler
                            </a>
                        </div>
                    </form>
                @else
                    <div class="text-center py-4">
                        <div class="text-gray-500 text-4xl mb-3">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <p class="text-gray-500 text-sm mb-4">Aucune solution n'a encore été ajoutée pour cette réclamation.</p>
                        <a href="{{ route('solutions.create', $reclamation) }}" 
                           class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i> Ajouter une solution
                        </a>
                    </div>
                @endcan
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regenerateBtn = document.getElementById('regenerateBtn');
    const solutionTextarea = document.getElementById('aiSolutionTextarea');
    const regenerateText = document.getElementById('regenerateText');
    
    if (regenerateBtn && solutionTextarea) {
        regenerateBtn.addEventListener('click', function() {
            // État de chargement
            regenerateText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Génération...';
            regenerateBtn.disabled = true;
            regenerateBtn.classList.add('opacity-50', 'cursor-not-allowed');
            solutionTextarea.value = '🤖 Génération d\'une nouvelle solution IA en cours...\n\nVeuillez patienter quelques secondes.';
            
            // Appel AJAX
            fetch(`/reclamations/{{ $reclamation->id }}/regenerate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.solution) {
                    solutionTextarea.value = data.solution;
                    
                    // Animation de succès
                    if (data.fallback) {
                        solutionTextarea.classList.add('border-yellow-500');
                        // Afficher un message d'info pour la solution de secours
                        const infoDiv = document.createElement('div');
                        infoDiv.className = 'mt-2 p-2 bg-yellow-100 border border-yellow-300 rounded text-sm text-yellow-800';
                        infoDiv.innerHTML = '<i class="fas fa-info-circle mr-1"></i> ' + (data.message || 'Solution générée automatiquement');
                        solutionTextarea.parentNode.appendChild(infoDiv);
                        setTimeout(() => {
                            infoDiv.remove();
                        }, 5000);
                    } else {
                        solutionTextarea.classList.add('border-green-500');
                    }
                    
                    setTimeout(() => {
                        solutionTextarea.classList.remove('border-green-500', 'border-yellow-500');
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Réponse invalide du serveur');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la régénération:', error);
                
                // Messages d'erreur spécifiques
                let errorMessage = '❌ Erreur lors de la génération de la solution.\n\n';
                
                if (error.message.includes('HTTP: 500')) {
                    errorMessage += 'Le serveur rencontre un problème temporaire. Veuillez réessayer dans quelques minutes.';
                } else if (error.message.includes('HTTP: 401')) {
                    errorMessage += 'Session expirée. Veuillez vous reconnecter.';
                } else if (error.message.includes('HTTP: 403')) {
                    errorMessage += 'Vous n\'avez pas les permissions nécessaires.';
                } else if (error.message.includes('fetch')) {
                    errorMessage += 'Problème de connexion. Vérifiez votre connexion internet.';
                } else {
                    errorMessage += 'Une erreur inattendue s\'est produite. Veuillez réessayer.';
                }
                
                solutionTextarea.value = errorMessage;
                solutionTextarea.classList.add('border-red-500');
                setTimeout(() => {
                    solutionTextarea.classList.remove('border-red-500');
                }, 3000);
            })
            .finally(() => {
                // Restaurer l'état du bouton
                regenerateText.innerHTML = '<i class="fas fa-sync-alt mr-2"></i> Regénérer une solution';
                regenerateBtn.disabled = false;
                regenerateBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        });
    }
});
</script>
@endpush
@push('scripts')
<script>
    // Scripts supplémentaires si nécessaire
</script>
@endpush
</x-layouts.base>
=======
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
>>>>>>> origin/complet
