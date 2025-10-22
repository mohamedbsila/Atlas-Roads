<x-layouts.app>
    <div>
        @if(session('success'))
            <div class="mb-4 p-4 text-white rounded-xl flex items-center justify-between" 
                 style="background:linear-gradient(to right,#84cc16,#4ade80)">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="mb-6">
            <a href="{{ route('admin.wishlist.index') }}" class="text-blue-600 hover:underline">
                <i class="fas fa-arrow-left mr-2"></i> Back to Wishlist Management
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-soft-xl p-6 mb-6 overflow-hidden">
                    <!-- Book Cover Image -->
                    @if($wishlist->image)
                        <div class="mb-6 -mx-6 -mt-6">
                            <div class="relative h-64 bg-gradient-to-br from-gray-100 to-gray-200">
                                <img src="{{ asset('storage/' . $wishlist->image) }}" 
                                     alt="{{ $wishlist->title }}" 
                                     class="w-full h-full object-contain">
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $wishlist->title }}</h2>
                            <p class="text-gray-600">by {{ $wishlist->author }}</p>
                        </div>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 text-xs font-bold text-white rounded-full"
                                  style="background: {{ $wishlist->status_color === 'gray' ? '#6b7280' : ($wishlist->status_color === 'blue' ? '#3b82f6' : ($wishlist->status_color === 'green' ? '#10b981' : ($wishlist->status_color === 'purple' ? '#a855f7' : '#ef4444'))) }}">
                                {{ $wishlist->status_label }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded"
                                  style="background: {{ $wishlist->priority_color === 'red' ? '#fee2e2' : ($wishlist->priority_color === 'yellow' ? '#fef3c7' : '#dcfce7') }}; color: {{ $wishlist->priority_color === 'red' ? '#991b1b' : ($wishlist->priority_color === 'yellow' ? '#92400e' : '#166534') }}">
                                {{ $wishlist->priority_label }} Priority
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        @if($wishlist->isbn)
                            <div>
                                <p class="text-xs text-gray-500 uppercase">ISBN</p>
                                <p class="font-semibold">{{ $wishlist->isbn }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Requested By</p>
                            <p class="font-semibold">{{ $wishlist->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $wishlist->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Requested On</p>
                            <p class="font-semibold">{{ $wishlist->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Votes</p>
                            <p class="font-semibold"><i class="fas fa-heart text-pink-500"></i> {{ $wishlist->votes_count }}</p>
                        </div>
                        @if($wishlist->max_price)
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Max Budget</p>
                                <p class="font-semibold text-green-600">${{ $wishlist->max_price }}</p>
                            </div>
                        @endif
                    </div>

                    @if($wishlist->description)
                        <div class="border-t pt-4">
                            <p class="text-xs text-gray-500 uppercase mb-2">User's Note</p>
                            <p class="text-gray-700">{{ $wishlist->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Update Status Form -->
                <div class="bg-white rounded-2xl shadow-soft-xl p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Update Status</h3>
                    <form action="{{ route('admin.wishlist.update-status', $wishlist) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
                                <option value="PENDING" {{ $wishlist->status == 'PENDING' ? 'selected' : '' }}>Pending</option>
                                <option value="SEARCHING" {{ $wishlist->status == 'SEARCHING' ? 'selected' : '' }}>Searching</option>
                                <option value="FOUND" {{ $wishlist->status == 'FOUND' ? 'selected' : '' }}>Found</option>
                                <option value="ORDERED" {{ $wishlist->status == 'ORDERED' ? 'selected' : '' }}>Ordered</option>
                                <option value="REJECTED" {{ $wishlist->status == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Notes</label>
                            <textarea name="admin_notes" rows="3" 
                                      class="w-full rounded-lg border border-gray-300 px-3 py-2"
                                      placeholder="Add notes for the user...">{{ $wishlist->admin_notes }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Estimated Price</label>
                                <input type="number" name="estimated_price" step="0.01" value="{{ $wishlist->estimated_price }}"
                                       class="w-full rounded-lg border border-gray-300 px-3 py-2" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Estimated Days</label>
                                <input type="number" name="estimated_days" value="{{ $wishlist->estimated_days }}"
                                       class="w-full rounded-lg border border-gray-300 px-3 py-2" placeholder="7">
                            </div>
                        </div>

                        <button type="submit" class="w-full px-6 py-3 font-bold text-white rounded-lg shadow-md hover:scale-105 transition-all"
                                style="background:linear-gradient(to right,#d946ef,#ec4899)">
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Link to Existing Book -->
                @if(!$wishlist->is_found && $similarBooks->count() > 0)
                    <div class="bg-white rounded-2xl shadow-soft-xl p-6">
                        <h3 class="text-lg font-bold mb-4">Similar Books in Library</h3>
                        <div class="space-y-3">
                            @foreach($similarBooks as $book)
                                <div class="flex justify-between items-center p-3 border rounded-lg hover:bg-gray-50">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $book->title }}</p>
                                        <p class="text-xs text-gray-500">by {{ $book->author }}</p>
                                    </div>
                                    <form action="{{ route('admin.wishlist.link-book', $wishlist) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="px-3 py-1 text-xs font-bold text-white bg-green-500 rounded hover:bg-green-600">
                                            Link This Book
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-soft-xl p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        @if($wishlist->is_found && $wishlist->book)
                            <a href="{{ route('books.show', $wishlist->book) }}" 
                               class="block w-full px-4 py-2 text-center font-bold text-white bg-green-500 rounded-lg hover:bg-green-600">
                                <i class="fas fa-book mr-2"></i> View Linked Book
                            </a>
                        @endif
                        
                        <button onclick="document.getElementById('createBookModal').classList.remove('hidden')"
                                class="block w-full px-4 py-2 text-center font-bold text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                            <i class="fas fa-plus mr-2"></i> Create New Book
                        </button>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="bg-white rounded-2xl shadow-soft-xl p-6">
                    <h3 class="text-lg font-bold mb-4">Request Info</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500">Created</p>
                            <p class="font-semibold">{{ $wishlist->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Last Updated</p>
                            <p class="font-semibold">{{ $wishlist->updated_at->diffForHumans() }}</p>
                        </div>
                        @if($wishlist->found_at)
                            <div>
                                <p class="text-xs text-gray-500">Found On</p>
                                <p class="font-semibold text-green-600">{{ $wishlist->found_at->format('M d, Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Book Modal (Simple Version) -->
    <div id="createBookModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Create Book from Request</h3>
                <button onclick="document.getElementById('createBookModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <p class="text-sm text-gray-600 mb-4">This will create a new book and automatically link it to this wishlist request.</p>
            
            <p class="text-center text-gray-500 py-8">
                <i class="fas fa-info-circle text-4xl mb-2"></i><br>
                For now, please create the book manually via Book Management,<br>
                then come back here to link it.
            </p>
        </div>
    </div>
</x-layouts.app> 