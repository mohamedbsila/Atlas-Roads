<x-layouts.app>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $meeting->title }}</h1>
                        <div class="text-gray-600">
                            <i class="fas fa-users mr-2"></i>
                            <a href="{{ route('clubs.show', $meeting->club) }}" class="text-blue-600 hover:text-blue-800">{{ $meeting->club->name }}</a>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('meetings.edit', $meeting) }}" class="bg-white/90 text-yellow-600 p-3 rounded-full shadow-lg hover:bg-yellow-600 hover:text-white transition-all duration-200" title="Edit Meeting">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('meetings.destroy', $meeting) }}" onsubmit="return confirm('Delete this meeting?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-white/90 text-red-600 p-3 rounded-full shadow-lg hover:bg-red-600 hover:text-white transition-all duration-200" title="Delete Meeting">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-clock text-blue-500 mr-3"></i>
                            <span class="font-medium">{{ $meeting->scheduled_at->format('M d, Y - H:i') }}</span>
                        </div>
                        @if($meeting->location)
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-map-marker-alt text-green-500 mr-3"></i>
                            <span>{{ $meeting->location }}</span>
                        </div>
                        @endif
                        <div>
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
                    <div>
                        @if($meeting->agenda)
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">Agenda</h2>
                            <div class="prose max-w-none text-gray-700 leading-relaxed">
                                {!! nl2br(e($meeting->agenda)) !!}
                            </div>
                        @else
                            <div class="text-gray-500">No agenda provided.</div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('meetings.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-list mr-2"></i>
                        All Meetings
                    </a>
                    <a href="{{ route('clubs.show', $meeting->club) }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-users mr-2"></i>
                        Back to Club
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
