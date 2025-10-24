<?php

namespace App\Http\Livewire\Events;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;

class EditEvent extends Component
{
    use WithFileUploads;

    public $eventId;
    public $title = '';
    public $description = '';
    public $location = '';
    public $thumbnail = null;
    public $currentThumbnailPath = null;
    public $startDate = '';
    public $endDate = '';
    public $maxParticipants = null;
    public $isPublic = true;
    public $communities = [];
    public $availableCommunities = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'location' => 'nullable|string|max:255',
        'startDate' => 'nullable|date',
        'endDate' => 'nullable|date|after_or_equal:startDate',
        'maxParticipants' => 'nullable|integer|min:1',
        'isPublic' => 'boolean',
        'thumbnail' => 'nullable|image|max:2048',
        'communities' => 'nullable|array',
        'communities.*' => 'exists:communities,id'
    ];

    public function mount($id)
    {
        $event = Event::findOrFail($id);

        $this->eventId = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->location = $event->location;
        $this->startDate = $event->start_date;
        $this->endDate = $event->end_date;
        $this->maxParticipants = $event->max_participants;
        $this->isPublic = (bool)$event->is_public;
        $this->currentThumbnailPath = $event->thumbnail;
        // load related communities
        $this->communities = $event->communities()->pluck('communities.id')->map(function($id){ return (string)$id; })->toArray();
        $this->availableCommunities = \App\Models\Community::orderBy('name')->get();
    }

    public function update()
    {
        $event = Event::findOrFail($this->eventId);
        Gate::authorize('update', $event);

        $this->validate();

        // Determine the thumbnail path to persist
        $thumbnailPath = $this->currentThumbnailPath;
        if ($this->thumbnail) {
            // Delete old file if it exists (best-effort)
            if ($this->currentThumbnailPath) {
                try {
                    Storage::disk('public')->delete($this->currentThumbnailPath);
                } catch (\Throwable $_) {
                    // ignore cleanup failures
                }
            }
            $thumbnailPath = $this->thumbnail->store('events', 'public');
        }

        $event->title = $this->title;
        $event->description = $this->description;
        $event->location = $this->location;
        $event->thumbnail = $thumbnailPath;
        $event->start_date = $this->startDate ?: null;
        $event->end_date = $this->endDate ?: null;
        $event->max_participants = $this->maxParticipants;
        $event->is_public = $this->isPublic;
        $event->save();

    // Sync communities selection
    $event->communities()->sync($this->communities ?? []);

        session()->flash('status', 'Event updated.');
        return redirect()->route('events.index');
    }

    public function render()
    {
        return view('livewire.events.edit-event');
    }
}