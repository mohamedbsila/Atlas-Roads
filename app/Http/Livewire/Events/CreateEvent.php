<?php

namespace App\Http\Livewire\Events;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;

class CreateEvent extends Component
{
    use WithFileUploads;
    public $title = '';
    public $description = '';
    public $location = '';
    public $thumbnail = null;
    public $startDate = '';
    public $endDate = '';
    public $maxParticipants = null;
    public $isPublic = true;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'location' => 'nullable|string|max:255',
        'startDate' => 'nullable|date',
    'endDate' => 'nullable|date|after_or_equal:startDate',
    'maxParticipants' => 'nullable|integer|min:1',
    'isPublic' => 'boolean',
    'thumbnail' => 'nullable|image|max:2048',
    ];

    public function create()
    {
        Gate::authorize('create', \App\Models\Event::class);
        $this->validate();
        $thumbnailPath = null;
        if ($this->thumbnail) {
            $thumbnailPath = $this->thumbnail->store('events', 'public');
        }
        Event::create([
            'organizer_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'thumbnail' => $thumbnailPath,
            'start_date' => null,
            'end_date' => $this->endDate ?: null,
            'max_participants' => $this->maxParticipants,
            'is_public' => $this->isPublic,
            'status' => 'PUBLISHED'
        ]);

        session()->flash('status', 'Event created successfully.');
        // use Livewire's redirect helper to ensure the browser navigates away
        $this->redirectRoute('events.index');
    }

    public function render()
    {
        return view('livewire.events.create-event');
    }
}
