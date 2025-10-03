<div class="p-6 bg-white rounded shadow">
    <h3 class="text-lg font-bold mb-4">Edit Event</h3>

    <form wire:submit.prevent="update">
        <div class="mb-3">
            <label class="block text-sm font-medium">Title</label>
            <input wire:model="title" class="mt-1 block w-full border rounded px-2 py-1" />
            @error('title') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Description</label>
            <textarea wire:model="description" class="mt-1 block w-full border rounded px-2 py-1"></textarea>
            @error('description') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Location</label>
            <input wire:model="location" class="mt-1 block w-full border rounded px-2 py-1" />
            @error('location') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Start</label>
            <input wire:model="startDate" type="datetime-local" class="mt-1 block w-full border rounded px-2 py-1" />
            @error('startDate') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">End</label>
            <input wire:model="endDate" type="datetime-local" class="mt-1 block w-full border rounded px-2 py-1" />
            @error('endDate') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">Thumbnail (replace)</label>
            <input wire:model="thumbnail" type="file" accept="image/*" />
            @error('thumbnail') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded">Update</button>
            <a href="{{ route('events.index') }}" class="px-3 py-2 bg-gray-200 rounded">Cancel</a>
        </div>
    </form>
</div>
