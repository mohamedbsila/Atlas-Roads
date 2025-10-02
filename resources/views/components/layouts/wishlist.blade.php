<x-layouts.base>
    <!-- Home-style navbar -->
    <header class="bg-white shadow-md w-full sticky top-0 z-50">
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
                    <a href="{{ route('wishlist.index') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition {{ Request::is('wishlist*') ? 'text-blue-600 font-bold' : '' }}">
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

    <!-- Main content -->
    <main class="min-h-screen bg-gray-50 py-8">
        {{ $slot }}
    </main>

    <!-- Simple footer -->
    <footer class="bg-white border-t border-gray-200 py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-600 text-sm">
                © {{ date('Y') }} Atlas Roads Library. All rights reserved.
            </p>
        </div>
    </footer>
</x-layouts.base> 