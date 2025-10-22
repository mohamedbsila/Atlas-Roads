<x-layouts.app>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Meetings</h1>
                <p class="text-gray-600">All meetings across clubs</p>
            </div>
            <a href="{{ route('clubs.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-users"></i>
                <span class="font-semibold">Clubs</span>
            </a>
        </div>

        @if ($meetings->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($meetings as $meeting)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group relative">
                        <!-- Overlay actions like club cards: View / Edit / Delete -->
                        <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('meetings.show', $meeting) }}" class="bg-white/90 backdrop-blur-sm text-blue-600 p-2 rounded-full shadow-lg hover:bg-blue-600 hover:text-white transition-colors duration-200" title="View">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('meetings.edit', $meeting) }}" class="bg-white/90 backdrop-blur-sm text-yellow-600 p-2 rounded-full shadow-lg hover:bg-yellow-600 hover:text-white transition-colors duration-200" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form method="POST" action="{{ route('meetings.destroy', $meeting) }}" class="inline" onsubmit="return confirm('Delete this meeting?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white/90 backdrop-blur-sm text-red-600 p-2 rounded-full shadow-lg hover:bg-red-600 hover:text-white transition-colors duration-200" title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="text-xl font-bold text-gray-800 line-clamp-1">{{ $meeting->title }}</h3>
                                <div class="flex gap-1"></div>
                            </div>
                            <div class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-users mr-1"></i> {{ $meeting->club->name }}
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-clock text-blue-500 mr-2"></i>
                                <span class="text-sm font-medium">{{ $meeting->scheduled_at->format('M d, Y - H:i') }}</span>
                            </div>
                            @if($meeting->location)
                                <div class="flex items-center text-gray-600 mt-2">
                                    <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                    <span class="text-sm">{{ $meeting->location }}</span>
                                </div>
                            @endif

                            
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="bg-white rounded-2xl shadow-lg p-12 max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-plus text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">No meetings yet</h3>
                    <p class="text-gray-600 mb-6">Create a meeting from a club page.</p>
                    <a href="{{ route('clubs.index') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center gap-2">
                        <i class="fas fa-users"></i>
                        <span class="font-semibold">Browse Clubs</span>
                    </a>
                </div>
            </div>
        @endif

        @if($meetings->hasPages())
            <div class="mt-12 flex justify-center">
                <div class="bg-white rounded-xl shadow-lg p-4">
                    {{ $meetings->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
</x-layouts.app>
