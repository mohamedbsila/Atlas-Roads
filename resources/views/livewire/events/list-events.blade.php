<div>
    <div class="p-6 bg-white rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Events</h3>
            <a href="{{ route('events.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Create Event</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-green-600">{{ session('status') }}</div>
        @endif

        <table class="w-full table-auto">
            <thead>
                <tr class="text-left">
                    <th>Title</th>
                    <th>Start</th>
                    <th>End</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr class="border-t">
                        <td class="py-2 flex items-center gap-3">
                            @if($event->thumbnail)
                                <img src="{{ asset('storage/' . $event->thumbnail) }}" alt="" class="w-12 h-8 object-cover rounded" />
                            @endif
                            <span>{{ $event->title }}</span>
                        </td>
                        <td class="py-2">{{ $event->start_date }}</td>
                        <td class="py-2">{{ $event->end_date }}</td>
                        <td class="py-2 text-right">
                            <a href="{{ route('events.edit', $event->id) }}" class="px-2 py-1 bg-blue-600 text-white rounded mr-2">Edit</a>
                            <button wire:click.prevent="confirmDelete('{{ $event->id }}')" class="px-2 py-1 bg-red-600 text-white rounded">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{}">
        <div x-show="" style="display: none;" wire:ignore.self class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <h4 class="text-lg font-bold mb-4">Confirm Delete</h4>
                <p class="mb-4">Are you sure you want to delete this event? This action cannot be undone.</p>
                <div class="text-right">
                    <button wire:click="cancelDelete" class="px-3 py-2 mr-2 bg-gray-200 rounded">Cancel</button>
                    <button wire:click="deleteConfirmed" class="px-3 py-2 bg-red-600 text-white rounded">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
