<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Meeting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MeetingController extends Controller
{
    public function index(): View
    {
        $meetings = Meeting::with('club')
            ->orderBy('scheduled_at', 'desc')
            ->paginate(12);
        return view('meetings.index', compact('meetings'));
    }

    public function show(Meeting $meeting): View
    {
        $meeting->load('club');
        return view('meetings.show', compact('meeting'));
    }

    public function create(Club $club): View
    {
        return view('meetings.create', compact('club'));
    }

    public function store(Request $request, Club $club): RedirectResponse
    {
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'min:3', 'max:255'],
                'agenda' => ['nullable', 'string', 'max:2000'],
                'scheduled_at' => ['required', 'date', 'after_or_equal:now'],
                'location' => ['nullable', 'string', 'max:255'],
            ],
            [
                'title.required' => 'The meeting title is required.',
                'title.min' => 'The meeting title must be at least :min characters.',
                'scheduled_at.after_or_equal' => 'The meeting date and time must be in the future.',
            ],
            [
                'scheduled_at' => 'date & time',
            ]
        );

        $validated['club_id'] = $club->id;

        $meeting = Meeting::create($validated);

        return redirect()->route('clubs.show', $club)->with('status', 'Meeting created successfully');
    }

    public function edit(Meeting $meeting): View
    {
        return view('meetings.edit', compact('meeting'));
    }

    public function update(Request $request, Meeting $meeting): RedirectResponse
    {
        $validated = $request->validate(
            [
                'title' => ['required', 'string', 'min:3', 'max:255'],
                'agenda' => ['nullable', 'string', 'max:2000'],
                'scheduled_at' => ['required', 'date', 'after_or_equal:now'],
                'location' => ['nullable', 'string', 'max:255'],
            ],
            [
                'title.required' => 'The meeting title is required.',
                'title.min' => 'The meeting title must be at least :min characters.',
                'scheduled_at.after_or_equal' => 'The meeting date and time must be in the future.',
            ],
            [
                'scheduled_at' => 'date & time',
            ]
        );

        $meeting->update($validated);

        return redirect()->route('clubs.show', $meeting->club)->with('status', 'Meeting updated successfully');
    }

    public function destroy(Meeting $meeting): RedirectResponse
    {
        $club = $meeting->club;
        $meeting->delete();
        return redirect()->route('clubs.show', $club)->with('status', 'Meeting deleted successfully');
    }
}


