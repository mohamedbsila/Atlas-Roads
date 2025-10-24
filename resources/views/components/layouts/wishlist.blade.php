<x-layouts.base>
    <x-client-navbar />

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