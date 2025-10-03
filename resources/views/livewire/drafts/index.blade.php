<div class="w-full">
  <!-- Search and filters -->
  <div class="px-8 py-6 border-b border-gray-100">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
      <div class="flex-1 max-w-lg">
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
          <input type="text" wire:model.debounce.400ms="search" placeholder="Rechercher dans vos brouillons..." class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
        </div>
      </div>
      
      <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
        <div class="flex items-center space-x-2">
          <label class="text-sm font-medium text-gray-700">Du</label>
          <input type="date" wire:model="filterDateFrom" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
        </div>
        <div class="flex items-center space-x-2">
          <label class="text-sm font-medium text-gray-700">Au</label>
          <input type="date" wire:model="filterDateTo" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
        </div>
      </div>
    </div>
  </div>

  <!-- Success message -->
  @if(session('success'))
    <div class="mx-8 mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
      </div>
    </div>
  @endif

  <!-- Drafts grid -->
  <div class="p-8">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
      @forelse($drafts as $draft)
        <div class="group bg-white border border-gray-200 rounded-xl hover:shadow-lg transition-all duration-300 hover:border-blue-300 overflow-hidden">
          <!-- Cover image -->
          @if($draft->cover_image)
            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
              <img src="{{ asset('storage/'.$draft->cover_image) }}" alt="{{ $draft->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" />
            </div>
          @else
            <div class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center">
              <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
            </div>
          @endif
          
          <!-- Content -->
          <div class="p-6">
            <div class="flex items-start justify-between mb-3">
              <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors">
                <a href="{{ route('drafts.show', $draft) }}" class="hover:underline">{{ $draft->title }}</a>
              </h3>
              <div class="flex items-center space-x-1 ml-2">
                @if($draft->is_published)
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Publié
                  </span>
                @else
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    Brouillon
                  </span>
                @endif
              </div>
            </div>
            
            <p class="text-sm text-gray-600 mb-3">{{ optional($draft->story_date)->format('d/m/Y') }}</p>
            <p class="text-gray-700 text-sm line-clamp-3 mb-4">{{ Str::limit(strip_tags($draft->content), 120) }}</p>
            
            <!-- Actions -->
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <a href="{{ route('drafts.edit', $draft) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                  Modifier
                </a>
                <button wire:click="deleteDraft({{ $draft->id }})" onclick="return confirm('Supprimer ce brouillon ?')" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                  Supprimer
                </button>
              </div>
              <span class="text-xs text-gray-500">{{ $draft->created_at->diffForHumans() }}</span>
            </div>
          </div>
        </div>
      @empty
        <div class="col-span-full flex flex-col items-center justify-center py-12">
          <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun brouillon trouvé</h3>
          <p class="text-gray-600 text-center mb-6">Commencez par créer votre premier brouillon d'histoire.</p>
          <a href="{{ route('drafts.create') }}" class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Créer un brouillon
          </a>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Pagination and sorting -->
  @if($drafts->count() > 0)
    <div class="px-8 py-6 border-t border-gray-100 bg-gray-50">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <span class="text-sm font-medium text-gray-700">Trier par:</span>
            <button wire:click="sortBy('title')" class="text-sm text-gray-600 hover:text-blue-600 transition-colors {{ $sortField === 'title' ? 'text-blue-600 font-medium' : '' }}">
              Titre
            </button>
            <span class="text-gray-300">•</span>
            <button wire:click="sortBy('story_date')" class="text-sm text-gray-600 hover:text-blue-600 transition-colors {{ $sortField === 'story_date' ? 'text-blue-600 font-medium' : '' }}">
              Date histoire
            </button>
            <span class="text-gray-300">•</span>
            <button wire:click="sortBy('created_at')" class="text-sm text-gray-600 hover:text-blue-600 transition-colors {{ $sortField === 'created_at' ? 'text-blue-600 font-medium' : '' }}">
              Créé
            </button>
          </div>
        </div>
        <div class="text-sm text-gray-600">
          {{ $drafts->total() }} résultat{{ $drafts->total() > 1 ? 's' : '' }}
        </div>
</div>

      <div class="mt-4">
        {{ $drafts->links() }}
      </div>
    </div>
  @endif
</div>