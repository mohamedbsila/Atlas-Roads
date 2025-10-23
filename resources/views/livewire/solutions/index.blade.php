<div class="p-6 bg-white rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Réclamations en attente de réponse</h3>
            <p class="text-sm text-gray-500 mt-1">Liste des réclamations non traitées ou en cours de traitement</p>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div class="w-1/3">
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Rechercher une solution..." 
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            <div class="flex items-center space-x-4">
                <select wire:model.live="perPage" class="px-4 py-2 border rounded-lg">
                    <option value="5">5 par page</option>
                    <option value="10">10 par page</option>
                    <option value="25">25 par page</option>
                    <option value="50">50 par page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Solutions Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('id')">
                        ID
                        @if($sortField === 'id')
                            @if($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('reclamation.titre')">
                        Réclamation
                        @if($sortField === 'reclamation.titre')
                            @if($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Demandeur
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('created_at')">
                        Date de création
                        @if($sortField === 'created_at')
                            @if($sortDirection === 'asc')
                                ↑
                            @else
                                ↓
                            @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reclamations as $reclamation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $reclamation->id }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('reclamations.show', $reclamation) }}" class="text-blue-600 hover:text-blue-800">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $reclamation->titre }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ Str::limit($reclamation->description, 100) }}
                                </div>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $reclamation->user->name }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $reclamation->user->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                    'en_cours' => 'bg-blue-100 text-blue-800',
                                    'resolue' => 'bg-green-100 text-green-800',
                                    'fermee' => 'bg-gray-100 text-gray-800'
                                ];
                                $statusTexts = [
                                    'en_attente' => 'En attente',
                                    'en_cours' => 'En cours',
                                    'resolue' => 'Résolue',
                                    'fermee' => 'Fermée'
                                ];
                                $color = $statusColors[$reclamation->statut] ?? 'bg-gray-100 text-gray-800';
                                $text = $statusTexts[$reclamation->statut] ?? 'Inconnu';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                {{ $text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $reclamation->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('reclamations.show', $reclamation) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-reply mr-1"></i> Répondre
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 text-sm">Aucune réclamation en attente de réponse</p>
                                <p class="text-gray-400 text-xs mt-1">Toutes les réclamations ont été traitées</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $reclamations->links() }}
    </div>
</div>

@push('styles')
<style>
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
    .page-item {
        margin: 0 0.25rem;
    }
    .page-link {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.25rem;
        color: #4a5568;
    }
    .page-item.active .page-link {
        background-color: #4299e1;
        border-color: #4299e1;
        color: white;
    }
    .page-link:hover {
        background-color: #ebf8ff;
    }
</style>
@endpush
