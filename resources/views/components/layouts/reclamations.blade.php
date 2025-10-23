<x-layouts.base>
    <header class="bg-white shadow-md w-full">
        <div class="px-8 py-3 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('assets/img/logo/logo-black.png') }}" alt="Atlas Roads" class="h-10">
                </a>
            </div>
            <nav class="flex items-center space-x-5">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                    <i class="fas fa-home mr-1"></i> Home
                </a>
                <a href="{{ route('home') }}#books" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                    <i class="fas fa-book mr-1"></i> Books
                </a>
                @auth
                    <a href="{{ route('reclamations.index') }}" class="px-3 py-1.5 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                        <i class="fas fa-exclamation-triangle mr-1"></i> My Reclamations
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="text-gray-700 hover:text-blue-600 font-medium text-sm transition">
                        <i class="fas fa-heart mr-1"></i> My Wishlist
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

    <main class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-8 py-6" style="max-width: 1400px;">
            {{ $slot }}
        </div>
    </main>

    <footer class="bg-white shadow-md mt-12 py-6">
        <div class="container mx-auto px-8 text-center text-gray-600">
            <p>&copy; {{ date('Y') }} Atlas Roads. All rights reserved.</p>
        </div>
    </footer>
</x-layouts.base>
