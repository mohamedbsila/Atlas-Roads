@extends('layouts.app')

@section('content')
<main class="mt-0 transition-all duration-200 ease-soft-in-out">
    <section class="min-h-screen mb-32">
        <div class="container max-w-lg mx-auto">
            <div class="bg-white shadow-soft-xl rounded-2xl p-6">
                <h2 class="text-2xl font-bold mb-4 text-slate-700">Nouvelle Réclamation</h2>

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
                        <a href="{{ route('reclamations.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</a>
                        <button type="submit" class="px-4 py-2 bg-gradient-dark-gray text-white rounded hover:bg-slate-700">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection
