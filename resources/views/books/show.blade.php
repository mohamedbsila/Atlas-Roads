<x-layouts.app>
<div class="mb-6">
    <a href="{{ route('books.index') }}" 
       class="inline-block px-4 py-2 mb-4 text-sm font-semibold text-slate-400 hover:text-slate-700 transition">
        <i class="fas fa-arrow-left mr-1"></i> Back to list
    </a>
    <h1 class="text-4xl font-bold text-slate-800">Book Details</h1>
</div>

<div class="flex flex-wrap lg:flex-nowrap gap-6">
    <!-- Left Column: Book Image & QR -->
    <div class="w-full lg:w-4/12 flex flex-col gap-6">
        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition duration-300">
            <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="w-full h-96 object-cover">
            <div class="p-6 space-y-4">
                <div class="text-center">
                    @if($book->is_available)
                        <span class="px-4 py-2 rounded-full bg-green-500 text-white font-semibold shadow-md inline-block">
                            <i class="fas fa-check mr-1"></i> Available
                        </span>
                    @else
                        <span class="px-4 py-2 rounded-full bg-gray-400 text-white font-semibold shadow-md inline-block">
                            <i class="fas fa-times mr-1"></i> Unavailable
                        </span>
                    @endif
                </div>

                <!-- QR Code -->
                <div class="bg-white rounded-2xl p-4 text-center shadow-inner">
                    <h6 class="text-xs font-bold text-purple-700 mb-3 uppercase">
                        <i class="fas fa-qrcode mr-1"></i> QR Code
                    </h6>
                    <div class="flex justify-center mb-2">
                        {!! QrCode::size(160)->generate(
                            "ATLAS ROADS LIBRARY\nTitle: {$book->title}\nAuthor: {$book->author}\nCategory: {$book->category}\nLanguage: {$book->language}\nYear: {$book->published_year}" .
                            ($book->isbn ? "\nISBN: {$book->isbn}" : "") .
                            "\nAvailable: " . ($book->is_available ? "Yes" : "No")
                        ) !!}
                    </div>
                    <p class="text-xs text-purple-400">Scan to view book info</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Info + Reviews + Actions -->
    <div class="w-full lg:w-8/12 flex flex-col gap-6">
        <!-- Book Info Card -->
        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-3xl shadow-xl p-6 space-y-4 hover:shadow-2xl transition duration-300">
            <h2 class="text-3xl font-bold text-slate-800">{{ $book->title }}</h2>
            <div class="flex flex-wrap gap-4 text-slate-700">
                <p><span class="font-semibold">Author:</span> {{ $book->author }}</p>
                <p><span class="font-semibold">Category:</span> {{ $book->category }}</p>
                <p><span class="font-semibold">Language:</span> {{ $book->language }}</p>
                <p><span class="font-semibold">Year:</span> {{ $book->published_year }}</p>
                @if($book->isbn)
                    <p><span class="font-semibold">ISBN:</span> {{ $book->isbn }}</p>
                @endif
            </div>

            <!-- Average Rating -->
            <div class="flex items-center gap-2 mt-2">
                @php
                    $avg = $book->reviews->avg('rating') ?? 0;
                    $fullStars = floor($avg);
                    $halfStar = ($avg - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp
                @for($i = 0; $i < $fullStars; $i++)
                    <i class="fas fa-star text-yellow-400 transition-transform hover:scale-110"></i>
                @endfor
                @if($halfStar)
                    <i class="fas fa-star-half-alt text-yellow-400 transition-transform hover:scale-110"></i>
                @endif
                @for($i = 0; $i < $emptyStars; $i++)
                    <i class="far fa-star text-gray-300 transition-transform hover:scale-110"></i>
                @endfor
                <span class="ml-2 font-semibold text-gray-600">{{ number_format($avg,1) }}/5</span>
            </div>

            <!-- Reviews Display -->
            <div class="mt-4 space-y-4 max-h-96 overflow-y-auto">
                @forelse($book->reviews as $review)
                    <div class="bg-white rounded-2xl p-4 shadow-inner flex flex-col gap-2 hover:shadow-md transition duration-200 relative">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
                                <img src="{{ $review->user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}" alt="avatar" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <strong class="text-slate-800">{{ $review->user->name }}</strong>
                                <div class="flex items-center gap-1 text-sm mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-yellow-400"></i>
                                        @else
                                            <i class="far fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-gray-400">{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                            @if($review->is_flagged)
                                <span class="absolute top-2 right-2 px-2 py-1 text-xs font-bold text-red-600 bg-red-100 rounded-full animate-pulse flex items-center gap-1">
                                    <i class="fas fa-flag"></i> Flagged
                                </span>
                            @endif
                        </div>
                        <p class="text-slate-700 ml-13">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No reviews yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Add Review & Manage Own Reviews -->
        @auth
        <div class="flex flex-col gap-6">
            <!-- Add Review -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-3xl shadow-xl p-6 hover:shadow-2xl transition">
                <h5 class="text-xl font-bold mb-4">Add a Review</h5>
                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <div class="mb-4">
                        <label class="block font-semibold text-slate-700 mb-1">Rating</label>
                        <select name="rating" class="border rounded-lg p-2 w-full">
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold text-slate-700 mb-1">Comment</label>
                        <textarea name="comment" rows="3" class="border rounded-lg p-2 w-full"></textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700">
                        Submit Review
                    </button>
                </form>
            </div>

            <!-- Edit/Delete/Flag Own Reviews -->
            @if($book->reviews->where('user_id', auth()->id())->count())
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-3xl shadow-xl p-6 space-y-4 hover:shadow-2xl transition">
                <h5 class="text-xl font-bold mb-4">Manage Your Reviews</h5>
                @foreach($book->reviews->where('user_id', auth()->id()) as $review)
                    <div class="bg-white rounded-2xl p-4 flex flex-col gap-2 shadow-inner">
                        <p class="text-slate-700">{{ $review->comment }}</p>
                        <div class="flex items-center gap-2 flex-wrap">
                            <!-- Edit -->
                            <form action="{{ route('reviews.update', $review) }}" method="POST" class="flex-1 flex gap-2">
                                @csrf
                                @method('PUT')
                                <select name="rating" class="border rounded-lg p-1">
                                    @for($i=1; $i<=5; $i++)
                                        <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <input type="text" name="comment" value="{{ $review->comment }}" class="border rounded-lg p-1 flex-1">
                                <button type="submit" class="px-2 py-1 bg-green-500 text-white rounded-lg hover:bg-green-600">Save</button>
                            </form>

                            <!-- Delete -->
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="px-2 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600">Delete</button>
                            </form>

                            <!-- Flag -->
                            <form action="{{ route('reviews.flag', $review) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="px-2 py-1 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Flag</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
        @else
            <p class="text-gray-500 text-center mt-2">Login to add or manage your reviews.</p>
        @endauth
    </div>
</div>

<!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
</x-layouts.app>