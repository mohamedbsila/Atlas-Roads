<?php

namespace App\Http\Livewire\Drafts;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Draft;

class Form extends Component
{
    use WithFileUploads;

    public ?Draft $draft = null;

    public $title = '';
    public $content = '';
    public $story_date = null;
    public $cover_image = null; // temp uploaded file

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'story_date' => 'nullable|date',
            'cover_image' => 'nullable|image|max:2048',
        ];
    }

public function mount(?Draft $draft = null)
{
}




    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'title' => $this->title,
            'content' => $this->content,
            'story_date' => $this->story_date,
        ];

        if ($this->cover_image) {
            $path = $this->cover_image->store('drafts', 'public');
            $data['cover_image'] = $path;
        }

        if ($this->draft) {
            $this->draft->update($data);
        } else {
            $this->draft = Draft::create($data);
        }
session()->flash('success', 'Draft enregistré.');
session()->flash('stay', true); // nouveau
return redirect()->back();

    }

    public function render()
    {
        return view('livewire.drafts.form');
    }
}
