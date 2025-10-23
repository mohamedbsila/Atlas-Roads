<x-layouts.base>
    <style>
        body {
            background-color: #f7fafc;
        }

        /* Scroll pour reviews si beaucoup */
        .reviews-container {
            max-height: 300px;
            overflow-y: auto;
        }

        /* Scrollbar stylisée */
        .reviews-container::-webkit-scrollbar {
            width: 6px;
        }
        .reviews-container::-webkit-scrollbar-thumb {
            background-color: rgba(107, 114, 128, 0.5);
            border-radius: 3px;
        }
        .reviews-container::-webkit-scrollbar-track {
            background-color: transparent;
        }
    </style>
    
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('home') }}" 
               class="inline-block px-4 py-2 mb-4 text-sm font-bold text-slate-400 hover:text-slate-700 transition">
                <i class="ni ni-bold-left mr-1"></i> Back to catalog
            </a>
            <h5 class="mb-0 font-bold text-2xl text-slate-700">Book Details</h5>
        </div>

        <div class="flex flex-wrap -mx-3">
            <!-- Image du livre -->
            <div class="w-full lg:w-4/12 px-3 mb-6">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-lg rounded-2xl">
                    <div class="p-6">
                        <div class="relative overflow-hidden rounded-xl mb-4">
                            <img src="{{ $book->image_url }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full h-auto rounded-xl shadow-lg">
                        </div>
                        <div class="text-center mb-4">
                            @if($book->is_available)
                                <span class="bg-gradient-to-r from-green-400 to-green-600 px-4 py-2 text-sm rounded-lg text-white font-bold inline-block shadow-md">
                                    <i class="ni ni-check-bold mr-1"></i> Available
                                </span>
                            @else
                                <span class="bg-gradient-to-r from-gray-400 to-gray-600 px-4 py-2 text-sm rounded-lg text-white font-bold inline-block shadow-md">
                                    <i class="ni ni-fat-remove mr-1"></i> Unavailable
                                </span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <!-- Informations + Reviews -->
            <div class="w-full lg:w-8/12 px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-lg rounded-2xl mb-6">
                    <div class="p-6">
                        <h3 class="mb-4 font-bold text-3xl text-slate-800">{{ $book->title }}</h3>
                        
                        <div class="mb-6">
                            <!-- Author -->
                            <div class="flex items-start mb-4 pb-4 border-b border-gray-200">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-r from-purple-400 to-purple-600 mr-4">
                                    <i class="ni ni-single-02 text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Author</p>
                                    <p class="text-lg font-semibold text-slate-700">{{ $book->author }}</p>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="flex items-start mb-4 pb-4 border-b border-gray-200">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-r from-cyan-400 to-cyan-600 mr-4">
                                    <i class="ni ni-tag text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Category</p>
                                    <p class="text-lg font-semibold text-slate-700">
                                        @if($book->category_id && $book->relationLoaded('category'))
                                            @php $cat = $book->getRelation('category'); @endphp
                                            {{ $cat ? $cat->category_name : 'N/A' }}
                                        @else
                                            {{ $book->getAttribute('category') ?? 'N/A' }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Language -->
                            <div class="flex items-start mb-4 pb-4 border-b border-gray-200">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-r from-orange-400 to-orange-600 mr-4">
                                    <i class="ni ni-world-2 text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Language</p>
                                    <p class="text-lg font-semibold text-slate-700">{{ $book->language }}</p>
                                </div>
                            </div>

                            <!-- Publication Year -->
                            <div class="flex items-start mb-4 pb-4 border-b border-gray-200">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-r from-green-400 to-green-600 mr-4">
                                    <i class="ni ni-calendar-grid-58 text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Publication Year</p>
                                    <p class="text-lg font-semibold text-slate-700">{{ $book->published_year }}</p>
                                </div>
                            </div>

                            <!-- ISBN -->
                            @if($book->isbn)
                                <div class="flex items-start mb-4">
                                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-gradient-to-r from-red-400 to-red-600 mr-4">
                                        <i class="ni ni-archive-2 text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase mb-1">ISBN</p>
                                        <p class="text-lg font-semibold text-slate-700">{{ $book->isbn }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions and QR Code Row -->
                        <div class="flex flex-wrap -mx-3 mt-6">
                            <!-- Actions -->
                            <div class="w-full lg:w-1/2 px-3 mb-4">
                                <div class="flex flex-col gap-3">
                                    <a href="{{ route('home') }}" 
                                       class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-r from-purple-500 to-purple-700 rounded-lg cursor-pointer text-xs hover:scale-105 hover:shadow-lg">
                                        <i class="ni ni-bold-left mr-1"></i> Back
                                    </a>
                                    
                                    @guest
                                        <a href="{{ route('login') }}" 
                                           class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-r from-blue-500 to-blue-700 rounded-lg cursor-pointer text-xs hover:scale-105 hover:shadow-lg">
                                            <i class="ni ni-lock-circle-open mr-1"></i> Login to Borrow
                                        </a>
                                    @endguest
                                    
                                    @auth
                                        <a href="{{ route('books.show', $book) }}" 
                                           class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-gradient-to-r from-gray-600 to-gray-800 rounded-lg cursor-pointer text-xs hover:scale-105 hover:shadow-lg">
                                            <i class="ni ni-settings mr-1"></i> Manage (Admin)
                                        </a>
                                    @endauth
                                </div>
                            </div>

                            <!-- QR Code -->
                            <div class="w-full lg:w-1/2 px-3 mb-4">
                                <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-xl p-4 text-center h-full flex flex-col justify-center">
                                    <h6 class="text-xs font-bold text-purple-700 mb-3 uppercase">
                                        <i class="ni ni-mobile-button mr-1"></i> Book QR Code
                                    </h6>
                                    <div class="flex justify-center mb-2">
                                        {!! QrCode::size(140)->encoding('UTF-8')->generate(
                                            "ATLAS ROADS LIBRARY\n\n" .
                                            "Title: " . $book->title . "\n" .
                                            "Author: " . $book->author . "\n" .
                                            "Category: " . $book->category . "\n" .
                                            "Language: " . $book->language . "\n" .
                                            "Year: " . $book->published_year . "\n" .
                                            ($book->isbn ? "ISBN: " . $book->isbn . "\n" : "") .
                                            "Available: " . ($book->is_available ? "Yes" : "No")
                                        ) !!}
                                    </div>
                              
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Section Inside Info Card -->
                        <div class="mt-8">
    <h5 class="font-bold mb-4 text-xl text-slate-700">Reviews</h5>
    <div class="reviews-container space-y-4">
        @if($book->reviews->count() > 0)
            @foreach($book->reviews as $review)
                <div class="flex p-4 border rounded-lg bg-white shadow-sm hover:shadow-md transition transform hover:-translate-y-1">
                    <!-- Avatar -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-purple-200 flex items-center justify-center text-white font-bold text-lg mr-4">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    
                    <!-- Review Content -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <strong class="text-slate-800">{{ $review->user->name }}</strong>
                            <span class="text-xs text-gray-400">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @else
                                    <i class="far fa-star text-gray-300"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-slate-700">{{ $review->comment }}</p>
                        
                        @if($review->is_flagged)
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-bold text-red-600 bg-red-100 rounded-full">
                                <i class="fas fa-flag mr-1"></i> Flagged
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-slate-500 mt-2">No reviews for this book yet.</p>
        @endif
    </div>
</div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-layouts.base>