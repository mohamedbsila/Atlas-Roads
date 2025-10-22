<x-layouts.client>
<div class="mb-6">
    <a href="{{ route('home') }}#books" 
       class="inline-block px-4 py-2 mb-4 text-sm font-semibold text-slate-400 hover:text-slate-700 transition-colors duration-300">
        <i class="fas fa-arrow-left mr-1"></i> Back to list
    </a>
    <h1 class="text-5xl font-bold text-slate-800 tracking-wide">Book Details</h1>
</div>

<div class="flex flex-wrap lg:flex-nowrap gap-6">
    <!-- Left Column: Book Image & QR -->
    <div class="w-full lg:w-4/12 flex flex-col gap-6">
        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-500">
            <img src="{{ $book->image_url }}" alt="{{ $book->title }}" class="w-full h-96 object-cover hover:scale-105 transition-transform duration-500">
            <div class="p-8 space-y-4">
                <div class="text-center">
                    @if($book->is_available)
                        <span class="px-4 py-2 rounded-full bg-green-500 text-white font-semibold uppercase tracking-wider shadow-md inline-block animate-pulse">
                            <i class="fas fa-check mr-1"></i> Available
                        </span>
                    @else
                        <span class="px-4 py-2 rounded-full bg-gray-400 text-white font-semibold uppercase tracking-wider shadow-md inline-block">
                            <i class="fas fa-times mr-1"></i> Unavailable
                        </span>
                    @endif
                </div>

                <!-- QR Code -->
                <div class="bg-white rounded-2xl p-4 text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                    <h6 class="text-xs font-bold text-purple-700 mb-3 uppercase tracking-wide">
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
        <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-3xl shadow-lg p-8 space-y-4 hover:shadow-2xl transition-shadow duration-500">
            <h2 class="text-4xl font-bold text-slate-800 tracking-wide">{{ $book->title }}</h2>
            <div class="flex flex-wrap gap-4 text-slate-600 font-medium">
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
                    <i class="fas fa-star text-yellow-400 transition-transform hover:scale-125"></i>
                @endfor
                @if($halfStar)
                    <i class="fas fa-star-half-alt text-yellow-400 transition-transform hover:scale-125"></i>
                @endif
                @for($i = 0; $i < $emptyStars; $i++)
                    <i class="far fa-star text-gray-300 transition-transform hover:scale-125"></i>
                @endfor
                <span class="ml-2 font-semibold text-gray-600">{{ number_format($avg,1) }}/5</span>
            </div>
             
            <!-- Reviews Display -->
            <div class="mt-4 space-y-4 max-h-96 overflow-y-auto p-2 rounded-2xl">
                @forelse($book->reviews as $review)
                    <div class="bg-white rounded-2xl p-4 shadow-inner flex flex-col gap-2 hover:shadow-md hover:bg-gray-50 transition-all duration-300 relative">
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
                                    <span class="ml-2 text-gray-400 italic">{{ $review->created_at->format('M d, Y') }}</span>
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

             <!-- ===== Recommendations Section ===== -->
        <div class="bg-white rounded-3xl shadow-inner p-6 space-y-4 mt-6 hover:shadow-lg transition-shadow duration-300">
            <h4 class="text-xl font-bold text-slate-800 mb-4">Recommendations</h4>

            <!-- List Recommendations -->
            <div class="space-y-4 max-h-80 overflow-y-auto p-2 rounded-2xl">
                @forelse($book->recommendations as $rec)
                <div class="bg-gray-50 rounded-2xl p-4 shadow-sm hover:shadow-md transition duration-300">
                    <p><strong>{{ $rec->user->name }}</strong> recommends:</p>
                    <p class="text-slate-700">{{ $rec->message }}</p>
                    <span class="text-sm text-gray-500 italic">on {{ $rec->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @empty
                    <p class="text-gray-500">No recommendations yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Add Review & Manage Own Reviews -->
        @auth
        <div class="flex flex-col gap-6">
            <!-- Add Review -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-3xl shadow-lg p-6 hover:shadow-2xl transition-shadow duration-500">
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
                    <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-xl hover:scale-105 transition-transform duration-200">
                        Submit Review
                    </button>
                </form>
            </div>

            <!-- Edit/Delete/Flag Own Reviews -->
            @if($book->reviews->where('user_id', auth()->id())->count())
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-3xl shadow-lg p-6 space-y-4 hover:shadow-2xl transition-shadow duration-500">
                <h5 class="text-xl font-bold mb-4">Manage Your Reviews</h5>
                @foreach($book->reviews->where('user_id', auth()->id()) as $review)
                    <div class="bg-white rounded-2xl p-4 flex flex-col gap-2 shadow-inner hover:shadow-md transition-shadow duration-300">
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

        <!-- ======= Recommendations Block ======= -->
@auth
<div class="flex flex-col gap-6 mt-6">
    <!-- Add Recommendation -->
    <div class="bg-gradient-to-r from-green-50 to-lime-50 rounded-3xl shadow-lg p-6 hover:shadow-2xl transition-shadow duration-500">
        <h5 class="text-xl font-bold mb-4">Recommend this Book</h5>
        <form action="{{ route('recommendations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="book_id" value="{{ $book->id }}">
            <div class="mb-4">
                <label class="block font-semibold text-slate-700 mb-1">Message (optional)</label>
                <textarea name="message" rows="3" class="border rounded-lg p-2 w-full" placeholder="Why do you recommend this book?"></textarea>
                @error('message')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold rounded-xl hover:scale-105 transition-transform duration-200">
                Submit Recommendation
            </button>
        </form>
    </div>

    <!-- List of Recommendations -->
    <div class="mt-6 bg-white rounded-3xl shadow-inner p-6 max-h-80 overflow-y-auto p-2 rounded-2xl">
        <h5 class="text-lg font-semibold mb-4">User Recommendations</h5>

        @forelse($book->recommendations as $rec)
        <div class="mb-4 p-3 border rounded bg-gray-50 shadow-sm hover:shadow-md transition-shadow duration-300">
            <p><strong>{{ $rec->user->name }}</strong> recommends:</p>

            <!-- If current user is the author, show edit/delete form -->
            @if($rec->user_id == auth()->id())
            <form action="{{ route('recommendations.update', $rec) }}" method="POST" class="mb-2 flex gap-2">
                @csrf
                @method('PUT')
                <input type="text" name="message" value="{{ $rec->message }}" class="border rounded p-1 flex-1">
                <button type="submit" class="px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
            </form>
            <form action="{{ route('recommendations.destroy', $rec) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
            </form>
            @else
                <p class="italic text-gray-700">{{ $rec->message }}</p>
            @endif

            <span class="text-sm text-gray-500 italic">on {{ $rec->created_at->format('d/m/Y H:i') }}</span>
        </div>
        @empty
        <p class="text-gray-500">No recommendations yet.</p>
        @endforelse
    </div>
</div>
@endauth

    </div>
</div>

<!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

<!-- Scrollbar Custom -->
<style>
::-webkit-scrollbar { width: 8px; }
::-webkit-scrollbar-track { background: #f0f0f0; border-radius: 8px; }
::-webkit-scrollbar-thumb { background: #c4c4c4; border-radius: 8px; }
::-webkit-scrollbar-thumb:hover { background: #a0a0a0; }
</style>

</x-layouts.client>
