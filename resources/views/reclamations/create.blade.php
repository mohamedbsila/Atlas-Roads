<x-layouts.base>
    <x-client-navbar />
    
    <div class="container mx-auto px-4 py-8 mt-4">
        <div class="mb-6">
            <a href="{{ route('reclamations.index') }}" 
               class="inline-block px-4 py-2 mb-4 text-sm font-bold text-slate-400 hover:text-slate-700">
                <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
            </a>
            <h5 class="mb-0 font-bold text-slate-700 text-xl">📝 Nouvelle Réclamation</h5>
            <p class="text-sm text-slate-400 mt-1">Signalez un problème et obtenez de l'aide</p>
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

                <form action="{{ route('reclamations.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Titre</label>
                        <input type="text" name="titre" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Description</label>
                        <textarea name="description" rows="4" class="w-full px-3 py-2 border rounded" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Catégorie</label>
                        <input type="text" name="categorie" class="w-full px-3 py-2 border rounded" required>
                    </div>

                    <div class="mb-3">
                        <label class="block mb-1 font-semibold">Priorité</label>
                        <select name="priorite" class="w-full px-3 py-2 border rounded" required>
                            <option value="basse">Basse</option>
                            <option value="moyenne" selected>Moyenne</option>
                            <option value="haute">Haute</option>
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
                            Ajouter
                        </button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.base>
