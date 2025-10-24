@push('styles')
<style>
:root {
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --glass-bg: rgba(255, 255, 255, 0.7);
    --glass-border: rgba(255, 255, 255, 0.18);
    --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
    --space-2: 0.5rem;
    --space-3: 0.75rem;
    --space-4: 1rem;
    --space-6: 1.5rem;
    --text-sm: 0.875rem;
    --text-xl: 1.25rem;
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --transition-smooth: cubic-bezier(0.4, 0, 0.2, 1);
    --transition-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    --color-text-secondary: #4a5568;
}

.header-creative {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: var(--glass-bg);
    backdrop-filter: blur(20px) saturate(180%);
    border-bottom: 1px solid var(--glass-border);
    box-shadow: var(--glass-shadow);
}

.header-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: var(--space-4) var(--space-6);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-4);
}

.logo-group {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.logo-img {
    height: 2.5rem;
    width: auto;
    filter: drop-shadow(0 2px 8px rgba(102, 126, 234, 0.3));
    transition: transform 0.3s var(--transition-bounce);
}

.logo-img:hover {
    transform: rotate(-5deg) scale(1.1);
}

.logo-text {
    font-size: var(--text-xl);
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -0.02em;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: var(--space-6);
    flex-wrap: wrap;
}

.nav-link {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-2) var(--space-3);
    font-size: var(--text-sm);
    font-weight: 600;
    color: var(--color-text-secondary);
    text-decoration: none;
    border-radius: var(--radius-sm);
    transition: all 0.3s var(--transition-smooth);
    position: relative;
}

.nav-link::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--gradient-primary);
    border-radius: var(--radius-sm);
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
    gap: var(--space-2);
    padding: var(--space-3) var(--space-6);
    background: var(--gradient-secondary);
    color: white;
    font-size: var(--text-sm);
    font-weight: 700;
    border-radius: var(--radius-md);
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
    transition: all 0.3s var(--transition-smooth);
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
</style>
@endpush

<header class="header-creative" role="banner">
    <div class="header-container">
        <div class="logo-group">
            <img src="{{ asset('assets/img/logo/logo black.png') }}" 
                 alt="Atlas Roads Logo" 
                 class="logo-img"
                 width="40" 
                 height="40">
            <span class="logo-text">Atlas Roads</span>
        </div>
        
        <nav class="nav-links" role="navigation" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="nav-link" aria-current="page">
                <i class="fas fa-home" aria-hidden="true"></i>
                <span>Home</span>
            </a>
            
            <a href="{{ route('home') }}#books" class="nav-link">
                <i class="fas fa-book" aria-hidden="true"></i>
                <span>Books</span>
            </a>
            
            @auth
                <a href="{{ route('rooms.search') }}" class="nav-link">
                    <i class="fas fa-door-open" aria-hidden="true"></i>
                    <span>Rooms</span>
                </a>
                
                <a href="{{ route('room-reservations.my-reservations') }}" class="nav-link">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i>
                    <span>My Reservations</span>
                </a>
                
                <a href="{{ route('wishlist.index') }}" class="nav-link">
                    <i class="fas fa-heart" aria-hidden="true"></i>
                    <span>Wishlist</span>
                </a>
                
                <a href="{{ route('wishlist.create') }}" class="nav-cta">
                    <i class="fas fa-plus" aria-hidden="true"></i>
                    <span>Request Book</span>
                </a>
                
                <a href="{{ route('reclamations.create') }}" class="nav-cta" style="background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%); box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                    <span>Report Problem</span>
                </a>
                
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
                    <span>Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-link">
                    <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                    <span>Login</span>
                </a>
            @endauth
        </nav>
    </div>
</header>

