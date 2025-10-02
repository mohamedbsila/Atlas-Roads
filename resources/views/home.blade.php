<x-layouts.base>
    <!-- Add custom styles -->
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/home/style.css') }}">
    @endpush
    
<header class="bg-white shadow-sm">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="logo text-2xl font-bold text-blue-600">Atlas Roads</div>
        <nav class="space-x-6">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">Home</a>
            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
            <a href="#contact" class="text-gray-700 hover:text-blue-600">Contact</a>
        </nav>
    </div>
</header>

<main>
    <div class="carousel">
        <div class="list">
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
            @else
                <div class="item">
                    <img src="{{ asset('assets/img/home/images/img1.png') }}" class="img" alt="Atlas Roads hero">
                    <div class="content">
                        <div class="author">Atlas Roads</div>
                        <div class="title">Road Management System</div>
                        <div class="topic">Modern Infrastructure Solutions</div>
                        <div class="des">
                            Streamline your road infrastructure management with our comprehensive dashboard.
                        </div>
                        <div class="buttons">
                            <button>Learn More</button>
                            <button>Get Started</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="arrows">
            <button id="prev"><</button>
            <button id="next">></button>
            <button id="back">See All  &#8599</button>
        </div>
    </div>
</main>
    @push('scripts')
        <script src="{{ asset('assets/js/home/app.js') }}"></script>
        <script src="{{ asset('assets/js/home/scroll-animation.js') }}"></script>
    @endpush
</x-layouts.base>
