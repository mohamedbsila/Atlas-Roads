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

        @if(session('error'))
            <div class="mb-4 p-4 text-white rounded-xl flex items-center justify-between" 
                 style="background:linear-gradient(to right,#ef4444,#dc2626)">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('wishlist.index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back to Wishlist
            </a>
        </div>

        <!-- Main Card -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border overflow-hidden">
            <!-- Book Cover Image -->
            @if($wishlist->image)
                <div class="relative h-64 md:h-80 bg-gradient-to-br from-gray-100 to-gray-200">
                    <img src="{{ asset('storage/' . $wishlist->image) }}" 
                         alt="{{ $wishlist->title }}" 
                         class="w-full h-full object-contain">
                </div>
            @endif

            <div class="p-6">
                <!-- Header with Status and Priority -->
                <div class="flex flex-wrap justify-between items-start mb-6">
                    <div>
                        <h5 class="mb-2 font-bold text-3xl">{{ $wishlist->title }}</h5>
                        <p class="text-lg text-slate-600">by {{ $wishlist->author }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="px-4 py-2 text-sm font-bold text-white rounded-lg text-center"
                              style="background: {{ $wishlist->status_color === 'gray' ? '#6b7280' : ($wishlist->status_color === 'blue' ? '#3b82f6' : ($wishlist->status_color === 'green' ? '#10b981' : ($wishlist->status_color === 'purple' ? '#a855f7' : '#ef4444'))) }}">
                            {{ $wishlist->status_label }}
                        </span>
                        <span class="px-3 py-2 text-sm font-semibold rounded-lg text-center"
                              style="background: {{ $wishlist->priority_color === 'red' ? '#fee2e2' : ($wishlist->priority_color === 'yellow' ? '#fef3c7' : '#dcfce7') }}; color: {{ $wishlist->priority_color === 'red' ? '#991b1b' : ($wishlist->priority_color === 'yellow' ? '#92400e' : '#166534') }}">
                            {{ $wishlist->priority_label }} Priority
                        </span>
                    </div>
                </div>

                <!-- ISBN -->
                @if($wishlist->isbn)
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-slate-600 mb-1">ISBN</p>
                        <p class="text-slate-800">{{ $wishlist->isbn }}</p>
                    </div>
                @endif

                <!-- Description -->
                @if($wishlist->description)
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-slate-600 mb-2">Description</p>
                        <p class="text-slate-700">{{ $wishlist->description }}</p>
                    </div>
                @endif

                <!-- Max Price -->
                @if($wishlist->max_price)
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-slate-600 mb-1">Maximum Price You're Willing to Pay</p>
                        <p class="text-green-600 font-bold text-lg">${{ number_format($wishlist->max_price, 2) }}</p>
                    </div>
                @endif

                <!-- Admin Notes -->
                @if($wishlist->admin_notes)
                    <div class="bg-blue-50 p-4 rounded-lg mb-4">
                        <p class="text-sm font-bold text-blue-800 mb-2">📝 Admin Notes</p>
                        <p class="text-sm text-blue-700">{{ $wishlist->admin_notes }}</p>
                    </div>
                @endif

                <!-- Estimated Info -->
                @if($wishlist->estimated_price || $wishlist->estimated_days)
                    <div class="bg-purple-50 p-4 rounded-lg mb-4">
                        <p class="text-sm font-bold text-purple-800 mb-3">📊 Estimates from Admin</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($wishlist->estimated_price)
                                <div>
                                    <p class="text-xs text-purple-600 mb-1">Estimated Price</p>
                                    <p class="text-green-600 font-bold text-xl">${{ number_format($wishlist->estimated_price, 2) }}</p>
                                </div>
                            @endif
                            @if($wishlist->estimated_days)
                                <div>
                                    <p class="text-xs text-purple-600 mb-1">Estimated Delivery</p>
                                    <p class="text-blue-600 font-bold text-xl">{{ $wishlist->estimated_days }} days</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- If Found and Linked to Book -->
                @if($wishlist->is_found && $wishlist->book)
                    <div class="bg-green-50 p-4 rounded-lg mb-4 border-2 border-green-200">
                        <p class="text-sm font-bold text-green-800 mb-3">✅ Great News! This Book is Now Available</p>
                        <div class="flex gap-4 items-center">
                            <div class="flex-1">
                                <p class="text-sm text-green-700 mb-1">Found on: {{ $wishlist->found_at?->format('M d, Y') }}</p>
                                <a href="{{ route('books.show', $wishlist->book) }}" 
                                   class="inline-block mt-2 px-6 py-2 font-bold text-white rounded-lg text-size-xs shadow-md hover:scale-105 transition-all"
                                   style="background:linear-gradient(to right,#10b981,#059669)">
                                    <i class="fas fa-book mr-2"></i> View Book in Library
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Community Votes -->
                <div class="bg-slate-50 p-4 rounded-lg mb-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-800 mb-1">👥 Community Interest</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $wishlist->votes->count() }} {{ $wishlist->votes->count() === 1 ? 'Vote' : 'Votes' }}</p>
                        </div>
                        @if($wishlist->votes->count() > 0)
                            <div>
                                <p class="text-xs text-slate-500 mb-2">Recent voters:</p>
                                <div class="flex -space-x-2">
                                    @foreach($wishlist->votes->take(5) as $voter)
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white text-xs font-bold border-2 border-white"
                                             title="{{ $voter->name }}">
                                            {{ strtoupper(substr($voter->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($wishlist->votes->count() > 5)
                                        <div class="w-8 h-8 rounded-full bg-slate-300 flex items-center justify-center text-slate-600 text-xs font-bold border-2 border-white">
                                            +{{ $wishlist->votes->count() - 5 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Feedback Section -->
                @if($wishlist->service_rating || $wishlist->feedback)
                    <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                        <p class="text-sm font-bold text-yellow-800 mb-3">⭐ Your Feedback</p>
                        @if($wishlist->service_rating)
                            <div class="mb-2">
                                <p class="text-xs text-yellow-600 mb-1">Service Rating</p>
                                <div class="flex gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $wishlist->service_rating ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        @endif
                        @if($wishlist->feedback)
                            <div>
                                <p class="text-xs text-yellow-600 mb-1">Your Comments</p>
                                <p class="text-sm text-yellow-700">{{ $wishlist->feedback }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Timeline Info -->
                <div class="border-t pt-4 mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-500">Requested On</p>
                            <p class="font-semibold text-slate-700">{{ $wishlist->created_at->format('M d, Y \a\t g:i A') }}</p>
                            <p class="text-xs text-slate-400">{{ $wishlist->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Last Updated</p>
                            <p class="font-semibold text-slate-700">{{ $wishlist->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            <p class="text-xs text-slate-400">{{ $wishlist->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 mt-6 pt-6 border-t">
                    @if($wishlist->canBeCancelled())
                        <form action="{{ route('wishlist.destroy', $wishlist) }}" method="POST" class="flex-1" id="deleteFormShow">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    onclick="showDeleteModal()"
                                    class="w-full px-6 py-3 font-bold text-white bg-red-500 rounded-lg text-size-sm shadow-md hover:scale-105 transition-all">
                                <i class="fas fa-times-circle mr-2"></i> Cancel Request
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('wishlist.index') }}" 
                       class="flex-1 px-6 py-3 text-center font-bold text-white rounded-lg text-size-sm shadow-md hover:scale-105 transition-all"
                       style="background:linear-gradient(to right,#06b6d4,#3b82f6)">
                        <i class="fas fa-list mr-2"></i> View All Wishes
                    </a>
                </div>
            </div>
        </div>
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
                                <p class="text-base font-bold text-gray-800 bg-white px-4 py-3 rounded-xl shadow-sm">
                                    📚 {{ $wishlist->title }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2 italic">
                                    by {{ $wishlist->author }}
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
        function showDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function confirmDelete() {
            document.getElementById('deleteFormShow').submit();
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</x-layouts.wishlist> 