<x-layouts.app>
  <div class="w-full px-6 py-6 mx-auto max-w-4xl">
    <!-- Header with breadcrumb -->
    <div class="mb-8">
      <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
          <li class="inline-flex items-center">
            <a href="{{ route('drafts.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
              <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
              </svg>
              Brouillons
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
              </svg>
              <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $draft->title }}</span>
            </div>
          </li>
        </ol>
      </nav>
      
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">{{ $draft->title }}</h1>
          <div class="mt-2 flex items-center space-x-4">
            <p class="text-gray-600">{{ optional($draft->story_date)->format('d/m/Y') }}</p>
            @if($draft->is_published)
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Publié
              </span>
            @else
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                Brouillon
              </span>
            @endif
          </div>
        </div>
        <div class="flex items-center space-x-3">
          <a href="{{ route('drafts.edit', $draft) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg hover:from-blue-700 hover:to-purple-700">
            Modifier
          </a>
          <a href="{{ route('drafts.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            Retour
          </a>
        </div>
      </div>
    </div>

    <!-- Draft content card -->
    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden mb-6">
      @if($draft->cover_image)
        <img src="{{ asset('storage/'.$draft->cover_image) }}" alt="{{ $draft->title }}" class="w-full h-64 object-cover rounded-t-xl shadow-md">
      @endif

      <div class="p-8">
        <div class="prose max-w-none">
          <div class="whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $draft->content }}</div>
        </div>

        <!-- Meta information -->
        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between text-sm text-gray-500">
          <span>Créé {{ $draft->created_at->diffForHumans() }}</span>
          @if($draft->updated_at != $draft->created_at)
            <span>Modifié {{ $draft->updated_at->diffForHumans() }}</span>
          @endif
          <span>{{ strlen($draft->content) }} caractères</span>
        </div>
      </div>
    </div>

    <!-- Reviews section -->
    <div class="bg-white shadow-xl rounded-2xl border border-gray-100 p-6">
      <h3 class="text-lg font-bold mb-4">Commentaires</h3>

      @forelse($draft->reviews as $review)
        <div class="p-4 mb-2 border rounded-lg">
          <p>{{ $review->content }}</p>
          <span class="text-sm text-gray-500">Par {{ $review->user->name }} le {{ $review->created_at->format('d/m/Y') }}</span>
        </div>
      @empty
        <p class="text-gray-500">Aucun commentaire pour ce brouillon.</p>
      @endforelse
    </div>
  </div>
</x-layouts.app>
