<x-layouts.app>
    <div>
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" 
               class="inline-block px-4 py-2 mb-4 text-size-xs font-bold text-slate-400 hover:text-slate-700">
                <i class="ni ni-bold-left mr-1"></i> Back to list
            </a>
            <h5 class="mb-0 font-bold">Edit Category</h5>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="w-full lg:w-8/12 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <form action="{{ route('categories.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-size-xs text-slate-700">
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="category_name" value="{{ old('category_name', $category->category_name) }}"
                                       class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none @error('category_name') border-red-500 @enderror"
                                       placeholder="Enter category name (minimum 3 characters)" required>
                                @error('category_name')
                                    <p class="mt-2 text-size-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-size-xs text-slate-700">
                                    Description
                                </label>
                                <textarea name="description" rows="5"
                                          class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none @error('description') border-red-500 @enderror"
                                          placeholder="Enter category description (maximum 500 characters)">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-size-xs text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-slate-400">Maximum 500 characters</p>
                            </div>

                            <div class="flex gap-2 mt-6">
                                <a href="{{ route('categories.index') }}"
                                   class="inline-block px-6 py-3 font-bold text-center text-slate-500 uppercase align-middle transition-all bg-transparent border border-slate-500 rounded-lg cursor-pointer hover:scale-102 leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:border-slate-700 hover:text-slate-700">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85 hover:shadow-soft-xs"
                                        style="background:linear-gradient(135deg,#f59e0b 0%,#d97706 100%)">
                                    Update Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="w-full lg:w-4/12 px-3 mt-4 lg:mt-0">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-0 font-semibold text-slate-700">
                            <i class="ni ni-chart-bar-32 mr-2"></i>Category Statistics
                        </h6>
                        <hr class="h-px my-4 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent">
                        
                        <div class="space-y-4">
                            <div class="p-4 rounded-lg" style="background:linear-gradient(135deg,#667eea20 0%,#764ba220 100%)">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-semibold text-slate-600 uppercase">Books Count</p>
                                        <h4 class="text-2xl font-bold text-slate-700">{{ $category->book_count }}</h4>
                                    </div>
                                    <div class="flex items-center justify-center w-12 h-12 rounded-lg" 
                                         style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)">
                                        <i class="ni ni-books text-lg text-white"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-slate-600 uppercase">Created</p>
                                <p class="text-sm text-slate-500 mt-1">{{ $category->created_at->format('d M Y, H:i') }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-semibold text-slate-600 uppercase">Last Updated</p>
                                <p class="text-sm text-slate-500 mt-1">{{ $category->updated_at->format('d M Y, H:i') }}</p>
                            </div>

                            @if($category->book_count > 0)
                                <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                    <p class="text-xs text-amber-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        This category contains {{ $category->book_count }} book(s) and cannot be deleted.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
