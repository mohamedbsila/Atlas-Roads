<x-layouts.app>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Clubs</h1>
                <p class="text-gray-600">Discover and join amazing clubs</p>
            </div>
            <a href="{{ route('clubs.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span class="font-semibold">New Club</span>
            </a>
        </div>

        @if ($clubs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($clubs as $club)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden group">
                        <!-- Club Image -->
                        <div class="relative h-48 overflow-hidden">
                            @if($club->image)
                                <img src="{{ asset('storage/' . $club->image) }}" alt="{{ $club->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                    <i class="fas fa-users text-white text-4xl"></i>
                                </div>
                            @endif
                            <!-- Action Buttons Overlay -->
                            <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ route('clubs.show', $club) }}" class="bg-white/90 backdrop-blur-sm text-blue-600 p-2 rounded-full shadow-lg hover:bg-blue-600 hover:text-white transition-colors duration-200" title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('clubs.edit', $club) }}" class="bg-white/90 backdrop-blur-sm text-yellow-600 p-2 rounded-full shadow-lg hover:bg-yellow-600 hover:text-white transition-colors duration-200" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form method="POST" action="{{ route('clubs.destroy', $club) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this club?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-white/90 backdrop-blur-sm text-red-600 p-2 rounded-full shadow-lg hover:bg-red-600 hover:text-white transition-colors duration-200" title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Club Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $club->name }}</h3>
                            
                            @if($club->location)
                                <div class="flex items-center text-gray-600 mb-3">
                                    <i class="fas fa-map-marker-alt text-sm mr-2"></i>
                                    <span class="text-sm">{{ $club->location }}</span>
                                </div>
                            @endif

                            @if($club->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $club->description }}</p>
                            @endif

                            <!-- Meetings Count -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-blue-600">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    <span class="text-sm font-medium">{{ $club->meetings_count }} meetings</span>
                                </div>
                                <a href="{{ route('clubs.show', $club) }}" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="bg-white rounded-2xl shadow-lg p-12 max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">No clubs yet</h3>
                    <p class="text-gray-600 mb-6">Be the first to create an amazing club!</p>
                    <a href="{{ route('clubs.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span class="font-semibold">Create First Club</span>
                    </a>
                </div>
            </div>
        @endif

        <!-- Pagination -->
        @if($clubs->hasPages())
            <div class="mt-12 flex justify-center">
                <div class="bg-white rounded-xl shadow-lg p-4">
                    {{ $clubs->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
</x-layouts.app>


