<x-layouts.base>
    @push('styles')
        <style>
        /* Creative Navbar Styles from home.blade.php - Scoped to navbar only */
        .header-creative {
            /* CSS Variables - scoped to navbar */
            --nav-gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --nav-gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --nav-glass-bg: rgba(255, 255, 255, 0.7);
            --nav-glass-border: rgba(255, 255, 255, 0.18);
            --nav-glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            --nav-color-text: #4a5568;
            --nav-space-2: 0.5rem;
            --nav-space-3: 0.75rem;
            --nav-space-4: 1rem;
            --nav-space-6: 1.5rem;
            --nav-text-sm: 0.875rem;
            --nav-text-xl: 1.25rem;
            --nav-radius-sm: 0.75rem;
            --nav-radius-md: 1rem;
            --nav-transition: cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Styles */
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--nav-glass-bg);
            backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--nav-glass-border);
            box-shadow: var(--nav-glass-shadow);
        }
        
        .header-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: var(--nav-space-4) var(--nav-space-6);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--nav-space-4);
        }
        
        .logo-group {
            display: flex;
            align-items: center;
            gap: var(--nav-space-3);
        }
        
        .logo-img {
            height: 2.5rem;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(102, 126, 234, 0.3));
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        .logo-img:hover {
            transform: rotate(-5deg) scale(1.1);
        }
        
        .logo-text {
            font-size: var(--nav-text-xl);
            font-weight: 800;
            background: var(--nav-gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: var(--nav-space-6);
            flex-wrap: wrap;
        }
        
        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: var(--nav-space-2);
            padding: var(--nav-space-2) var(--nav-space-3);
            font-size: var(--nav-text-sm);
            font-weight: 600;
            color: var(--nav-color-text);
            text-decoration: none;
            border-radius: var(--nav-radius-sm);
            transition: all 0.3s var(--nav-transition);
            position: relative;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--nav-gradient-primary);
            border-radius: var(--nav-radius-sm);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: -1;
        }
        
        .nav-link:hover {
            color: white;
            transform: translateY(-2px);
        }
        
        .nav-link:hover::before {
            opacity: 1;
        }
        
        .nav-link:focus {
            outline: 3px solid rgba(102, 126, 234, 0.5);
            outline-offset: 2px;
        }
        
        .nav-cta {
            display: inline-flex;
            align-items: center;
            gap: var(--nav-space-2);
            padding: var(--nav-space-3) var(--nav-space-6);
            background: var(--nav-gradient-secondary);
            color: white;
            font-size: var(--nav-text-sm);
            font-weight: 700;
            border-radius: var(--nav-radius-md);
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
            transition: all 0.3s var(--nav-transition);
            position: relative;
            overflow: hidden;
        }
        
        .nav-cta::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
            transition: transform 0.6s;
        }
        
        .nav-cta:hover::before {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }
        
        .nav-cta:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(245, 87, 108, 0.5);
        }
        
        @media (max-width: 767px) {
            .nav-links {
                justify-content: center;
            }
        }
        
        /* ========================================
           🛡️ PROTECTION: Ensure Tailwind works in main content
           ======================================== */
        main button,
        main .btn,
        main a.inline-block,
        main form button {
            /* Reset any navbar interference */
            all: revert;
        }
        
        /* Restore Tailwind utility classes for buttons */
        main .bg-blue-600 { background-color: rgb(37 99 235) !important; }
        main .bg-blue-700 { background-color: rgb(29 78 216) !important; }
        main .bg-green-500 { background-color: rgb(34 197 94) !important; }
        main .bg-green-600 { background-color: rgb(22 163 74) !important; }
        main .bg-red-500 { background-color: rgb(239 68 68) !important; }
        main .bg-red-600 { background-color: rgb(220 38 38) !important; }
        main .bg-yellow-500 { background-color: rgb(234 179 8) !important; }
        main .bg-yellow-600 { background-color: rgb(202 138 4) !important; }
        main .bg-gray-400 { background-color: rgb(156 163 175) !important; }
        main .bg-gray-500 { background-color: rgb(107 114 128) !important; }
        
        /* Gradient support for Download PDF button and others */
        main .bg-gradient-to-r { background-image: linear-gradient(to right, var(--tw-gradient-stops)) !important; }
        main .from-red-500 { --tw-gradient-from: #ef4444 !important; --tw-gradient-to: rgb(239 68 68 / 0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        main .to-pink-600 { --tw-gradient-to: #db2777 !important; }
        main .hover\:from-red-600:hover { --tw-gradient-from: #dc2626 !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        main .hover\:to-pink-700:hover { --tw-gradient-to: #be185d !important; }
        
        /* Text colors */
        main .text-white { color: rgb(255 255 255) !important; }
        main .text-slate-700 { color: rgb(51 65 85) !important; }
        main .text-slate-800 { color: rgb(30 41 59) !important; }
        
        /* Font weights */
        main .font-bold { font-weight: 700 !important; }
        main .font-semibold { font-weight: 600 !important; }
        
        /* Border radius */
        main .rounded-lg { border-radius: 0.5rem !important; }
        main .rounded-xl { border-radius: 0.75rem !important; }
        main .rounded-full { border-radius: 9999px !important; }
        
        /* Padding */
        main .px-2 { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
        main .px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
        main .px-6 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
        main .py-1 { padding-top: 0.25rem !important; padding-bottom: 0.25rem !important; }
        main .py-2 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
        main .py-2\.5 { padding-top: 0.625rem !important; padding-bottom: 0.625rem !important; }
        main .py-3 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
        
        /* Shadows */
        main .shadow-lg { box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important; }
        main .shadow-xl { box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important; }
        main .hover\:shadow-xl:hover { box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important; }
        
        /* Transforms */
        main .hover\:scale-105:hover { transform: scale(1.05) !important; }
        
        /* Transitions */
        main .transition-all { transition-property: all !important; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1) !important; transition-duration: 150ms !important; }
        main .duration-300 { transition-duration: 300ms !important; }
        
        /* Hover backgrounds */
        main .hover\:bg-blue-700:hover { background-color: rgb(29 78 216) !important; }
        main .hover\:bg-green-600:hover { background-color: rgb(22 163 74) !important; }
        main .hover\:bg-red-600:hover { background-color: rgb(220 38 38) !important; }
        main .hover\:bg-yellow-600:hover { background-color: rgb(202 138 4) !important; }
        </style>
    @endpush
    
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

