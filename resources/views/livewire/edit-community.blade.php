<div>
    <div class="p-6 bg-white rounded shadow">
        <div class="mb-4">
            <h3 class="text-lg font-bold">Edit Community</h3>
        </div>

        <form wire:submit.prevent="update">
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold mb-2">Community Name <span class="text-red-600">*</span></label>
                <input type="text" id="name" wire:model="name" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="slug" class="block text-sm font-semibold mb-2">Slug (URL-friendly name)</label>
                <input type="text" id="slug" wire:model="slug" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave empty to auto-generate">
                @error('slug') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                <p class="text-sm text-gray-500 mt-1">Leave empty to auto-generate from name</p>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-semibold mb-2">Description</label>
                <textarea id="description" wire:model="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="newCoverImage" class="block text-sm font-semibold mb-2">Cover Image</label>
                
                @if($currentCoverImagePath)
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <img src="{{ str_starts_with($currentCoverImagePath, 'http') ? $currentCoverImagePath : asset('storage/' . $currentCoverImagePath) }}" alt="Current cover" class="w-48 h-32 object-cover rounded border">
                    </div>
                @endif
                
                <input type="file" id="newCoverImage" wire:model="newCoverImage" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('newCoverImage') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                <p class="text-sm text-gray-500 mt-1">Maximum file size: 2MB. Supported formats: JPG, PNG, GIF</p>
                
                @if($newCoverImage)
                    <div class="mt-3">
                        <p class="text-sm text-gray-600 mb-2">Preview:</p>
                        <img src="{{ $newCoverImage->temporaryUrl() }}" alt="Preview" class="w-48 h-32 object-cover rounded border">
                    </div>
                @endif
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" wire:model="is_public" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="text-sm font-semibold">Public Community</span>
                </label>
                <p class="text-sm text-gray-500 mt-1 ml-6">Public communities are visible to all users</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Associated Events</label>
                @if(!empty($associatedEvents) && count($associatedEvents))
                    <ul class="list-disc list-inside text-sm">
                        @foreach($associatedEvents as $ev)
                            <li>{{ $ev->title }} ({{ $ev->start_date ?? 'N/A' }})</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">No associated events.</p>
                @endif
            </div>

            <div class="flex justify-between">
                <a href="{{ route('community.index') }}" class="px-4 py-2 inline-flex items-center rounded" style="background-color:#e5e7eb;color:#111827;border:1px solid rgba(0,0,0,0.04);">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Communities
                </a>
                <button type="submit" class="px-4 py-2 inline-flex items-center rounded" style="background-color:#2563eb;color:#ffffff;border:1px solid rgba(0,0,0,0.05);">
                    <i class="fas fa-save mr-2"></i>Update Community
                </button>
            </div>
        </form>
    </div>
</div>