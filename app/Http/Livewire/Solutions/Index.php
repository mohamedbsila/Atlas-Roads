<?php

namespace App\Http\Livewire\Solutions;

use Livewire\Component;
use App\Models\Reclamation;
use App\Models\Solution;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = ['search' => ['except' => ''], 'sortField', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        // Start building the query
        $query = Reclamation::with(['user', 'solution'])
            ->whereIn('statut', ['en_attente', 'en_cours']);
            
        // Add search conditions if search term exists
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('titre', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm)
                  ->orWhereHas('user', function($q) use ($searchTerm) {
                      $q->where('name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                  });
            });
        }
        
        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);
        
        // Get paginated results
        $reclamations = $query->paginate($this->perPage);
        
        // Debug: Log the query and results
        \Log::info('Reclamations query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'count' => $reclamations->total(),
            'perPage' => $this->perPage,
            'currentPage' => $reclamations->currentPage(),
            'lastPage' => $reclamations->lastPage(),
        ]);
        
        return view('livewire.solutions.index', [
            'reclamations' => $reclamations
        ]);
    }
}
