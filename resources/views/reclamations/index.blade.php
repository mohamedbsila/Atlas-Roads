@extends('layouts.app')

@section('content')
<main class="mt-0 transition-all duration-200 ease-soft-in-out">
    <section class="min-h-screen mb-32">
        <div class="container">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-slate-700">Liste des Réclamations</h1>
                <a href="{{ route('reclamations.create') }}" class="inline-block px-6 py-3 text-white bg-gradient-dark-gray hover:bg-slate-700 rounded-lg shadow-soft-md">Nouvelle Réclamation</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left table-auto border-collapse border border-slate-200">
                    <thead class="bg-gradient-dark-gray text-white">
                        <tr>
                            <th class="px-4 py-2 border border-slate-300">ID</th>
                            <th class="px-4 py-2 border border-slate-300">Titre</th>
                            <th class="px-4 py-2 border border-slate-300">Catégorie</th>
                            <th class="px-4 py-2 border border-slate-300">Priorité</th>
                            <th class="px-4 py-2 border border-slate-300">Statut</th>
                            <th class="px-4 py-2 border border-slate-300">Utilisateur</th>
                            <th class="px-4 py-2 border border-slate-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($reclamations as $reclamation)
                        <tr class="hover:bg-gray-100 transition-colors">
                            <td class="px-4 py-2 border border-slate-300">{{ $reclamation->id }}</td>
                            <td class="px-4 py-2 border border-slate-300">{{ $reclamation->titre }}</td>
                            <td class="px-4 py-2 border border-slate-300">{{ $reclamation->categorie }}</td>
                            <td class="px-4 py-2 border border-slate-300">
                                <span class="px-2 py-1 rounded-lg font-semibold 
                                    {{ $reclamation->priorite == 'haute' ? 'bg-red-100 text-red-700' : ($reclamation->priorite == 'moyenne' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                    {{ ucfirst($reclamation->priorite) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border border-slate-300">
                                <span class="px-2 py-1 rounded-lg font-semibold
                                    {{ $reclamation->statut == 'resolue' ? 'bg-green-100 text-green-700' : ($reclamation->statut == 'en_cours' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                    {{ ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border border-slate-300">{{ $reclamation->user->name }}</td>
                            <td class="px-4 py-2 border border-slate-300 flex gap-2">
                                <a href="{{ route('reclamations.show', $reclamation) }}" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Voir</a>
                                <a href="{{ route('reclamations.edit', $reclamation) }}" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Modifier</a>
                                <form action="{{ route('reclamations.destroy', $reclamation) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('Supprimer ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $reclamations->links() }}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
