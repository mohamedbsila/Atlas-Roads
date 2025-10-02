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
            
            .books-section {
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                padding: 60px 0;
            }
            
            /* Navigation responsive */
            header {
                position: sticky;
                top: 0;
                z-index: 50;
                background: white;
            }
            header .container {
                max-width: 1400px;
            }
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
            @auth
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </a>
            @endauth
            <a href="#books" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                <i class="fas fa-book mr-1"></i> Books
            </a>
            <a href="#contact" class="px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                <i class="fas fa-envelope mr-1"></i> Contact
            </a>
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
    <div class="container mx-auto px-8" style="max-width: 1400px;">
        <!-- Section Title -->
        <div class="text-center mb-12">
            <h2 class="text-5xl font-bold text-gray-800 mb-4">📚 Our Library</h2>
            <p class="text-xl text-gray-600">Discover our collection of {{ $books->total() }} available books</p>
        </div>

        <!-- Grille de livres -->
        @if($books->count() > 0)
            <div class="flex flex-wrap -mx-3">
                @foreach($books as $book)
                    <div class="w-full md:w-1/2 lg:w-1/3 px-3 mb-6">
                        <div class="relative flex flex-col h-full bg-white shadow rounded-2xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div class="flex-auto p-4">
                            <div class="relative overflow-hidden rounded-xl mb-3 group">
                                <img src="{{ $book->image_url }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-full h-48 object-cover rounded-xl transition-transform duration-300 group-hover:scale-110"
                                     loading="lazy">
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
                            <p class="mb-3 text-xs text-slate-400">
                                <i class="ni ni-calendar-grid-58 mr-1"></i> {{ $book->published_year }} | {{ $book->language }}
                            </p>

                            <!-- Action Button -->
                            <a href="{{ route('book.show', $book) }}" 
                               class="block w-full text-center px-3 py-2 rounded-lg text-xs font-bold text-white shadow-md hover:scale-105 transition-all"
                               style="background:linear-gradient(to right,#06b6d4,#0ea5e9)">
                                <i class="ni ni-zoom-split-in mr-1"></i>
                                <span>View Details</span>
                            </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                <div class="bg-white rounded-xl shadow-md p-4">
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
