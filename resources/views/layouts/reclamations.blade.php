```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atlas Roads - @yield('title')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #d946ef, #ec4899, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .shimmer-effect {
            background: linear-gradient(90deg, 
                rgba(255,255,255,0) 0%, 
                rgba(255,255,255,0.5) 50%, 
                rgba(255,255,255,0) 100%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Global Navbar -->
    <header class="bg-white shadow-lg">
        <nav class="container mx-auto px-4 py-4 flex flex-wrap justify-between items-center">
            <!-- Branding -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold gradient-text flex items-center">
                    <i class="fas fa-road mr-2"></i> Atlas Roads
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                    <i class="fas fa-home mr-1"></i> Accueil
                </a>
                <a href="{{ route('reclamations.index') }}" class="text-blue-600 border-b-2 border-blue-600 pb-1 font-medium">
                    <i class="fas fa-exclamation-circle mr-1"></i> Réclamations
                </a>
                <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-blue-600 transition-colors font-medium">
                    <i class="far fa-calendar-alt mr-1"></i> Événements
                </a>
                <a href="{{ route('books.index') }}" class="text-gray-700 hover:text-purple-500 font-semibold text-sm transition-all duration-300 hover:scale-105">
                    <i class="fas fa-book mr-1"></i> Books
                </a>
                <a href="{{ route('reclamations.index') }}" class="text-gray-700 hover:text-purple-500 font-semibold text-sm transition-all duration-300 hover:scale-105">
                    <i class="fas fa-exclamation-circle mr-1"></i> My Reclamations
                </a>
                <a href="{{ route('wishlist.index') }}" class="text-gray-700 hover:text-purple-500 font-semibold text-sm transition-all duration-300 hover:scale-105">
                    <i class="fas fa-heart mr-1"></i> My Wishlist
                </a>
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-purple-500 font-semibold text-sm transition-all duration-300 hover:scale-105">
                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                </a>
            </div>

            <!-- User Profile -->
            <div class="relative flex items-center">
                <span class="text-gray-700 font-semibold text-sm mr-2">{{ auth()->user()->name ?? 'oussamaa' }}</span>
                <a href="{{ route('logout') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg"
                   style="background:linear-gradient(135deg,#06b6d4,#3b82f6)">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-inner mt-6 py-4">
        <div class="container mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Atlas Roads. All rights reserved.
        </div>
    </footer>
</body>
</html>
```