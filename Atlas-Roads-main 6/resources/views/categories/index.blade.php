<x-layouts.app>
    <div>
        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4 p-4 text-white rounded-xl flex items-center justify-between" 
                 style="background:linear-gradient(to right,#84cc16,#4ade80)">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="mb-4 p-4 text-white rounded-xl flex items-center justify-between" 
                 style="background:linear-gradient(to right,#ef4444,#dc2626)">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-wrap justify-between items-center mb-6">
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">Categories Management</h5>
                <p class="text-sm text-slate-400 mt-1">Organize your book collection by categories</p>
            </div>
            <a href="{{ route('categories.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-xs shadow-soft-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
               style="background:linear-gradient(135deg,#d946ef 0%,#ec4899 100%)">
                <i class="fa-solid fa-plus"></i>
                <span>Add New Category</span>
            </a>
        </div>

        <!-- Categories Table -->
        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0">
                <h6 class="font-semibold text-slate-700">
                    <i class="fa-solid fa-list-ul mr-2"></i>All Categories
                </h6>
                <p class="text-sm text-slate-400 mt-1">{{ $categories->total() }} total categories</p>
            </div>

            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-slate-400 opacity-70">
                                    ID
                                </th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-slate-400 opacity-70">
                                    Category Name
                                </th>
                                <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-slate-400 opacity-70">
                                    Description
                                </th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-slate-400 opacity-70">
                                    Books Count
                                </th>
                                <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 text-xxs text-slate-400 opacity-70">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                        <div class="flex px-4 py-1">
                                            <span class="text-sm font-semibold text-slate-600">#{{ $category->id }}</span>
                                        </div>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                                        <div class="flex px-2 py-1">
                                            <div class="flex items-center justify-center w-10 h-10 mr-3 text-white rounded-xl" 
                                                 style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)">
                                                <i class="fa-solid fa-tag text-lg"></i>
                                            </div>
                                            <div class="flex flex-col justify-center">
                                                <h6 class="mb-0 text-sm font-semibold text-slate-700">{{ $category->category_name }}</h6>
                                                <p class="text-xs text-slate-400">Created {{ $category->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b">
                                        <p class="mb-0 text-xs font-normal text-slate-500 px-2">
                                            {{ Str::limit($category->description, 60) ?: 'No description' }}
                                        </p>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center px-3 py-1 text-xs font-bold text-white rounded-lg"
                                              style="background:linear-gradient(135deg,#06b6d4 0%,#0ea5e9 100%)">
                                            <i class="fa-solid fa-book mr-1"></i>
                                            {{ $category->books_count }}
                                        </span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- View --}}
                                            <a href="{{ route('categories.show', $category) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 text-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                               style="background:linear-gradient(135deg,#06b6d4 0%,#0ea5e9 100%)"
                                               title="View Details">
                                                <i class="fa-solid fa-eye text-base"></i>
                                            </a>
                                            {{-- Edit --}}
                                            <a href="{{ route('categories.edit', $category) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 text-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                               style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)"
                                               title="Edit">
                                                <i class="fa-solid fa-pen text-base"></i>
                                            </a>
                                            {{-- Delete --}}
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-8 h-8 text-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                                        style="background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%)"
                                                        title="Delete">
                                                    <i class="fa-regular fa-trash-can text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 mb-4 rounded-full flex items-center justify-center" 
                                                 style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)">
                                                <i class="fa-solid fa-tag text-3xl text-white"></i>
                                            </div>
                                            <h6 class="mb-2 text-lg font-semibold text-slate-600">No categories found</h6>
                                            <p class="text-sm text-slate-400">Start by creating your first category</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($categories->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
