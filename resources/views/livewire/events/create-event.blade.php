<div class="p-6 bg-white rounded shadow">
    <h3 class="text-lg font-bold mb-4">Create Event</h3>
    <form wire:submit.prevent="create" class="space-y-4">
        <div>
            <label for="title" class="block text-sm">Title</label>
            <input id="title" wire:model="title" class="w-full border rounded px-3 py-2" />
            @error('title') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div>
            <label for="description" class="block text-sm">Description</label>
            <textarea id="description" wire:model="description" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="startDate" class="block text-sm">Start Date</label>
                <input id="startDate" wire:model="startDate" type="datetime-local" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label for="endDate" class="block text-sm">End Date</label>
                <input id="endDate" wire:model="endDate" type="datetime-local" class="w-full border rounded px-3 py-2" />
            </div>
        </div>
        <div>
            <label for="communities" class="block text-sm font-medium text-gray-700">Communities</label>
            @if(isset($availableCommunities) && $availableCommunities->count())
                <select id="communities" wire:model="communities" multiple class="w-full border rounded-md shadow-sm py-2 px-3">
                    @foreach($availableCommunities as $community)
                        <option value="{{ $community->id }}">{{ $community->name }}</option>
                    @endforeach
                </select>
            @else
                <div class="text-gray-500 text-sm">No communities available.</div>
            @endif
            @error('communities') <div class="text-red-500 text-sm mt-1">{{ $message }}</div> @enderror
        </div>
        <div>
            <label for="location" class="block text-sm">Location</label>
            <input id="location" wire:model="location" class="w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label for="thumbnail" class="block text-sm">Thumbnail</label>
            <input id="thumbnail" type="file" wire:model="thumbnail" class="w-full" />
            @error('thumbnail') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="maxParticipants" class="block text-sm">Max Participants</label>
                <input id="maxParticipants" wire:model="maxParticipants" type="number" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
                <label class="inline-flex items-center mt-2">
                    <input type="checkbox" wire:model="isPublic" class="form-checkbox" />
                    <span class="ml-2">Public</span>
                </label>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded flex items-center" wire:loading.attr="disabled" aria-live="polite">
                <!-- spinner shown while loading -->
                <svg wire:loading xmlns="http://www.w3.org/2000/svg" class="animate-spin mr-2 h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
                    <path class="opacity-75" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" stroke-width="4"></path>
                </svg>

                <span wire:loading.remove>Create</span>
                <span wire:loading>Creating...</span>
            </button>

            <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded">Cancel</a>
        </div>
    </form>
</div>
