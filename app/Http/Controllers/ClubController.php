<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClubController extends Controller
{
    public function index(): View
    {
        $clubs = Club::withCount('meetings')->latest()->paginate(12);
        return view('clubs.index', compact('clubs'));
    }

    public function create(): View
    {
        return view('clubs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'location' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('clubs', 'public');
        }

        $club = Club::create($validated);

        return redirect()->route('clubs.show', $club)->with('status', 'Club created successfully');
    }

    public function show(Club $club): View
    {
        $club->load(['meetings' => function ($q) {
            $q->orderBy('scheduled_at', 'desc');
        }]);
        return view('clubs.show', compact('club'));
    }

    public function showPublic(Club $club): View
    {
        $club->load(['meetings' => function ($q) {
            $q->orderBy('scheduled_at', 'desc');
        }]);
        return view('clubs.show-public', compact('club'));
    }

    public function edit(Club $club): View
    {
        return view('clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($club->image) {
                \Storage::disk('public')->delete($club->image);
            }
            $validated['image'] = $request->file('image')->store('clubs', 'public');
        }

        $club->update($validated);

        return redirect()->route('clubs.show', $club)->with('status', 'Club updated successfully');
    }

    public function destroy(Club $club): RedirectResponse
    {
        // Delete image if exists
        if ($club->image) {
            \Storage::disk('public')->delete($club->image);
        }
        $club->delete();
        return redirect()->route('clubs.index')->with('status', 'Club deleted successfully');
    }
}


