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

        <div class="flex flex-wrap justify-between items-center mb-6">
            <div>
                <h5 class="mb-0 font-bold text-2xl">📚 Wishlist Management</h5>
                <p class="text-size-sm text-slate-400">Manage all book requests from users</p>
            </div>
            <a href="{{ route('admin.wishlist.dashboard') }}" 
               class="inline-block px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-size-xs shadow-md hover:scale-105 transition-all"
               style="background:linear-gradient(to right,#06b6d4,#3b82f6)">
                <i class="fas fa-chart-line mr-2"></i> Dashboard
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-soft-xl p-4">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 uppercase">Total</p>
                        <h4 class="text-2xl font-bold">{{ $stats['total'] }}</h4>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-soft-xl p-4">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 uppercase">Pending</p>
                        <h4 class="text-2xl font-bold text-gray-600">{{ $stats['pending'] }}</h4>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-soft-xl p-4">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs text-blue-500 uppercase">Searching</p>
                        <h4 class="text-2xl font-bold text-blue-600">{{ $stats['searching'] }}</h4>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-search text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-soft-xl p-4">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs text-green-500 uppercase">Found</p>
                        <h4 class="text-2xl font-bold text-green-600">{{ $stats['found'] }}</h4>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-soft-xl p-4">
                <div class="flex items-center">
                    <div class="flex-1">
                        <p class="text-xs text-red-500 uppercase">Rejected</p>
                        <h4 class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</h4>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4">
                <form method="GET" action="{{ route('admin.wishlist.index') }}" class="flex gap-4 flex-wrap items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="text-xs font-semibold text-gray-700 mb-1 block">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Title, author, user..."
                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1 block">Status</label>
                        <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            <option value="">All Statuses</option>
                            <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="SEARCHING" {{ request('status') == 'SEARCHING' ? 'selected' : '' }}>Searching</option>
                            <option value="FOUND" {{ request('status') == 'FOUND' ? 'selected' : '' }}>Found</option>
                            <option value="ORDERED" {{ request('status') == 'ORDERED' ? 'selected' : '' }}>Ordered</option>
                            <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1 block">Priority</label>
                        <select name="priority" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            <option value="">All Priorities</option>
                            <option value="HIGH" {{ request('priority') == 'HIGH' ? 'selected' : '' }}>High</option>
                            <option value="MEDIUM" {{ request('priority') == 'MEDIUM' ? 'selected' : '' }}>Medium</option>
                            <option value="LOW" {{ request('priority') == 'LOW' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <button type="submit" class="px-6 py-2 font-bold text-white rounded-lg text-size-xs shadow-md hover:scale-105 transition-all"
                            style="background:linear-gradient(to right,#d946ef,#ec4899)">
                        Filter
                    </button>
                    <a href="{{ route('admin.wishlist.index') }}" class="px-6 py-2 font-bold text-gray-700 bg-gray-200 rounded-lg text-size-xs shadow-md hover:scale-105 transition-all">
                        Clear
                    </a>
                </form>
            </div>
        </div>

        <!-- Wishlist Table -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                @if($wishes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 px-2 font-semibold text-gray-700">Image</th>
                                    <th class="text-left py-3 px-2 font-semibold text-gray-700">User</th>
                                    <th class="text-left py-3 px-2 font-semibold text-gray-700">Book Details</th>
                                    <th class="text-left py-3 px-2 font-semibold text-gray-700">Priority</th>
                                    <th class="text-left py-3 px-2 font-semibold text-gray-700">Status</th>
                                    <th class="text-left py-3 px-2 font-semibold text-gray-700">Votes</th>
                                    <th class="text-left py-3 px-2 font-semibold text-gray-700">Date</th>
                                    <th class="text-left py-3 px-2 font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wishes as $wish)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-2">
                                            @if($wish->image)
                                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 shadow-sm">
                                                    <img src="{{ asset('storage/' . $wish->image) }}" 
                                                         alt="{{ $wish->title }}" 
                                                         class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                    <i class="fas fa-book text-gray-400 text-xl"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-2">
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $wish->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $wish->user->email }}</p>
                                            </div>
                                        </td>
                                        <td class="py-3 px-2">
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $wish->title }}</p>
                                                <p class="text-xs text-gray-500">by {{ $wish->author }}</p>
                                                @if($wish->isbn)
                                                    <p class="text-xs text-gray-400">ISBN: {{ $wish->isbn }}</p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-2">
                                            <span class="px-2 py-1 text-xs font-semibold rounded"
                                                  style="background: {{ $wish->priority_color === 'red' ? '#fee2e2' : ($wish->priority_color === 'yellow' ? '#fef3c7' : '#dcfce7') }}; color: {{ $wish->priority_color === 'red' ? '#991b1b' : ($wish->priority_color === 'yellow' ? '#92400e' : '#166534') }}">
                                                {{ $wish->priority_label }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2">
                                            <span class="px-3 py-1 text-xs font-bold text-white rounded-full"
                                                  style="background: {{ $wish->status_color === 'gray' ? '#6b7280' : ($wish->status_color === 'blue' ? '#3b82f6' : ($wish->status_color === 'green' ? '#10b981' : ($wish->status_color === 'purple' ? '#a855f7' : '#ef4444'))) }}">
                                                {{ $wish->status_label }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2">
                                            <span class="text-gray-600">
                                                <i class="fas fa-heart text-pink-500"></i> {{ $wish->votes_count }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-2">
                                            <p class="text-xs text-gray-500">{{ $wish->created_at->format('M d, Y') }}</p>
                                        </td>
                                        <td class="py-3 px-2">
                                            <a href="{{ route('admin.wishlist.show', $wish) }}" 
                                               class="inline-block px-3 py-1 text-xs font-bold text-white bg-blue-500 rounded hover:bg-blue-600">
                                                Manage
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $wishes->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-600 mb-2">No Wishlist Requests</h3>
                        <p class="text-gray-400">No requests match your filters</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app> 