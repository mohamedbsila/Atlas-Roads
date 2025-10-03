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
            
            /* Books Section */
            .books-section {
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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

            /* Book Card */
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

            /* Borrow Button */
            .borrow-button {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .borrow-button:hover {
                background: linear-gradient(135deg, #059669 0%, #047857 100%);
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            }

            .borrow-button:active {
                transform: translateY(-1px);
            }

            .borrow-button::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s;
            }

            .borrow-button:hover::before {
                left: 100%;
            }

            /* Navigation responsive */
            header {
                position: sticky;
                top: 0;
                z-index: 50;
                background: white;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }

            header .container {
                max-width: 1400px;
            }

            /* Pagination */
            .pagination-container {
                background: white;
                border-radius: 8px;
                padding: 1rem;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            }

            /* Modal Button Improvements */
            #borrowModal .px-6 {
                position: relative;
                overflow: hidden;
            }

            #borrowModal button[type="submit"] {
                position: relative;
                overflow: hidden;
            }

            #borrowModal button[type="submit"]::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                transition: left 0.5s;
            }

            #borrowModal button[type="submit"]:hover::before {
                left: 100%;
            }

            #borrowModal button[type="button"]:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
                
                /* Modal responsive */
                #borrowModal .flex.justify-between {
                    flex-direction: column;
                    gap: 0.75rem;
                }
                
                #borrowModal button {
                    width: 100%;
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
            @endauth
        </nav>
    </div>
    </header>

    <main>
    <div class="carousel">
        <div class="list">
            <!-- Display Events in Carousel -->
            @if (!empty($events) && $events->count())
                @foreach ($events as $event)
                    <div class="item">
                        <img src="{{ $event->thumbnail ? asset('storage/' . $event->thumbnail) : asset('assets/img/home/images/img1.png') }}" class="img" alt="{{ $event->title }}">
                        <div class="content">
                            <div class="author">{{ config('app.name', 'Atlas Roads') }}</div>
                            <div class="title">{{ $event->title }}</div>
                            <div class="topic">{{ \Illuminate\Support\Str::limit($event->description, 60) }}</div>
                            <div class="des">{{ \Illuminate\Support\Str::limit($event->description, 140) }}</div>
                            <div class="buttons">
                                <a href="{{ route('events.index') }}" class="btn">See Events</a>
                                <a href="{{ route('events.index') }}" class="btn">Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Display Books in Carousel -->
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
                            <button class="seeMore" onclick="window.location.href='{{ route('books.show', $carouselBook) }}'">Discover</button>
                            @guest
                                <button onclick="window.location.href='{{ route('login') }}'">Borrow</button>
                            @else
                                <button onclick="openBorrowModal('{{ $carouselBook->id }}', '{{ e($carouselBook->title) }}', '{{ e($carouselBook->author) }}')">Borrow</button>
                            @endguest
                        </div>
                    </div>
                </div>
            @empty
                <!-- Display default message if no books or events -->
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
            <h2 class="section-title text-5xl font-bold mb-4">📚 Our Library</h2>
            <p class="section-subtitle text-xl">Discover our collection of {{ $books->total() }} available books</p>
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

                            <h5 class="book-title mb-2 font-bold text-base leading-tight">{{ Str::limit($book->title, 40) }}</h5>
                            <p class="book-info mb-1 text-sm text-slate-600">
                                <i class="fas fa-user mr-1"></i> {{ Str::limit($book->author, 25) }}
                            </p>
                            <p class="book-info mb-1 text-xs text-slate-400">
                                <i class="fas fa-bookmark mr-1"></i> {{ $book->category }}
                            </p>
                            <p class="book-info mb-3 text-xs text-slate-400">
                                <i class="fas fa-calendar mr-1"></i> {{ $book->published_year }} | {{ $book->language }}
                            </p>

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                @auth
                                    @if($book->is_available && $book->ownerId !== Auth::id())
                                        <button onclick="openBorrowModal('{{ $book->id }}', '{{ e($book->title) }}', '{{ e($book->author) }}')"
                                                class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:from-green-600 hover:to-green-700 transition-all transform hover:-translate-y-1 hover:shadow-lg">
                                            <i class="fas fa-hand-holding-heart me-2"></i>
                                            📚 Borrow Book
                                        </button>
                                    @elseif($book->ownerId === Auth::id())
                                        <div class="w-full bg-gray-100 text-gray-500 px-4 py-2.5 rounded-lg text-sm font-semibold text-center">
                                            <i class="fas fa-crown me-1"></i>
                                            Your Book
                                        </div>
                                    @else
                                        <div class="w-full bg-gray-100 text-gray-500 px-4 py-2.5 rounded-lg text-sm font-semibold text-center">
                                            <i class="fas fa-ban me-1"></i>
                                            Not Available
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:from-gray-500 hover:to-gray-600 transition-all text-center block">
                                        <i class="fas fa-sign-in-alt me-1"></i>
                                        Login to Borrow
                                    </a>
                                @endauth
                                
                                <a href="{{ route('books.show', $book) }}" 
                                   class="book-action-btn block w-full text-center px-3 py-2 rounded-lg text-xs font-bold text-white shadow-md hover:scale-105 transition-all"
                                   style="background:linear-gradient(to right,#06b6d4,#0ea5e9)">
                                    <i class="fas fa-eye me-1"></i>
                                    View Details
                                </a>
                            </div>
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

    <!-- Borrow Request Modal -->
    @auth
    <div id="borrowModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative">
            <form id="borrowForm" method="POST" action="{{ route('borrow-requests.store') }}">
                @csrf
                <input type="hidden" id="modal_book_id" name="book_id">
                
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Borrow Request</h3>
                    <button type="button" onclick="closeBorrowModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <h4 class="font-medium text-gray-900" id="modal_book_title"></h4>
                        <p class="text-sm text-gray-600" id="modal_book_author"></p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   min="{{ now()->addDay()->toDateString() }}"
                                   required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" 
                                   id="end_date" 
                                   name="end_date" 
                                   required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Message (optional)</label>
                            <textarea id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      maxlength="500"
                                      placeholder="Why would you like to borrow this book?"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-200 flex justify-between space-x-3">
                    <button type="button" 
                            onclick="closeBorrowModal()" 
                            class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 hover:border-gray-400 transition-all">
                        <i class="fas fa-times me-2"></i>
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <i class="fas fa-check-circle me-2"></i>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endauth

    @push('scripts')
        <script src="{{ asset('assets/js/home/app.js') }}"></script>
        <script src="{{ asset('assets/js/home/scroll-animation.js') }}"></script>
        
        <script>
            function openBorrowModal(bookId, bookTitle, bookAuthor) {
                document.getElementById('modal_book_id').value = bookId;
                document.getElementById('modal_book_title').textContent = bookTitle;
                document.getElementById('modal_book_author').textContent = "Author: " + bookAuthor;

                document.getElementById('borrowModal').classList.remove('hidden');
            }

            function closeBorrowModal() {
                document.getElementById('borrowModal').classList.add('hidden');
                document.getElementById('borrowForm').reset();
                document.getElementById('modal_book_title').textContent = '';
                document.getElementById('modal_book_author').textContent = '';
            }

            // Dynamic date validation
            document.getElementById('start_date')?.addEventListener('change', function () {
                let start = this.value;
                let endDateInput = document.getElementById('end_date');
                if (start) {
                    endDateInput.min = start;
                }
            });
        </script>
    @endpush
</x-layouts.base>
