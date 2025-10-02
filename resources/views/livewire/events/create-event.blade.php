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
        <div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
        </div>
    </form>
</div>
