<?php

namespace App\Http\Livewire\Events;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;

class EditEvent extends Component
{
    use WithFileUploads;

    public $eventId;
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
        'thumbnail' => 'nullable|image|max:2048'
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
    }

    public function update()
    {
        $event = Event::findOrFail($this->eventId);
        Gate::authorize('update', $event);

        $this->validate();

        if ($this->thumbnail) {
            $path = $this->thumbnail->store('events', 'public');
            $event->thumbnail = $path;
        }

        $event->title = $this->title;
        $event->description = $this->description;
        $event->location = $this->location;
        $event->start_date = $this->startDate ?: null;
        $event->end_date = $this->endDate ?: null;
        $event->max_participants = $this->maxParticipants;
        $event->is_public = $this->isPublic;
        $event->save();

        session()->flash('status', 'Event updated.');
        return redirect()->route('events.index');
    }

    public function render()
    {
        return view('livewire.events.edit-event');
    }
}
