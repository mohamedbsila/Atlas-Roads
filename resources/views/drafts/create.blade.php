<x-layouts.app>
<div class="max-w-4xl mx-auto p-6">

  <!-- Header -->
  <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">
        {{ isset($draft) ? 'Modifier le brouillon' : 'Créer un nouveau brouillon' }}
      </h1>
      <p class="text-gray-500 mt-1">Racontez votre histoire et partagez-la avec le monde</p>
    </div>
    <a href="{{ route('drafts.index') }}" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center transition">
      <i class="fas fa-arrow-left mr-2"></i> Retour
    </a>
  </div>

  <!-- Success message -->
  @if(session('success'))
  <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center transition">
    <i class="fas fa-check-circle text-green-500 mr-2"></i>
    <span class="text-green-700">{{ session('success') }}</span>
  </div>
  @endif

  <!-- Form Card -->
  <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-100">
      <h2 class="text-xl font-semibold text-gray-900">Détails du brouillon</h2>
      <p class="mt-1 text-gray-500">Remplissez les informations de votre histoire</p>
    </div>

    <div class="p-8 space-y-6">
      <form method="POST" 
            action="{{ isset($draft) ? route('drafts.update', $draft) : route('drafts.store') }}" 
            enctype="multipart/form-data" 
            class="space-y-6">
        @csrf
        @if(isset($draft)) @method('PUT') @endif

        <!-- Title & Date -->
        <div class="grid md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Titre de l'histoire</label>
            <input type="text" name="title" placeholder="Titre de votre histoire"
              value="{{ old('title', $draft->title ?? '') }}"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date de l'histoire</label>
            <input type="date" name="story_date"
              value="{{ old('story_date', $draft->story_date ?? '') }}"
              class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
          </div>
        </div>

        <!-- Cover Image -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Image de couverture</label>
          <div id="cover-dropzone" class="border-2 border-dashed border-gray-300 rounded-xl p-6 flex flex-col items-center justify-center hover:border-blue-400 transition cursor-pointer group">
            <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-3 group-hover:text-blue-500 transition"></i>
            <p class="text-gray-500 text-sm">Glisser-déposer ou <span class="text-blue-600 font-medium">Télécharger</span></p>
            <input type="file" name="cover_image" class="hidden" accept="image/*">
            @if(isset($draft) && $draft->cover_image)
            <p class="text-xs text-gray-400 mt-1">Image actuelle : {{ $draft->cover_image }}</p>
            @else
            <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF jusqu'à 2MB</p>
            @endif
          </div>
        </div>

        <!-- Content -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Contenu de l'histoire</label>
          <textarea name="content" rows="12" placeholder="Commencez à écrire..." 
            class="w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none" required>{{ old('content', $draft->content ?? '') }}</textarea>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-4 pt-4">
          <a href="{{ route('drafts.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 transition-all">Annuler</a>
          <button type="submit"
            class="px-8 py-3 rounded-lg text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 flex items-center justify-center">
            <i class="fas fa-save mr-2"></i> {{ isset($draft) ? 'Mettre à jour' : 'Enregistrer' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>




<script>
  const dropzone = document.getElementById('cover-dropzone');
  const fileInput = dropzone.querySelector('input[type="file"]');

  dropzone.addEventListener('click', () => fileInput.click());

  fileInput.addEventListener('change', () => {
    if(fileInput.files.length > 0){
      dropzone.querySelector('p.text-gray-500').textContent = fileInput.files[0].name;
    }
  });
</script>
</x-layouts.app>
