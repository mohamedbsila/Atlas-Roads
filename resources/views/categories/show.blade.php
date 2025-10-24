<x-layouts.app>
    <div>
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" 
               class="inline-block px-4 py-2 mb-4 text-size-xs font-bold text-slate-400 hover:text-slate-700">
                <i class="ni ni-bold-left mr-1"></i> Back to list
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="mb-0 font-bold">Category Details</h5>
                    <p class="text-sm text-slate-400 mt-1">{{ $category->category_name }}</p>
                </div>
                <a href="{{ route('categories.edit', $category) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-xs shadow-soft-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                   style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Edit Category</span>
                </a>
            </div>
        </div>

        <div class="flex flex-wrap -mx-3">
            <!-- Main Content -->
            <div class="w-full lg:w-8/12 px-3">
                <!-- Category Information Card -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-semibold text-slate-700">
                            <i class="ni ni-tag mr-2"></i>Category Information
                        </h6>

                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase">Category Name</label>
                                <p class="text-sm text-slate-700 mt-1">{{ $category->category_name }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-bold text-slate-600 uppercase">Description</label>
                                <p class="text-sm text-slate-700 mt-1">
                                    {{ $category->description ?: 'No description provided.' }}
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-600 uppercase">Created At</label>
                                    <p class="text-sm text-slate-700 mt-1">{{ $category->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-600 uppercase">Last Updated</label>
                                    <p class="text-sm text-slate-700 mt-1">{{ $category->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Books in Category -->
                @if($category->books->count() > 0)
                    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0">
                            <h6 class="mb-0 font-semibold text-slate-700">
                                <i class="ni ni-books mr-2"></i>Books in this Category
                            </h6>
                            <p class="text-sm text-slate-400 mt-1">{{ $category->books->count() }} books found</p>
                        </div>

                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-0 overflow-x-auto">
                                <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Book
                                            </th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Author
                                            </th>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                ISBN
                                            </th>
                                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->books as $book)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                                <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                    <div class="flex px-2 py-1">
                                                        <div>
                                                            <img src="{{ $book->image_url }}" 
                                                                 class="inline-flex items-center justify-center mr-4 text-white transition-all duration-200 ease-soft-in-out text-sm h-12 w-12 rounded-xl object-cover"
                                                                 alt="{{ $book->title }}">
                                                        </div>
                                                        <div class="flex flex-col justify-center">
                                                            <h6 class="mb-0 text-sm font-semibold text-slate-700">{{ $book->title }}</h6>
                                                            <p class="text-xs text-slate-400">{{ $book->published_year }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-2 align-middle bg-transparent border-b shadow-transparent">
                                                    <p class="mb-0 text-xs font-semibold text-slate-600 px-2">{{ $book->author }}</p>
                                                </td>
                                                <td class="p-2 align-middle bg-transparent border-b shadow-transparent">
                                                    <p class="mb-0 text-xs text-slate-500 px-2">{{ $book->isbn ?: 'N/A' }}</p>
                                                </td>
                                                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                    @if($book->is_available)
                                                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold text-white rounded-lg"
                                                              style="background:linear-gradient(135deg,#84cc16 0%,#4ade80 100%)">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            Available
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold text-white rounded-lg"
                                                              style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%)">
                                                            <i class="fas fa-times-circle mr-1"></i>
                                                            Unavailable
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-8 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 mb-4 rounded-full flex items-center justify-center" 
                                     style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)">
                                    <i class="ni ni-books text-3xl text-white"></i>
                                </div>
                                <h6 class="mb-2 text-lg font-semibold text-slate-600">No books in this category</h6>
                                <p class="text-sm text-slate-400">Books assigned to this category will appear here</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-4/12 px-3 mt-4 lg:mt-0">
                <!-- Statistics Card -->
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-semibold text-slate-700">
                            <i class="ni ni-chart-bar-32 mr-2"></i>Statistics
                        </h6>

                        <div class="p-4 mb-4 rounded-lg" style="background:linear-gradient(135deg,#667eea20 0%,#764ba220 100%)">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-slate-600 uppercase">Total Books</p>
                                    <h4 class="text-3xl font-bold text-slate-700">{{ $category->book_count }}</h4>
                                </div>
                                <div class="flex items-center justify-center w-16 h-16 rounded-lg" 
                                     style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)">
                                    <i class="ni ni-books text-2xl text-white"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-xs font-semibold text-slate-600">Available Books</span>
                                <span class="text-sm font-bold text-green-600">
                                    {{ $category->books->where('is_available', true)->count() }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-xs font-semibold text-slate-600">Unavailable Books</span>
                                <span class="text-sm font-bold text-red-600">
                                    {{ $category->books->where('is_available', false)->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-semibold text-slate-700">
                            <i class="ni ni-settings-gear-65 mr-2"></i>Quick Actions
                        </h6>

                        <div class="space-y-2">
                            <a href="{{ route('categories.edit', $category) }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <i class="fas fa-pencil-alt text-amber-500"></i>
                                Edit Category
                            </a>

                            <a href="{{ route('books.index', ['category' => $category->category_name]) }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <i class="ni ni-books text-blue-500"></i>
                                View All Books
                            </a>

                            @if($category->book_count == 0)
                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-white rounded-lg transition-all duration-200 hover:shadow-lg"
                                            style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%)">
                                        <i class="far fa-trash-alt"></i>
                                        Delete Category
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
