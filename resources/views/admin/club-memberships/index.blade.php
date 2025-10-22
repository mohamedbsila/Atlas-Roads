<x-layouts.app>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Club Membership Requests</h1>
            <p class="text-gray-600">Manage user applications to join clubs</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($memberships->count() > 0)
            <div class="space-y-6">
                @foreach($memberships as $membership)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-white text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-800">{{ $membership->user->name }}</h3>
                                            <p class="text-gray-600">{{ $membership->user->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-blue-50 rounded-xl p-4 mb-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="fas fa-users text-blue-600"></i>
                                            <span class="font-semibold text-blue-800">Wants to join:</span>
                                        </div>
                                        <h4 class="text-lg font-bold text-gray-800">{{ $membership->club->name }}</h4>
                                        @if($membership->club->location)
                                            <p class="text-gray-600 text-sm flex items-center mt-1">
                                                <i class="fas fa-map-marker-alt mr-2"></i>
                                                {{ $membership->club->location }}
                                            </p>
                                        @endif
                                    </div>

                                    @if($membership->message)
                                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                            <h5 class="font-semibold text-gray-700 mb-2 flex items-center">
                                                <i class="fas fa-comment mr-2 text-gray-600"></i>
                                                User Message:
                                            </h5>
                                            <p class="text-gray-700 leading-relaxed">{{ $membership->message }}</p>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar mr-2"></i>
                                            Applied: {{ $membership->created_at->format('M d, Y - H:i') }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-2"></i>
                                            Status: 
                                            <span class="ml-1 px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col gap-2 ml-6">
                                    <form method="POST" action="{{ route('admin.club-memberships.approve', $membership) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2 font-semibold">
                                            <i class="fas fa-check"></i>
                                            Approve
                                        </button>
                                    </form>

                                    <button onclick="openRejectModal({{ $membership->id }})" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2 font-semibold">
                                        <i class="fas fa-times"></i>
                                        Reject
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $memberships->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="bg-white rounded-2xl shadow-xl p-12 max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-check text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">All caught up!</h3>
                    <p class="text-gray-600">No pending club membership requests at the moment.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full relative">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Reject Application</h3>
                    <p class="text-red-100 text-sm">Provide a reason for rejection (optional)</p>
                </div>
                <button onclick="closeRejectModal()" class="text-white hover:text-red-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Form -->
        <form id="rejectForm" method="POST" class="p-6">
            @csrf
            @method('PATCH')
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-comment mr-2 text-red-600"></i>
                    Reason for rejection (Optional)
                </label>
                <textarea 
                    name="admin_notes" 
                    rows="4" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 resize-none"
                    placeholder="Let the user know why their application was rejected..."
                    maxlength="500"></textarea>
                <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-colors duration-200">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all transform hover:-translate-y-1 hover:shadow-lg">
                    <i class="fas fa-times mr-2"></i>
                    Reject Application
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(membershipId) {
    document.getElementById('rejectForm').action = `/admin/club-memberships/${membershipId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('rejectForm').reset();
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
</x-layouts.app>
