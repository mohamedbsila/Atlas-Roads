<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Bibliotheque;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::with('bibliotheque');

        if ($request->has('bibliotheque_id')) {
            $query->where('bibliotheque_id', $request->bibliotheque_id);
        }

        $sections = $query->withCount('rooms')->paginate(15);
        $bibliotheques = Bibliotheque::all();

        return view('admin.sections.index', compact('sections', 'bibliotheques'));
    }

    public function create()
    {
        $bibliotheques = Bibliotheque::all();
        return view('admin.sections.create', compact('bibliotheques'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bibliotheque_id' => 'required|exists:bibliotheques,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Section::create($validated);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section créée avec succès!');
    }

    public function show(Section $section)
    {
        $section->load(['bibliotheque', 'rooms']);
        return view('admin.sections.show', compact('section'));
    }

    public function edit(Section $section)
    {
        $bibliotheques = Bibliotheque::all();
        return view('admin.sections.edit', compact('section', 'bibliotheques'));
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'bibliotheque_id' => 'required|exists:bibliotheques,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $section->update($validated);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section mise à jour avec succès!');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section supprimée avec succès!');
    }
}
