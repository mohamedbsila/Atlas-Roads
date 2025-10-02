<x-layouts.app>
    <div>
        <div class="mb-6">
            <a href="{{ route('books.index') }}" 
               class="inline-block px-4 py-2 mb-4 text-size-xs font-bold text-slate-400 hover:text-slate-700">
                <i class="ni ni-bold-left mr-1"></i> Back to list
            </a>
            <h5 class="mb-0 font-bold">Edit: {{ $book->title }}</h5>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="w-full lg:w-8/12 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-size-xs text-slate-700">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" value="{{ old('title', $book->title) }}"
                                       class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none @error('title') border-red-500 @enderror"
                                       placeholder="Book title">
                                @error('title')
                                    <p class="mt-2 text-size-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-size-xs text-slate-700">
                                    Author <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="author" value="{{ old('author', $book->author) }}"
                                       class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none @error('author') border-red-500 @enderror"
                                       placeholder="Author name">
                                @error('author')
                                    <p class="mt-2 text-size-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-wrap -mx-3 mb-4">
                                <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                                    <label class="inline-block mb-2 ml-1 font-bold text-size-xs text-slate-700">
                                        ISBN
                                    </label>
                                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                                           class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none @error('isbn') border-red-500 @enderror"
                                           placeholder="978-3-16-148410-0">
                                    @error('isbn')
                                        <p class="mt-2 text-size-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-full md:w-1/2 px-3">
                                    <label class="inline-block mb-2 ml-1 font-bold text-size-xs text-slate-700">
                                        Publication Year <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}"
                                           min="1900" max="2025"
                                           class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none @error('published_year') border-red-500 @enderror"
                                           placeholder="2023">
                                    @error('published_year')
                                        <p class="mt-2 text-size-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-wrap -mx-3 mb-4">
                                <div class="w-full md:w-1/2 px-3 mb-4 md:mb-0">
                                    <label class="inline-block mb-2 ml-1 font-bold text-size-xs text-slate-700">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="category" value="{{ old('category', $book->category) }}"
                                           class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none @error('category') border-red-500 @enderror"
                                           placeholder="Novel, Science-Fiction, etc.">
                                    @error('category')
                                        <p class="mt-2 text-size-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="w-full md:w-1/2 px-3">
                                    <label class="inline-block mb-2 ml-1 font-bold text-size-xs text-slate-700">
                                        Language <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="language" value="{{ old('language', $book->language) }}"
                                           class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none @error('language') border-red-500 @enderror"
                                           placeholder="French, English, etc.">
                                    @error('language')
                                        <p class="mt-2 text-size-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="inline-block mb-2 ml-1 font-bold text-size-xs text-slate-700">
                                    New Cover Image
                                </label>
                                @if($book->image)
                                    <div class="mb-3">
                                        <img src="{{ $book->image_url }}" alt="{{ $book->title }}" 
                                             class="h-32 w-auto rounded-lg shadow-soft-md">
                                        <p class="text-size-xs text-slate-400 mt-1">Current image</p>
                                    </div>
                                @endif
                                <input type="file" name="image" accept="image/jpeg,image/jpg,image/png"
                                       class="focus:shadow-soft-primary-outline text-size-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:outline-none @error('image') border-red-500 @enderror">
                                <p class="mt-1 text-size-xs text-slate-400">Accepted formats: JPG, JPEG, PNG (max 2MB)</p>
                                @error('image')
                                    <p class="mt-2 text-size-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_available" value="1" {{ $book->is_available ? 'checked' : '' }}
                                           class="w-5 h-5 ease-soft -ml-7 rounded-1.4 checked:bg-gradient-fuchsia checked:after:duration-250 checked:after:ease-soft-in-out duration-250 relative float-left mt-1 cursor-pointer appearance-none border border-solid border-slate-200 bg-white bg-contain bg-center bg-no-repeat align-top transition-all after:absolute after:flex after:h-full after:w-full after:items-center after:justify-center after:text-white after:opacity-0 after:transition-all after:content-['✔'] checked:border-0 checked:border-transparent checked:bg-transparent checked:after:opacity-100">
                                    <span class="ml-2 font-bold text-size-sm text-slate-700">
                                        Book available
                                    </span>
                                </label>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit"
                                        class="inline-block px-8 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-fuchsia rounded-lg cursor-pointer leading-pro text-size-xs ease-soft-in tracking-tight-soft shadow-soft-md hover:scale-102 hover:shadow-soft-xs active:opacity-85">
                                    <i class="ni ni-check-bold mr-1"></i> Update
                                </button>
                                <a href="{{ route('books.index') }}"
                                   class="inline-block px-8 py-3 font-bold text-center text-slate-700 uppercase align-middle transition-all bg-transparent border border-solid border-slate-700 rounded-lg cursor-pointer leading-pro text-size-xs ease-soft-in tracking-tight-soft hover:border-slate-900 hover:text-slate-900">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-4/12 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border p-6">
                    <h6 class="mb-4 font-bold">Information</h6>
                    <div class="text-size-sm text-slate-600">
                        <p class="mb-3">
                            <i class="ni ni-calendar-grid-58 text-slate-500 mr-2"></i>
                            Created: {{ $book->created_at->format('m/d/Y') }}
                        </p>
                        <p class="mb-3">
                            <i class="ni ni-time-alarm text-slate-500 mr-2"></i>
                            Modified: {{ $book->updated_at->format('m/d/Y') }}
                        </p>
                        <p class="mb-3">
                            <i class="ni ni-bulb-61 text-fuchsia-500 mr-2"></i>
                            Leave the image field empty to keep the current image
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

