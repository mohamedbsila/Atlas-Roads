<x-layouts.app>
    <div>
        <div class="mb-6">
            <a href="{{ route('books.index') }}" 
               class="inline-block px-4 py-2 mb-4 text-size-xs font-bold text-slate-400 hover:text-slate-700">
                <i class="ni ni-bold-left mr-1"></i> Back to list
            </a>
            <h5 class="mb-0 font-bold">Book Details</h5>
        </div>

        <div class="flex flex-wrap -mx-3">
            <div class="w-full lg:w-4/12 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <div class="relative overflow-hidden rounded-xl mb-4">
                            <img src="{{ $book->image_url }}" alt="{{ $book->title }}" 
                                 class="w-full h-auto rounded-xl shadow-soft-lg">
                        </div>
                        <div class="text-center mb-4">
                            @if($book->is_available)
                                <span class="bg-gradient-lime px-4 py-2 text-size-sm rounded-lg text-white font-bold inline-block">
                                    <i class="ni ni-check-bold mr-1"></i> Available
                                </span>
                            @else
                                <span class="bg-gradient-slate px-4 py-2 text-size-sm rounded-lg text-white font-bold inline-block">
                                    <i class="ni ni-fat-remove mr-1"></i> Unavailable
                                </span>
                            @endif
                        </div>

                        @auth
                            @if($book->is_available && $book->ownerId !== auth()->id() && !is_null($book->price))
                            <div class="flex justify-center mb-2">
                                <form method="POST" action="{{ route('books.purchase', $book) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 text-white text-size-xs font-bold rounded-lg" style="background:linear-gradient(to right,#0ea5e9,#06b6d4)">
                                        <i class="ni ni-cart mr-2"></i> Acheter ({{ $book->price_formatted }})
                                    </button>
                                </form>
                            </div>
                            @endif
                        @endauth

                        <!-- QR Code -->
                        <div class="bg-gray-50 rounded-xl p-4 text-center">
                            <h6 class="text-xs font-bold text-slate-600 mb-3 uppercase">
                                <i class="ni ni-mobile-button mr-1"></i> QR Code
                            </h6>
                            <div class="flex justify-center mb-2">
                                {!! QrCode::size(180)->encoding('UTF-8')->generate(
                                    "ATLAS ROADS LIBRARY\n\n" .
                                    "Title: " . $book->title . "\n" .
                                    "Author: " . $book->author . "\n" .
                                    "Category: " . $book->category . "\n" .
                                    "Language: " . $book->language . "\n" .
                                    "Year: " . $book->published_year . "\n" .
                                    ($book->price_formatted ? "Price: " . $book->price_formatted . "\n" : "") .
                                    ($book->isbn ? "ISBN: " . $book->isbn . "\n" : "") .
                                    "Available: " . ($book->is_available ? "Yes" : "No")
                                ) !!}
                            </div>
                            <p class="text-xs text-slate-400">Scan to view book info</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-8/12 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border mb-6">
                    <div class="p-6">
                        <h3 class="mb-4 font-bold text-size-2xl">{{ $book->title }}</h3>
                        
                        <div class="mb-6">
                            <div class="flex items-start mb-4 pb-4 border-b border-gray-200">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-fuchsia mr-4">
                                    <i class="ni ni-single-02 text-white text-size-lg"></i>
                                </div>
                                <div>
                                    <p class="text-size-xs font-bold text-slate-400 uppercase mb-1">Author</p>
                                    <p class="text-size-lg font-semibold text-slate-700">{{ $book->author }}</p>
                                </div>
                            </div>

                            <div class="flex items-start mb-4 pb-4 border-b border-gray-200">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-cyan mr-4">
                                    <i class="ni ni-tag text-white text-size-lg"></i>
                                </div>
                                <div>
                                    <p class="text-size-xs font-bold text-slate-400 uppercase mb-1">Category</p>
                                    <p class="text-size-lg font-semibold text-slate-700">{{ $book->category }}</p>
                                </div>
                            </div>

                            <div class="flex items-start mb-4 pb-4 border-b border-gray-200">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-orange mr-4">
                                    <i class="ni ni-world-2 text-white text-size-lg"></i>
                                </div>
                                <div>
                                    <p class="text-size-xs font-bold text-slate-400 uppercase mb-1">Language</p>
                                    <p class="text-size-lg font-semibold text-slate-700">{{ $book->language }}</p>
                                </div>
                            </div>

                            <div class="flex items-start mb-4 pb-4 border-b border-gray-200">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-lime mr-4">
                                    <i class="ni ni-calendar-grid-58 text-white text-size-lg"></i>
                                </div>
                                <div>
                                    <p class="text-size-xs font-bold text-slate-400 uppercase mb-1">Publication Year</p>
                                    <p class="text-size-lg font-semibold text-slate-700">{{ $book->published_year }}</p>
                                </div>
                            </div>

                            @if(!is_null($book->price))
                            <div class="flex items-start mb-4 pb-4 border-b border-gray-200">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-cyan mr-4">
                                    <i class="ni ni-credit-card text-white text-size-lg"></i>
                                </div>
                                <div>
                                    <p class="text-size-xs font-bold text-slate-400 uppercase mb-1">Price</p>
                                    <p class="text-size-lg font-semibold text-slate-700">{{ $book->price_formatted }}</p>
                                </div>
                            </div>
                            @endif

                            @if($book->isbn)
                                <div class="flex items-start mb-4">
                                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-red mr-4">
                                        <i class="ni ni-archive-2 text-white text-size-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-size-xs font-bold text-slate-400 uppercase mb-1">ISBN</p>
                                        <p class="text-size-lg font-semibold text-slate-700">{{ $book->isbn }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6">
                        <h6 class="mb-4 font-bold">System Information</h6>
                        <div class="flex flex-wrap -mx-3">
                            <div class="w-full md:w-1/2 px-3 mb-4">
                                <div class="p-4 bg-gradient-to-r from-fuchsia-100 to-fuchsia-50 rounded-lg">
                                    <p class="text-size-xs font-bold text-slate-600 mb-1">Created Date</p>
                                    <p class="text-size-sm font-semibold text-slate-700">
                                        {{ $book->created_at->format('m/d/Y \a\t H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 px-3 mb-4">
                                <div class="p-4 bg-gradient-to-r from-cyan-100 to-cyan-50 rounded-lg">
                                    <p class="text-size-xs font-bold text-slate-600 mb-1">Last Modified</p>
                                    <p class="text-size-sm font-semibold text-slate-700">
                                        {{ $book->updated_at->format('m/d/Y \a\t H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

   
    </div>
</x-layouts.app>

