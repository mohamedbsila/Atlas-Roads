<x-layouts.base>
    <!-- Add custom styles -->
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/home/style.css') }}">
        <style>
            /* Fix pour afficher tous les livres du carousel */
            .carousel .list .item:nth-child(n + 6){
                opacity: 1 !important;
            }
            .carousel .list .item:nth-child(6){
                transform: translate(140%,35%) scale(0.25);
                filter: blur(50px);
                z-index: 6;
                opacity: 0;
                pointer-events: none;
            }
            
            /* Clean Books Section */
            .books-section {
                background: #f8f9fa;
                padding: 60px 0;
            }
            
            /* Section Title */
            .section-title {
                color: #2d3748;
                margin-bottom: 0.5rem;
            }
            
            .section-subtitle {
                color: #718096;
            }
            
            /* Simple Book Card */
            .book-card {
                background: white;
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                height: 100%;
            }
            
            .book-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 16px rgba(0,0,0,0.12);
            }
            
            /* Book Image */
            .book-image-container {
                position: relative;
                overflow: hidden;
                background: #f1f5f9;
            }
            
            .book-image {
                width: 100%;
                height: 320px;
                object-fit: cover;
                transition: transform 0.3s ease;
            }
            
            .book-card:hover .book-image {
                transform: scale(1.05);
            }
            
            /* Badges */
            .badge-available {
                background: #10b981;
                color: white;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            
            .badge-unavailable {
                background: #6b7280;
                color: white;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
            }
            
            /* Book Info */
            .book-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: #1a202c;
                margin-bottom: 0.75rem;
                line-height: 1.4;
            }
            
            .book-info {
                color: #64748b;
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }
            
            .book-info i {
                margin-right: 0.5rem;
                color: #94a3b8;
            }
            
            /* Action Button */
            .book-action-btn {
                background: #3b82f6;
                color: white;
                transition: all 0.3s ease;
            }
            
            .book-action-btn:hover {
                background: #2563eb;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }
            
            /* Header */
            header {
                position: sticky;
                top: 0;
                z-index: 50;
                background: white;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            
            /* Pagination */
            .pagination-container {
                background: white;
                border-radius: 8px;
                padding: 1rem;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            }
            
            /* Responsive */
            @media (max-width: 768px) {
                header nav {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                    justify-content: center;
                }
                header nav a {
                    font-size: 0.875rem;
                }
                .book-image {
                    height: 250px;
                }
            }
        </style>
    @endpush
    
<header class="bg-white shadow-md w-full">
    <div class="px-8 py-3 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <img src="{{ asset('assets/img/logo/logo black.png') }}" alt="Atlas Roads" class="h-8">
            <span class="logo text-xl font-bold text-blue-600">Atlas Roads</span>
        </div>
        <nav class="flex items-center space-x-5">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                <i class="fas fa-home mr-1"></i> Home
            </a>
            <a href="#books" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                <i class="fas fa-book mr-1"></i> Books
            </a>
            @auth
                <a href="{{ route('wishlist.index') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                    <i class="fas fa-heart mr-1"></i> My Wishlist
                </a>
                <a href="{{ route('wishlist.create') }}" class="px-3 py-1.5 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                    <i class="fas fa-plus mr-1"></i> Request Book
                </a>
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </a>
            @endauth
        </nav>
    </div>
</header>

<main>
    <div class="carousel">
        <div class="list">
            @forelse($carouselBooks as $carouselBook)
                <div class="item">
                    <img src="{{ $carouselBook->image_url }}" class="img" alt="{{ $carouselBook->title }}">
                    <div class="introduce">
                        <div class="author">{{ $carouselBook->author }}</div>
                        <div class="title">{{ $carouselBook->title }}</div>
                        <div class="topic">{{ $carouselBook->category }}</div>
                        <div class="des">
                            Discover this book published in {{ $carouselBook->published_year }} in the {{ $carouselBook->category }} category. 
                            Language: {{ $carouselBook->language }}.
                            @if($carouselBook->isbn)
                                ISBN: {{ $carouselBook->isbn }}
                            @endif
                        </div>
                        <div class="buttons">
                            <button class="seeMore" onclick="window.location.href='{{ route('book.show', $carouselBook) }}'">Discover</button>
                            <button onclick="window.location.href='{{ route('login') }}'">Borrow</button>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Display default message if no books -->
                <div class="item">
                    <img src="{{ asset('assets/img/curved-images/curved14.jpg') }}" class="img" alt="Library">
                    <div class="introduce">
                        <div class="author">Atlas Roads Library</div>
                        <div class="title">Book Collection</div>
                        <div class="topic">Coming Soon</div>
                        <div class="des">
                            Our book catalog will be available soon. Come back and visit us soon!
                        </div>
                        <div class="buttons">
                            <button class="seeMore" onclick="window.location.href='#books'">Discover</button>
                            <button onclick="window.location.href='{{ route('login') }}'">Sign Up</button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        <div class="arrows">
            <button id="prev"><</button>
            <button id="next">></button>
            <button id="back">See All  &#8599</button>
        </div>
    </div>
</main>

<!-- Section Livres -->
<section id="books" class="books-section">
    <div class="container mx-auto px-8" style="max-width: 1400px; position: relative; z-index: 2;">
        <!-- Section Title -->
        <div class="text-center mb-12">
            <h2 class="section-title text-5xl font-bold mb-4">Our Library</h2>
            <p class="section-subtitle text-xl">Discover our collection of {{ $books->total() }} available books</p>
        </div>

        <!-- Grille de livres -->
        @if($books->count() > 0)
            <div class="flex flex-wrap -mx-3">
                @foreach($books as $book)
                    <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                        <div class="book-card">
                            <!-- Book Image -->
                            <div class="book-image-container relative">
                                <img src="{{ $book->image_url }}" 
                                     alt="{{ $book->title }}" 
                                     class="book-image"
                                     loading="lazy"
                                     onerror="this.src='{{ asset('assets/img/curved-images/curved14.jpg') }}'">
                                
                                <!-- Availability Badge -->
                                @if($book->is_available)
                                    <span class="badge-available absolute" style="top: 12px; right: 12px;">
                                        Available
                                    </span>
                                @else
                                    <span class="badge-unavailable absolute" style="top: 12px; right: 12px;">
                                        Unavailable
                                    </span>
                                @endif
                            </div>

                            <!-- Book Info -->
                            <div class="p-4">
                                <h5 class="book-title">{{ Str::limit($book->title, 50) }}</h5>
                                
                                <p class="book-info">
                                    <i class="fas fa-user"></i>
                                    {{ Str::limit($book->author, 35) }}
                                </p>
                                
                                <p class="book-info">
                                    <i class="fas fa-bookmark"></i>
                                    {{ $book->category }}
                                </p>
                                
                                <p class="book-info mb-4">
                                    <i class="fas fa-calendar"></i>
                                    {{ $book->published_year }} • {{ $book->language }}
                                </p>

                                <!-- Action Button -->
                                <a href="{{ route('book.show', $book) }}" 
                                   class="book-action-btn block w-full text-center px-4 py-2.5 rounded-lg text-sm font-semibold">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center mt-8">
                <div class="pagination-container">
                    {{ $books->links() }}
                </div>
            </div>
        @else
            <div class="w-full px-3">
                <div class="p-6 text-center bg-white rounded-2xl shadow">
                    <i class="ni ni-books text-6xl text-slate-300 mb-4"></i>
                    <p class="text-slate-400">No books found</p>
                </div>
            </div>
        @endif
    </div>
</section>

    @push('scripts')
        <script src="{{ asset('assets/js/home/app.js') }}"></script>
        <script src="{{ asset('assets/js/home/scroll-animation.js') }}"></script>
    @endpush
</x-layouts.base>
