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
            @if($currentThumbnailPath)
                <div class="mb-2">
                    <img src="{{ Storage::url($currentThumbnailPath) }}" alt="Current thumbnail" class="w-28 h-20 object-cover rounded border" />
                </div>
            @endif
            <input wire:model="thumbnail" type="file" accept="image/*" />
            @error('thumbnail') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="communitiesSelect" class="block text-sm font-medium">Communities</label>
            @if(isset($availableCommunities) && $availableCommunities->count())
                <select id="communitiesSelect" wire:model="communities" multiple class="mt-1 block w-full border rounded px-2 py-1">
                    @foreach($availableCommunities as $community)
                        <option value="{{ $community->id }}" @if(in_array((string)$community->id, (array)$communities)) selected @endif>{{ $community->name }}</option>
                    @endforeach
                </select>
            @else
                <div class="mt-1 block w-full border rounded px-2 py-1 text-gray-500">No communities available. <a href="{{ route('community.create') }}" class="text-blue-600 underline">Create one</a></div>
            @endif
            @error('communities') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded">Update</button>
            <a href="{{ route('events.index') }}" class="px-3 py-2 bg-gray-200 rounded">Cancel</a>
        </div>
    </form>
</div>