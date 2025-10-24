<x-layouts.base>
    <x-client-navbar />
    
    <div class="container mx-auto px-4 py-8 mt-4">
        <div class="mb-6">
            <a href="{{ route('reclamations.index') }}" 
               class="inline-block px-4 py-2 mb-4 text-sm font-bold text-slate-400 hover:text-slate-700">
                <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
            </a>
            <h5 class="mb-0 font-bold text-slate-700 text-xl">✏️ Modifier Réclamation</h5>
            <p class="text-sm text-slate-400 mt-1">Mettez à jour votre réclamation</p>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="w-full lg:w-8/12 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reclamations.update', $reclamation) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Titre</label>
                        <input type="text" name="titre" value="{{ $reclamation->titre }}" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Description</label>
                        <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded" required>{{ $reclamation->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Catégorie</label>
                        <input type="text" name="categorie" value="{{ $reclamation->categorie }}" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Priorité</label>
                        <select name="priorite" class="w-full px-3 py-2 border rounded" required>
                            <option value="basse" {{ $reclamation->priorite=='basse'?'selected':'' }}>Basse</option>
                            <option value="moyenne" {{ $reclamation->priorite=='moyenne'?'selected':'' }}>Moyenne</option>
                            <option value="haute" {{ $reclamation->priorite=='haute'?'selected':'' }}>Haute</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Statut</label>
                        <select name="statut" class="w-full px-3 py-2 border rounded" required>
                            <option value="en_attente" {{ $reclamation->statut=='en_attente'?'selected':'' }}>En attente</option>
                            <option value="en_cours" {{ $reclamation->statut=='en_cours'?'selected':'' }}>En cours</option>
                            <option value="resolue" {{ $reclamation->statut=='resolue'?'selected':'' }}>Résolue</option>
                        </select>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('reclamations.index') }}" 
                           class="inline-block px-6 py-3 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-transparent border border-slate-700 rounded-lg cursor-pointer hover:bg-slate-700 hover:text-white text-size-xs">
                            Annuler
                        </a>
                        <button type="submit" 
                                class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer text-size-xs shadow-md hover:scale-105"
                                style="background:linear-gradient(to right,#d946ef,#ec4899)">
                            Mettre à jour
                        </button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.base>
