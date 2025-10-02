<?php

namespace App\Http\Livewire\Events;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;

class ListEvents extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $confirmingDeletion = false;
    public $deletingId = null;

    public function delete($id)
    {
        $event = Event::find($id);

        if ($event) {
            Gate::authorize('delete', $event);
            $event->delete();

            session()->flash('status', 'Event deleted.');
            $this->dispatchBrowserEvent('notify', ['message' => 'Event deleted.']);
        }
    }

    public function confirmDelete($id)
    {
        $this->deletingId = $id;
        $this->confirmingDeletion = true;
    }

    public function cancelDelete()
    {
        $this->deletingId = null;
        $this->confirmingDeletion = false;
    }

    public function deleteConfirmed()
    {
        if ($this->deletingId) {
            $this->delete($this->deletingId);
            $this->cancelDelete();
        }
    }

    public function render()
    {
        return view('livewire.events.list-events', [
            'events' => Event::orderBy('start_date', 'desc')->paginate(10),
        ]);
    }
}
