<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    public function index()
    {
        $reclamations = Reclamation::with('user')->latest()->paginate(10);
        return view('reclamations.index', compact('reclamations'));
    }

    public function create()
    {
        return view('reclamations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required',
            'categorie' => 'required|string',
            'priorite' => 'required|in:basse,moyenne,haute',
        ]);

        Reclamation::create([
            'user_id' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'categorie' => $request->categorie,
            'priorite' => $request->priorite,
        ]);

        return redirect()->route('reclamations.index')->with('success', 'Réclamation ajoutée avec succès');
    }

    public function show(Reclamation $reclamation)
    {
        return view('reclamations.show', compact('reclamation'));
    }

    public function edit(Reclamation $reclamation)
    {
        return view('reclamations.edit', compact('reclamation'));
    }

    public function update(Request $request, Reclamation $reclamation)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required',
            'categorie' => 'required|string',
            'priorite' => 'required|in:basse,moyenne,haute',
            'statut' => 'required|in:en_attente,en_cours,resolue',
        ]);

        $reclamation->update($request->all());

        return redirect()->route('reclamations.index')->with('success', 'Réclamation mise à jour');
    }

    public function destroy(Reclamation $reclamation)
    {
        $reclamation->delete();
        return redirect()->route('reclamations.index')->with('success', 'Réclamation supprimée');
    }
}
