<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Community;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EditCommunity extends Component
{
    use WithFileUploads;

    public $communityId;
    public $name;
    public $slug;
    public $description;
    public $is_public = true;
    public $cover_image;
    public $newCoverImage;
    public $currentCoverImagePath;
    public $associatedEvents = [];

    protected $rules = [
        'name' => 'required|string|max:100',
        'slug' => 'nullable|string|max:120',
        'description' => 'nullable|string',
        'newCoverImage' => 'nullable|image|max:2048',
        'is_public' => 'boolean',
    ];

    public function mount($id)
    {
        $community = Community::findOrFail($id);
        
        // Authorization check
        $this->authorize('update', $community);

        $this->communityId = $community->id;
        $this->name = $community->name;
        $this->slug = $community->slug;
        $this->description = $community->description;
        $this->is_public = $community->is_public;
        $this->currentCoverImagePath = $community->cover_image;
        // load associated events for display
        $this->associatedEvents = $community->events()->orderBy('start_date', 'desc')->get();
    }

    public function update()
    {
        $this->validate();

        $community = Community::findOrFail($this->communityId);

        // Authorization check
        $this->authorize('update', $community);

        // Auto-generate slug if not provided
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }

        $coverImagePath = $this->currentCoverImagePath;

        // Handle new image upload
        if ($this->newCoverImage) {
            // Delete old image if it exists and is a local file
            if ($this->currentCoverImagePath && !str_starts_with($this->currentCoverImagePath, 'http')) {
                Storage::disk('public')->delete($this->currentCoverImagePath);
            }
            
            // Store new image
            $coverImagePath = $this->newCoverImage->store('communities', 'public');
        }

        $community->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'is_public' => $this->is_public,
            'cover_image' => $coverImagePath,
        ]);

        session()->flash('success', 'Community updated successfully!');

        return redirect()->route('community.index');
    }

    public function render()
    {
        return view('livewire.edit-community');
    }
}
