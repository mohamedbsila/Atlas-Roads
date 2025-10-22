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

        <!-- Header Section -->
        <div class="flex flex-wrap justify-between items-center mb-6">
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">Book Management</h5>
                <p class="text-sm text-slate-400 mt-1">Manage your library collection</p>
            </div>
            <a href="{{ route('books.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-xs shadow-soft-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
               style="background:linear-gradient(135deg,#d946ef 0%,#ec4899 100%)">
                <i class="ni ni-fat-add"></i>
                <span>Add New Book</span>
            </a>
        </div>

        <!-- Filters and Search Section -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h6 class="mb-0 font-semibold text-slate-700">
                        <i class="ni ni-settings-gear-65 mr-2"></i>Search & Filters
                    </h6>
             
                </div>

                <form method="GET" action="{{ route('books.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <!-- Search Input -->
                        <div class="relative">
                            <label class="block mb-2 text-xs font-semibold text-slate-600 uppercase">
                                <i class="fas fa-search mr-1"></i>Search
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Title, author, ISBN..."
                                   class="w-full px-4 py-2.5 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent transition-all duration-200">
                        </div>

                        <!-- Category Filter -->
                        <div class="relative">
                            <label class="block mb-2 text-xs font-semibold text-slate-600 uppercase">
                                <i class="ni ni-tag mr-1"></i>Category
                            </label>
                            <select name="category"
                                    class="w-full px-4 py-2.5 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent transition-all duration-200 cursor-pointer">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Language Filter -->
                        <div class="relative">
                            <label class="block mb-2 text-xs font-semibold text-slate-600 uppercase">
                                <i class="ni ni-world-2 mr-1"></i>Language
                            </label>
                            <select name="language"
                                    class="w-full px-4 py-2.5 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent transition-all duration-200 cursor-pointer">
                                <option value="">All Languages</option>
                                @foreach($languages as $lang)
                                    <option value="{{ $lang }}" {{ request('language') == $lang ? 'selected' : '' }}>
                                        {{ $lang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Availability Filter -->
                        <div class="relative">
                            <label class="block mb-2 text-xs font-semibold text-slate-600 uppercase">
                                <i class="ni ni-check-bold mr-1"></i>Availability
                            </label>
                            <select name="available"
                                    class="w-full px-4 py-2.5 text-sm text-slate-700 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-fuchsia-400 focus:border-transparent transition-all duration-200 cursor-pointer">
                                <option value="">All Status</option>
                                <option value="1" {{ request('available') === '1' ? 'selected' : '' }}>Available</option>
                                <option value="0" {{ request('available') === '0' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="submit"
                                class="flex-1 px-6 py-3 font-bold text-center text-white uppercase rounded-lg text-xs shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                style="background:linear-gradient(135deg,#d946ef 0%,#ec4899 100%)">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('books.index') }}"
                           class="px-6 py-3 font-bold text-center text-slate-600 uppercase rounded-lg text-xs border-2 border-slate-300 hover:border-slate-400 hover:bg-slate-50 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>Clear All
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="flex flex-wrap -mx-3">
            @forelse($books as $book)
                <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                    <div class="relative flex flex-col h-full bg-white shadow-soft-xl rounded-2xl transition-all duration-300 hover:shadow-xl hover:-translate-y-1 overflow-hidden">
                        <div class="flex-auto p-4">
                            <div class="relative overflow-hidden rounded-xl mb-4 group">
                                <img src="{{ $book->image_url }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-full h-56 object-cover rounded-xl transition-transform duration-500 group-hover:scale-110"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/img/curved-images/curved14.jpg') }}';">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-black/10 to-transparent rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                <!-- Availability Badge -->
                                @if($book->is_available)
                                    <span class="absolute px-3 py-1.5 text-xs rounded-lg text-white font-bold backdrop-blur-sm"
                                          style="background-color: rgba(34, 197, 94, 0.9); z-index: 10; top: 12px; right: 12px; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);">
                                        <i class="ni ni-check-bold mr-1"></i> Available
                                    </span>
                                @else
                                    <span class="absolute px-3 py-1.5 text-xs rounded-lg text-white font-bold backdrop-blur-sm"
                                          style="background-color: rgba(100, 116, 139, 0.9); z-index: 10; top: 12px; right: 12px; box-shadow: 0 4px 12px rgba(100, 116, 139, 0.4);">
                                        <i class="ni ni-fat-remove mr-1"></i> Unavailable
                                    </span>
                                @endif
                            </div>

                            <!-- Book Info -->
                            <div class="mb-4">
                                <h5 class="mb-2 font-bold text-base leading-tight text-slate-700 hover:text-fuchsia-600 transition-colors duration-200">
                                    {{ Str::limit($book->title, 40) }}
                                </h5>
                                <p class="mb-1.5 text-sm text-slate-600 flex items-center">
                                    <i class="ni ni-single-02 mr-2 text-slate-400"></i> 
                                    <span>{{ Str::limit($book->author, 25) }}</span>
                                </p>
                                <p class="mb-1.5 text-xs text-slate-500 flex items-center">
                                    <i class="ni ni-tag mr-2 text-slate-400"></i> 
                                    <span>
                                        @if($book->category_id && $book->relationLoaded('category'))
                                            @php $cat = $book->getRelation('category'); @endphp
                                            {{ $cat ? $cat->category_name : 'N/A' }}
                                        @else
                                            {{ $book->getAttribute('category') ?? 'N/A' }}
                                        @endif
                                    </span>
                                </p>
                                <p class="mb-0 text-xs text-slate-500 flex items-center">
                                    <i class="ni ni-calendar-grid-58 mr-2 text-slate-400"></i> 
                                    <span>{{ $book->published_year }} • {{ $book->language }}</span>
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2 mt-auto pt-3 border-t border-slate-100">
                                <!-- View Button -->
                                <a href="{{ route('books.show', $book) }}"
                                   class="flex-1 px-3 py-2.5 text-center rounded-lg text-xs font-bold text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                   style="background:linear-gradient(135deg,#06b6d4 0%,#0ea5e9 100%)"
                                   title="View Details">
                                    <i class="ni ni-zoom-split-in"></i>
                                </a>
                                <!-- Edit Button -->
                                <a href="{{ route('books.edit', $book) }}"
                                   class="flex-1 px-3 py-2.5 text-center rounded-lg text-xs font-bold text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                   style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)"
                                   title="Edit Book">
                                    <i class="ni ni-settings"></i>
                                </a>
                                <!-- Delete Button -->
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="flex-1"
                                      onsubmit="return confirm('⚠️ Are you sure you want to delete « {{ $book->title }} »?\n\nThis action is irreversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-3 py-2.5 text-center rounded-lg text-xs font-bold text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                            style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%)"
                                            title="Delete Book">
                                        <i class="ni ni-basket"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-full px-3">
                    <div class="p-12 text-center bg-white rounded-2xl shadow-soft-xl">
                        <div class="inline-flex items-center justify-center w-20 h-20 mb-4 rounded-full bg-gradient-to-br from-slate-100 to-slate-200">
                            <i class="ni ni-books text-4xl text-slate-400"></i>
                        </div>
                        <h6 class="mb-2 font-bold text-slate-700">No books found</h6>
                        <p class="mb-4 text-sm text-slate-400">Try adjusting your search or filter criteria</p>
                        <a href="{{ route('books.index') }}" 
                           class="inline-block px-6 py-2.5 text-xs font-bold text-white uppercase rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                           style="background:linear-gradient(135deg,#d946ef 0%,#ec4899 100%)">
                            <i class="fas fa-redo mr-2"></i>Reset Filters
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="mt-6">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
