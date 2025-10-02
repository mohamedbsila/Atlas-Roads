@extends('layouts.app')

@section('content')
<main class="mt-0 transition-all duration-200 ease-soft-in-out">
    <section class="min-h-screen mb-32">
        <div class="container max-w-lg mx-auto">
            <div class="bg-white shadow-soft-xl rounded-2xl p-6">
                <h2 class="text-2xl font-bold mb-4 text-slate-700">Modifier Réclamation</h2>

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
                        <a href="{{ route('reclamations.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</a>
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
