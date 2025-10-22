<x-layouts.app>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Edit Meeting</h1>
                <p class="text-gray-600">Update meeting details for <span class="font-semibold text-blue-600">{{ $meeting->club->name }}</span></p>
                <div class="mt-4 flex justify-center gap-3">
                    <a href="{{ route('clubs.show', $meeting->club) }}" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span class="font-semibold">Back to Club</span>
                    </a>
                    <a href="{{ route('clubs.index') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-users"></i>
                        <span class="font-semibold">All Clubs</span>
                    </a>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl mb-6">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-semibold">Please fix the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('meetings.update', $meeting) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Meeting Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-blue-600"></i>
                            Meeting Title
                        </label>
                        <input type="text" name="title" value="{{ old('title', $meeting->title) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                               placeholder="What's this meeting about?" required />
                    </div>

                    <!-- Date & Time -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                            Date & Time
                        </label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $meeting->scheduled_at->format('Y-m-d\TH:i')) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                               required />
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                            Location
                        </label>
                        <input type="text" name="location" value="{{ old('location', $meeting->location) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                               placeholder="Where will the meeting take place?" />
                    </div>

                    <!-- Agenda -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-list-ul mr-2 text-blue-600"></i>
                            Agenda
                        </label>
                        <textarea name="agenda" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none" 
                                  placeholder="What will be discussed in this meeting?">{{ old('agenda', $meeting->agenda) }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-6">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 font-semibold">
                            <i class="fas fa-save mr-2"></i>
                            Update Meeting
                        </button>
                        <a href="{{ route('clubs.show', $meeting->club) }}" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 font-semibold text-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
