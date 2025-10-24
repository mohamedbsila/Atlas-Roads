<?php

namespace App\Http\Livewire\Events;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ListEvents extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $confirmingDeletion = false;
    public $deletingId = null;

    // Mark an event for deletion and open the confirmation modal
    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->confirmingDeletion = true;
    }

    // Cancel the delete flow and close the modal
    public function cancelDelete()
    {
        $this->deletingId = null;
        $this->confirmingDeletion = false;
    }

    // Perform deletion after user confirms
    public function deleteConfirmed()
    {
        if (! $this->deletingId) {
            session()->flash('status', 'No event selected for deletion.');
            return;
        }

        $event = Event::find($this->deletingId);

        if (! $event) {
            session()->flash('status', 'Event not found.');
            $this->cancelDelete();
            return;
        }

        // Debug: log current user and event info before authorization check
        try {
            $user = Auth::user();
            Log::info('Delete attempt', [
                'auth_id' => $user ? $user->id : null,
                'is_admin' => $user ? ($user->is_admin ?? null) : null,
                'event_id' => $this->deletingId,
                'event_organizer' => $event->organizer_id,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log auth info before delete', ['err' => $e->getMessage()]);
        }

        // Authorization: do not throw; provide friendly feedback
        $allowed = Gate::allows('delete', $event);
        Log::info('Gate allows delete?', ['allowed' => $allowed]);

        if (! $allowed) {
            session()->flash('status', 'You are not authorized to delete this event.');
            $this->cancelDelete();
            return;
        }

        try {
            $event->delete();

            // Success: flash and notify frontend
            session()->flash('status', 'Event deleted.');
            $this->dispatchBrowserEvent('notify', ['message' => 'Event deleted.']);

            // emit an event in case other components need to react
            $this->emit('eventDeleted', $this->deletingId);
        } catch (\Exception $e) {
            Log::error('Failed to delete event: ' . $e->getMessage(), ['event_id' => $this->deletingId]);
            session()->flash('status', 'An error occurred while deleting the event.');
        }

        $this->cancelDelete();
    }

    public function render()
    {
        return view('livewire.events.list-events', [
            'events' => Event::with('communities')->orderBy('start_date', 'desc')->paginate(10),
        ]);
    }
}

