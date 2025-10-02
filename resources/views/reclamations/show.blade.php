@extends('layouts.app')

@section('content')
<main class="mt-0 transition-all duration-200 ease-soft-in-out">
    <section class="min-h-screen mb-32">
        <div class="container max-w-xl mx-auto">
            <div class="bg-white shadow-soft-xl rounded-2xl p-6">
                <h2 class="text-2xl font-bold mb-4 text-slate-700">{{ $reclamation->titre }}</h2>

                <div class="space-y-3 text-slate-700">
                    <p><strong>Description :</strong> {{ $reclamation->description }}</p>
                    <p><strong>Catégorie :</strong> {{ $reclamation->categorie }}</p>
                    <p><strong>Priorité :</strong> {{ ucfirst($reclamation->priorite) }}</p>
                    <p><strong>Statut :</strong> {{ ucfirst(str_replace('_',' ', $reclamation->statut)) }}</p>
                    <p><strong>Utilisateur :</strong> {{ $reclamation->user->name }}</p>
                    <p><strong>Créée le :</strong> {{ $reclamation->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <a href="{{ route('reclamations.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Retour</a>
                    <div class="flex gap-2">
                        <a href="{{ route('reclamations.edit', $reclamation) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Modifier</a>
                        <form action="{{ route('reclamations.destroy', $reclamation) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('Supprimer ?')">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
