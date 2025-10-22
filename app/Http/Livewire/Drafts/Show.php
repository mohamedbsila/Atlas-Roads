<?php

namespace App\Http\Livewire\Drafts;

use Livewire\Component;
use App\Models\Draft;

class Show extends Component
{
    public Draft $draft;

    public function mount(Draft $draft)
    {
        abort_unless(auth()->check() && $draft->user_id === auth()->id(), 403);
        $this->draft = $draft;
    }

    public function render()
    {
        return view('livewire.drafts.show');
    }
}
