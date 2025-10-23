<x-layouts.wishlist>
    <div class="container mx-auto px-4">
        @if(session('success'))
            <div class="mb-4 p-4 text-white rounded-xl flex items-center justify-between" 
                 style="background:linear-gradient(to right,#84cc16,#4ade80)">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 p-4 text-white rounded-xl flex items-center justify-between" 
                 style="background:linear-gradient(to right,#f59e0b,#f97316)">
                <span>{{ session('warning') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="flex flex-wrap justify-between items-center mb-6">
            <div>
                <h5 class="mb-0 font-bold text-2xl">📚 My Wishlist</h5>
                <p class="text-size-sm text-slate-400">Books you requested</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('wishlist.browse') }}" 
                   class="inline-block px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-size-xs shadow-md hover:scale-105 transition-all"
                   style="background:linear-gradient(to right,#06b6d4,#3b82f6)">
                    <i class="fas fa-globe mr-2"></i> Browse Wishes
                </a>
                <a href="{{ route('wishlist.create') }}" 
                   class="inline-block px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-size-xs shadow-md hover:scale-105 transition-all"
                   style="background:linear-gradient(to right,#d946ef,#ec4899)">
                    <i class="fas fa-plus mr-2"></i> Request Book
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4">
                <form method="GET" action="{{ route('wishlist.index') }}" class="flex gap-4 flex-wrap">
                    <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option value="">All Statuses</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="SEARCHING" {{ request('status') == 'SEARCHING' ? 'selected' : '' }}>🔍 Searching</option>
                        <option value="FOUND" {{ request('status') == 'FOUND' ? 'selected' : '' }}>✅ Found</option>
                        <option value="ORDERED" {{ request('status') == 'ORDERED' ? 'selected' : '' }}>📦 Ordered</option>
                        <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>❌ Rejected</option>
                    </select>

                    <select name="priority" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        <option value="">All Priorities</option>
                        <option value="HIGH" {{ request('priority') == 'HIGH' ? 'selected' : '' }}>🔴 High</option>
                        <option value="MEDIUM" {{ request('priority') == 'MEDIUM' ? 'selected' : '' }}>🟡 Medium</option>
                        <option value="LOW" {{ request('priority') == 'LOW' ? 'selected' : '' }}>🟢 Low</option>
                    </select>

                    <button type="submit" class="px-6 py-2 font-bold text-white rounded-lg text-size-xs shadow-md hover:scale-105 transition-all"
                            style="background:linear-gradient(to right,#d946ef,#ec4899)">
                        Filter
                    </button>
                    <a href="{{ route('wishlist.index') }}" class="px-6 py-2 font-bold text-gray-700 bg-gray-200 rounded-lg text-size-xs shadow-md hover:scale-105 transition-all">
                        Clear
                    </a>
                </form>
            </div>
        </div>

        <!-- Wishlist Items -->
        @if($wishes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wishes as $wish)
                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border hover:shadow-xl transition-all overflow-hidden">
                        <!-- Book Cover Image -->
                        @if($wish->image)
                            <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
                                <img src="{{ asset('storage/' . $wish->image) }}" 
                                     alt="{{ $wish->title }}" 
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            </div>
                        @endif

                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="absolute {{ $wish->image ? 'top-4' : 'top-4' }} right-4">
                                <span class="px-3 py-1 text-xs font-bold text-white rounded-full shadow-lg"
                                      style="background: {{ $wish->status_color === 'gray' ? '#6b7280' : ($wish->status_color === 'blue' ? '#3b82f6' : ($wish->status_color === 'green' ? '#10b981' : ($wish->status_color === 'purple' ? '#a855f7' : '#ef4444'))) }}">
                                    {{ $wish->status_label }}
                                </span>
                            </div>

                            <!-- Priority Badge -->
                            <div class="mb-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded"
                                      style="background: {{ $wish->priority_color === 'red' ? '#fee2e2' : ($wish->priority_color === 'yellow' ? '#fef3c7' : '#dcfce7') }}; color: {{ $wish->priority_color === 'red' ? '#991b1b' : ($wish->priority_color === 'yellow' ? '#92400e' : '#166534') }}">
                                    {{ $wish->priority_label }}
                                </span>
                            </div>

                            <h6 class="mb-2 font-bold text-lg">{{ $wish->title }}</h6>
                            <p class="text-sm text-slate-500 mb-2">by {{ $wish->author }}</p>
                            
                            @if($wish->isbn)
                                <p class="text-xs text-slate-400 mb-2">ISBN: {{ $wish->isbn }}</p>
                            @endif

                            @if($wish->description)
                                <p class="text-sm text-slate-600 mb-3">{{ Str::limit($wish->description, 100) }}</p>
                            @endif

                            <!-- Admin Notes -->
                            @if($wish->admin_notes)
                                <div class="bg-blue-50 p-3 rounded-lg mb-3">
                                    <p class="text-xs font-semibold text-blue-800 mb-1">📝 Admin Note:</p>
                                    <p class="text-xs text-blue-700">{{ $wish->admin_notes }}</p>
                                </div>
                            @endif

                            <!-- Estimated Info -->
                            @if($wish->estimated_price || $wish->estimated_days)
                                <div class="flex gap-3 mb-3 text-xs">
                                    @if($wish->estimated_price)
                                        <span class="text-green-600">💰 ~${{ $wish->estimated_price }}</span>
                                    @endif
                                    @if($wish->estimated_days)
                                        <span class="text-blue-600">⏱️ ~{{ $wish->estimated_days }} days</span>
                                    @endif
                                </div>
                            @endif

                            <!-- If Found and Linked -->
                            @if($wish->is_found && $wish->book)
                                <div class="bg-green-50 p-3 rounded-lg mb-3">
                                    <p class="text-xs font-semibold text-green-800 mb-2">✅ Book is now available!</p>
                                    <a href="{{ route('books.show', $wish->book) }}" class="text-xs text-green-700 hover:underline font-semibold">
                                        View Book →
                                    </a>
                                </div>
                            @endif

                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('wishlist.show', $wish) }}" 
                                   class="flex-1 px-4 py-2 text-center font-bold text-white rounded-lg text-size-xs shadow-md hover:scale-105 transition-all"
                                   style="background:linear-gradient(to right,#06b6d4,#3b82f6)">
                                    View Details
                                </a>
                                
                                @if($wish->canBeCancelled())
                                    <form action="{{ route('wishlist.destroy', $wish) }}" method="POST" class="inline" id="deleteForm{{ $wish->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="showDeleteModal({{ $wish->id }}, '{{ $wish->title }}')"
                                                class="px-4 py-2 font-bold text-white bg-red-500 rounded-lg text-size-xs shadow-md hover:scale-105 transition-all">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <p class="text-xs text-slate-400 mt-3">Requested: {{ $wish->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $wishes->links() }}
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-2xl shadow-soft-xl">
                <i class="fas fa-book-reader text-6xl text-slate-300 mb-4"></i>
                <h3 class="text-xl font-bold text-slate-600 mb-2">No Wishes Yet</h3>
                <p class="text-slate-400 mb-4">Start by requesting a new book</p>
                <a href="{{ route('wishlist.create') }}" 
                   class="inline-block px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-size-xs shadow-md hover:scale-105 transition-all"
                   style="background:linear-gradient(to right,#d946ef,#ec4899)">
                    <i class="fas fa-plus mr-2"></i> Request Your First Book
                </a>
            </div>
        @endif
    </div>

    <!-- Custom Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay with animation -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeDeleteModal()"></div>

            <!-- Center modal -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 style="animation: slideInUp 0.3s ease-out;">
                <div class="bg-gradient-to-br from-red-50 to-pink-50 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-red-400 to-red-600 sm:mx-0 sm:h-16 sm:w-16 shadow-lg"
                             style="animation: bounce 1s infinite;">
                            <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-2" id="modal-title">
                                Cancel Book Request?
                            </h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 mb-2">
                                    Are you sure you want to cancel your request for:
                                </p>
                                <p class="text-base font-bold text-gray-800 bg-white px-4 py-3 rounded-xl shadow-sm" id="bookTitle">
                                    📚 <span id="modalBookTitle"></span>
                                </p>
                                <p class="text-xs text-red-600 mt-3 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    This action cannot be undone!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="button" onclick="confirmDelete()" 
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-base font-bold text-white hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Yes, Cancel Request
                    </button>
                    <button type="button" onclick="closeDeleteModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-xl border-2 border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-all transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        No, Keep It
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(100px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        #deleteModal {
            backdrop-filter: blur(4px);
        }
    </style>

    <script>
        let currentDeleteFormId = null;

        function showDeleteModal(wishId, bookTitle) {
            currentDeleteFormId = wishId;
            document.getElementById('modalBookTitle').textContent = bookTitle;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentDeleteFormId = null;
        }

        function confirmDelete() {
            if (currentDeleteFormId) {
                document.getElementById('deleteForm' + currentDeleteFormId).submit();
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</x-layouts.wishlist> 