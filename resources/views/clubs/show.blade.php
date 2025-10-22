<x-layouts.app>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Club Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <!-- Club Image/Header -->
            <div class="relative h-64 overflow-hidden">
                @if($club->image)
                    <img src="{{ asset('storage/' . $club->image) }}" alt="{{ $club->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-users text-white text-6xl"></i>
                    </div>
                @endif
                <!-- Action Buttons -->
                <div class="absolute top-4 right-4 flex gap-2">
                    <a href="{{ route('clubs.create') }}" class="bg-white/90 backdrop-blur-sm text-green-600 p-3 rounded-full shadow-lg hover:bg-green-600 hover:text-white transition-all duration-200" title="New Club">
                        <i class="fas fa-plus"></i>
                    </a>
                    <a href="{{ route('clubs.edit', $club) }}" class="bg-white/90 backdrop-blur-sm text-yellow-600 p-3 rounded-full shadow-lg hover:bg-yellow-600 hover:text-white transition-all duration-200" title="Edit Club">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('clubs.meetings.create', $club) }}" class="bg-white/90 backdrop-blur-sm text-blue-600 p-3 rounded-full shadow-lg hover:bg-blue-600 hover:text-white transition-all duration-200" title="New Meeting">
                        <i class="fas fa-calendar-plus"></i>
                    </a>
                    <form method="POST" action="{{ route('clubs.destroy', $club) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this club?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-white/90 backdrop-blur-sm text-red-600 p-3 rounded-full shadow-lg hover:bg-red-600 hover:text-white transition-all duration-200" title="Delete Club">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Club Info -->
            <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $club->name }}</h1>
                        @if($club->location)
                            <div class="flex items-center text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $club->location }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ $club->meetings_count }} meetings
                        </div>
                    </div>
                </div>

                @if($club->description)
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($club->description)) !!}
                    </div>
                @endif
            </div>
        </div>

        <!-- Meetings Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                    Meetings for {{ $club->name }}
                </h2>
                <div class="flex gap-3">
                    <a href="{{ route('clubs.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="font-semibold">All Clubs</span>
                    </a>
                    <a href="{{ route('clubs.meetings.create', $club) }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-calendar-plus"></i>
                        <span class="font-semibold">New Meeting</span>
                    </a>
                </div>
            </div>

            @if($club->meetings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($club->meetings as $meeting)
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 hover:shadow-lg transition-all duration-300 group">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-800 line-clamp-1">{{ $meeting->title }}</h3>
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="{{ route('meetings.edit', $meeting) }}" class="text-blue-600 hover:text-blue-800 p-1" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form method="POST" action="{{ route('meetings.destroy', $meeting) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this meeting?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                                    <span class="text-sm font-medium">{{ $meeting->scheduled_at->format('M d, Y - H:i') }}</span>
                                </div>

                                @if($meeting->location)
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                        <span class="text-sm">{{ $meeting->location }}</span>
                                    </div>
                                @endif

                                @if($meeting->agenda)
                                    <div class="mt-4">
                                        <p class="text-gray-700 text-sm leading-relaxed line-clamp-3">{{ $meeting->agenda }}</p>
                                    </div>
                                @endif
                            </div>

                            

                            <!-- Status Badge -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                @if($meeting->scheduled_at->isFuture())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Upcoming
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Past
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Meetings State -->
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-plus text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No meetings yet</h3>
                    <p class="text-gray-600 mb-6">Schedule the first meeting for this club!</p>
                    <div class="flex gap-3 justify-center">
                        <a href="{{ route('clubs.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span class="font-semibold">All Clubs</span>
                        </a>
                        <a href="{{ route('clubs.meetings.create', $club) }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 inline-flex items-center gap-2">
                            <i class="fas fa-calendar-plus"></i>
                            <span class="font-semibold">Schedule Meeting</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</x-layouts.app>


