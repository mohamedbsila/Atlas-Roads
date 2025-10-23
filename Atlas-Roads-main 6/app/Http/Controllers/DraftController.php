<?php

namespace App\Http\Controllers;

use App\Models\Draft;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
}

    

  
    public function create()
    {
        return view('drafts.create');
    }
public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'story_date' => 'nullable|date',
        'cover_image' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('cover_image')) {
        $data['cover_image'] = $request->file('cover_image')->store('drafts', 'public');
    }

    $data['user_id'] = auth()->id();
    Draft::create($data);

    // Message de succès et drapeau pour rester sur la page
    session()->flash('success', 'Draft enregistré avec succès !');
    session()->flash('showCreateAnother', true);

    return redirect()->back();
}


    public function show(Draft $draft)
    {
        abort_unless($draft->user_id === auth()->id(), 403);
        return view('drafts.show', compact('draft'));
    }

    public function edit(Draft $draft)
{
    // Sécurité : seul le propriétaire peut éditer
    abort_unless($draft->user_id === auth()->id(), 403);

    // Passe les infos au formulaire
    return view('drafts.edit', compact('draft'));
}

public function update(Request $request, Draft $draft)
{
    abort_unless($draft->user_id === auth()->id(), 403);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'story_date' => 'nullable|date',
        'cover_image' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('cover_image')) {
        $validated['cover_image'] = $request->file('cover_image')->store('drafts', 'public');
    }

    $draft->update($validated);

    return redirect()->route('drafts.index')->with('success', 'Brouillon mis à jour.');
}


    public function destroy(Draft $draft)
    {
        abort_unless($draft->user_id === auth()->id(), 403);
        $draft->delete();
        return redirect()->route('drafts.index')->with('success', 'Brouillon supprimé.');
    }

    public function index(Request $request)
{
    $query = Draft::where('user_id', auth()->id());

    // Recherche par titre
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // Filtrage par statut
    if ($request->filled('status')) {
        if ($request->status === 'published') {
            $query->where('is_published', true);
        } elseif ($request->status === 'draft') {
            $query->where('is_published', false);
        }
    }

    // Tri
    if ($request->filled('sort')) {
        if ($request->sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($request->sort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($request->sort === 'title_desc') {
            $query->orderBy('title', 'desc');
        }
    } else {
        $query->orderBy('created_at', 'desc'); // tri par défaut
    }

    $drafts = $query->paginate(10)->withQueryString();

    return view('drafts.index', compact('drafts'));
}


}


