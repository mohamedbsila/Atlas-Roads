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
                <h5 class="mb-0 font-bold">Book Management</h5>
                <p class="text-size-sm text-slate-400">Manage your library</p>
            </div>
            <a href="{{ route('books.create') }}" 
               class="inline-block px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-size-xs shadow-md hover:scale-105 transition-all"
               style="background:linear-gradient(to right,#d946ef,#ec4899)">
                <i class="ni ni-fat-add mr-2"></i> Add Book
            </a>
        </div>

        <!-- Filtres et recherche -->
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4">
                <form method="GET" action="{{ route('books.index') }}">
                    <div class="w-full px-3 mb-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search title, author, ISBN..."
                               class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none">
                    </div>
                    <br>
                    <div class="w-full px-3 mb-4">
                        <select name="category"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none">
                            <option value="">Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="w-full px-3 mb-4">
                        <select name="language"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none">
                            <option value="">Language</option>
                            @foreach($languages as $lang)
                                <option value="{{ $lang }}" {{ request('language') == $lang ? 'selected' : '' }}>
                                    {{ $lang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="w-full px-3 mb-4">
                        <select name="available"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:outline-none">
                            <option value="">Availability</option>
                            <option value="1" {{ request('available') === '1' ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ request('available') === '0' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>
                    <br>
                    <div class="w-full px-3">
                        <button type="submit"
                                class="w-full px-6 py-3 font-bold text-center text-white uppercase rounded-lg text-size-xs shadow-md hover:scale-105 transition-all"
                                style="background:linear-gradient(to right,#d946ef,#ec4899)">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des livres -->
        <div class="flex flex-wrap -mx-3">
            @forelse($books as $book)
                <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                    <div class="relative flex flex-col h-full bg-white shadow rounded-2xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex-auto p-4">
                            <div class="relative overflow-hidden rounded-xl mb-3 group">
                                <img src="{{ $book->image_url }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-full h-48 object-cover rounded-xl transition-transform duration-300 group-hover:scale-110"
                                     onerror="this.src='{{ asset('assets/img/curved-images/curved14.jpg') }}'">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-xl" style="z-index: 1;"></div>

                                <!-- Availability Badge -->
                                @if($book->is_available)
                                    <span class="absolute px-3 py-1.5 text-xs rounded-lg text-white font-bold"
                                          style="background-color: #22c55e; z-index: 999; top: 8px; right: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.5);">
                                        <i class="ni ni-check-bold mr-1"></i> Available
                                    </span>
                                @else
                                    <span class="absolute px-3 py-1.5 text-xs rounded-lg text-white font-bold"
                                          style="background-color: #64748b; z-index: 999; top: 8px; right: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.5);">
                                        <i class="ni ni-fat-remove mr-1"></i> Unavailable
                                    </span>
                                @endif
                            </div>

                            <h5 class="mb-2 font-bold text-base leading-tight">{{ Str::limit($book->title, 40) }}</h5>
                            <p class="mb-1 text-sm text-slate-600">
                                <i class="ni ni-single-02 mr-1"></i> {{ Str::limit($book->author, 25) }}
                            </p>
                            <p class="mb-1 text-xs text-slate-400">
                                <i class="ni ni-tag mr-1"></i> {{ $book->category }}
                            </p>
                            <p class="mb-2 text-xs text-slate-400">
                                <i class="ni ni-calendar-grid-58 mr-1"></i> {{ $book->published_year }} | {{ $book->language }}
                            </p>

                            <div class="flex gap-1.5">
                                <!-- View Button -->
                                <a href="{{ route('books.show', $book) }}"
                                   class="flex-1 px-3 py-2 text-center rounded-lg text-xs font-bold text-white shadow-md hover:scale-105 transition-all"
                                   style="background:linear-gradient(to right,#06b6d4,#0ea5e9)">
                                    <i class="ni ni-zoom-split-in mr-1"></i>
                                    <span>View</span>
                                </a>
                                <!-- Edit Button -->
                                <a href="{{ route('books.edit', $book) }}"
                                   class="flex-1 px-3 py-2 text-center rounded-lg text-xs font-bold text-white shadow-md hover:scale-105 transition-all"
                                   style="background-color:#f59e0b; border:1px solid #d97706;">
                                    <i class="ni ni-settings mr-1"></i>
                                    <span>Edit</span>
                                </a>
                                <!-- Delete Button -->
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="flex-1"
                                      onsubmit="return confirm('⚠️ Are you sure you want to delete « {{ $book->title }} »?\n\nThis action is irreversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-3 py-2 text-center rounded-lg text-xs font-bold text-white shadow-md hover:scale-105 transition-all"
                                            style="background:linear-gradient(to right,#ef4444,#dc2626)">
                                        <i class="ni ni-basket mr-1"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-full px-3">
                    <div class="p-6 text-center bg-white rounded-2xl shadow">
                        <i class="ni ni-books text-6xl text-slate-300 mb-4"></i>
                        <p class="text-slate-400">No books found</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    </div>
</x-layouts.app>
