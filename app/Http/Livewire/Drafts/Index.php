<?php

namespace App\Http\Livewire\Drafts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Draft;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $filterDateFrom = null;
    public $filterDateTo = null;

    protected $updatesQueryString = ['search'];

    public function render()
    {
        $drafts = Draft::query()
            ->when(auth()->check(), function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterDateFrom, function ($q) {
                $q->whereDate('story_date', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function ($q) {
                $q->whereDate('story_date', '<=', $this->filterDateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest()
            ->paginate(8);

        return view('livewire.drafts.index', compact('drafts'));
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function deleteDraft($id)
    {
        $draft = Draft::where('user_id', auth()->id())->findOrFail($id);
        $draft->delete();
        session()->flash('success', 'Brouillon supprimé.');
        $this->resetPage();
    }
}
