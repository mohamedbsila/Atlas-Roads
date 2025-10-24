<x-layouts.base>
  
    
    @push('styles')
        <style>
        /* ========================================
           🎨 DESIGN TOKENS - CREATIVE EDITION
           ======================================== */
        :root {
            /* Gradient Colors */
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-accent: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --gradient-hero: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            
            /* Base Colors */
            --color-bg-primary: #ffffff;
            --color-bg-dark: #1a202c;
            --color-text-primary: #1a202c;
            --color-text-secondary: #4a5568;
            --color-text-light: #718096;
            
            /* Glassmorphism */
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.18);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            
            /* Spacing (8px system) */
            --space-1: 0.25rem;
            --space-2: 0.5rem;
            --space-3: 0.75rem;
            --space-4: 1rem;
            --space-6: 1.5rem;
            --space-8: 2rem;
            --space-12: 3rem;
            --space-16: 4rem;
            --space-20: 5rem;
            
            /* Typography */
            --text-xs: 0.75rem;
            --text-sm: 0.875rem;
            --text-base: 1rem;
            --text-lg: 1.125rem;
            --text-xl: 1.25rem;
            --text-2xl: 1.5rem;
            --text-3xl: 1.875rem;
            --text-4xl: 2.25rem;
            --text-5xl: 3rem;
            --text-6xl: 3.75rem;
            
            /* Effects */
            --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 8px 30px rgba(0, 0, 0, 0.12);
            --shadow-strong: 0 20px 60px rgba(0, 0, 0, 0.15);
            --radius-sm: 0.75rem;
            --radius-md: 1rem;
            --radius-lg: 1.5rem;
            --radius-xl: 2rem;
            
            /* Transitions */
            --transition-smooth: cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        
        /* ========================================
           🌈 ANIMATED BACKGROUND
           ======================================== */
        body {
            position: relative;
            background: #f7fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }
        
        /* Animated gradient background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            opacity: 0.03;
            z-index: -1;
                pointer-events: none;
            }
            
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Geometric patterns overlay */
        .pattern-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(240, 147, 251, 0.05) 0%, transparent 50%);
            z-index: -1;
            pointer-events: none;
        }
        
        /* ========================================
           🧭 HEADER - Glass Morphism
           ======================================== */
        .header-creative {
            position: sticky;
            top: 0;
            z-index: 50;
            background: var(--glass-bg);
            backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            /* UX: Semi-transparent header pour effet moderne */
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
        
        /* Navigation */
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
        
        /* Gradient CTA Button */
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
        
        /* Shine effect on hover */
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
        
        /* ========================================
           🎪 HERO SECTION - Immersive Carousel
           ======================================== */
        .hero-section {
            position: relative;
            min-height: 700px;
            padding: var(--space-16) 0;
            overflow: hidden;
        }
        
        /* Animated blob backgrounds */
        .hero-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: blobFloat 20s ease-in-out infinite;
        }
        
        .hero-blob-1 {
            width: 500px;
            height: 500px;
            top: 10%;
            left: 10%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            animation-delay: 0s;
        }
        
        .hero-blob-2 {
            width: 400px;
            height: 400px;
            bottom: 20%;
            right: 15%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            animation-delay: 7s;
        }
        
        @keyframes blobFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        .carousel-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 var(--space-6);
            position: relative;
            z-index: 2;
        }
        
        /* Carousel Item with Glass Card */
        .carousel-item {
            display: none;
            opacity: 0;
        }
        
        .carousel-item.active {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-12);
            align-items: center;
            opacity: 1;
            animation: slideInUp 0.8s var(--transition-smooth);
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .carousel-image-wrapper {
            position: relative;
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-strong);
            transform-style: preserve-3d;
            transition: transform 0.3s;
        }
        
        .carousel-image-wrapper:hover {
            transform: translateY(-10px) rotateY(5deg);
        }
        
        .carousel-image {
            width: 100%;
            height: 550px;
            object-fit: cover;
            display: block;
        }
        
        /* Gradient overlay on image */
        .carousel-image-wrapper::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.3) 0%, rgba(240, 147, 251, 0.3) 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .carousel-image-wrapper:hover::after {
            opacity: 1;
        }
        
        .carousel-content {
            padding: var(--space-8);
        }
        
        .carousel-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            padding: var(--space-2) var(--space-4);
            background: var(--gradient-success);
            color: white;
            font-size: var(--text-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-radius: 999px;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
            margin-bottom: var(--space-6);
        }
        
        .carousel-title {
            font-size: var(--text-5xl);
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: var(--space-6);
            background: linear-gradient(135deg, #1a202c 0%, #4a5568 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.03em;
        }
        
        .carousel-description {
            font-size: var(--text-lg);
            color: var(--color-text-secondary);
            line-height: 1.8;
            margin-bottom: var(--space-8);
        }
        
        .carousel-actions {
            display: flex;
            gap: var(--space-4);
            flex-wrap: wrap;
        }
        
        /* Gradient Buttons */
        .btn-gradient-primary {
            display: inline-flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-4) var(--space-8);
            background: var(--gradient-primary);
            color: white;
            font-size: var(--text-base);
            font-weight: 700;
            border-radius: var(--radius-md);
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            transition: all 0.3s var(--transition-smooth);
            border: none;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gradient-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-gradient-primary:hover::before {
            left: 100%;
        }
        
        .btn-gradient-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.5);
        }
        
        .btn-gradient-secondary {
            display: inline-flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-4) var(--space-8);
            background: white;
            color: #667eea;
            font-size: var(--text-base);
            font-weight: 700;
            border: 3px solid transparent;
            background-clip: padding-box;
            border-radius: var(--radius-md);
            text-decoration: none;
            position: relative;
            transition: all 0.3s;
            box-shadow: var(--shadow-soft);
        }
        
        .btn-gradient-secondary::before {
            content: '';
            position: absolute;
            inset: -3px;
            background: var(--gradient-primary);
            border-radius: var(--radius-md);
            z-index: -1;
        }
        
        .btn-gradient-secondary:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
            color: #764ba2;
        }
        
        /* Carousel Navigation - Floating Pills */
        .carousel-nav {
            display: flex;
            gap: var(--space-3);
            justify-content: center;
            margin-top: var(--space-12);
        }
        
        .carousel-arrow {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: none;
            border-radius: 50%;
            color: #667eea;
            font-size: var(--text-xl);
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s var(--transition-smooth);
        }
        
        .carousel-arrow:hover {
            background: var(--gradient-primary);
            color: white;
            transform: scale(1.15) translateY(-2px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }
        
        /* ========================================
           🎉 EVENTS SECTION
           ======================================== */
        .events-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .events-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .events-title {
            color: white;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .events-subtitle {
            color: rgba(255,255,255,0.9);
            font-size: 1.25rem;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .event-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: 100%;
            position: relative;
        }
        
        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .event-image-container {
            position: relative;
            overflow: hidden;
            height: 220px;
        }
        
        .event-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .event-card:hover .event-image {
            transform: scale(1.1);
        }
        
        .event-date {
            position: absolute;
            top: 16px;
            left: 16px;
            background: rgba(255,255,255,0.95);
            border-radius: 10px;
            padding: 10px 12px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px);
        }
        
        .event-day {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #4f46e5;
            line-height: 1;
        }
        
        .event-month {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 2px;
        }
        
        .event-content {
            padding: 24px;
        }
        
        .event-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        
        .event-description {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .event-meta {
            display: flex;
            align-items: center;
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 20px;
        }
        
        .event-meta i {
            margin-right: 8px;
            color: #9ca3af;
        }
        
        .event-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            text-align: center;
            border: none;
            cursor: pointer;
        }
        
        .event-button:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3);
            color: white;
        }

        /* Communities badges for events */
        .event-communities {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .community-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.09);
            color: #ffffff;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            text-decoration: none;
            transition: all 0.2s ease;
            backdrop-filter: blur(4px);
        }

        .community-badge img {
            width: 22px;
            height: 22px;
            border-radius: 999px;
            object-fit: cover;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .community-badge:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.18);
            background: rgba(255,255,255,0.14);
        }

        /* Modal community item visual improvements */
        .modal-content ul li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #f9fafb;
            border-radius: 10px;
            padding: 12px;
        }

        .modal-content .join-actions a,
        .modal-content .join-actions button {
            font-size: 0.75rem;
            padding: 6px 10px;
            border-radius: 8px;
        }
        
        .events-cta {
            text-align: center;
            margin-top: 4rem;
        }
        
        .events-cta-button {
            display: inline-flex;
            align-items: center;
            background: white;
            color: #4f46e5;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.125rem;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .events-cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            color: #4f46e5;
        }
        
        .no-events {
            text-align: center;
            padding: 60px 20px;
            color: white;
        }
        
        .no-events-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }
        
        .no-events-text {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }

        /* Communities modal styles */
        .modal-backdrop {
            animation: fadeIn 0.2s ease-out;
        }
        
        .modal-content {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* ========================================
           📚 BOOKS SECTION - Card Grid
           ======================================== */
            .books-section {
            padding: var(--space-20) 0;
            background: linear-gradient(180deg, #f7fafc 0%, #ffffff 100%);
            position: relative;
        }
        
        .section-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 var(--space-6);
        }
        
        /* Section Header with Gradient Underline */
        .section-header {
            text-align: center;
            margin-bottom: var(--space-16);
            position: relative;
        }
        
            .section-title {
            font-size: var(--text-5xl);
            font-weight: 900;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: var(--space-4);
            letter-spacing: -0.03em;
            }
            
            .section-subtitle {
            font-size: var(--text-xl);
            color: var(--color-text-secondary);
            font-weight: 500;
        }
        
        /* Decorative line under title */
        .section-header::after {
            content: '';
            display: block;
            width: 100px;
            height: 5px;
            background: var(--gradient-accent);
            border-radius: 999px;
            margin: var(--space-6) auto 0;
        }
        
        /* Books Grid */
        .books-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--space-8);
        }
        
        /* Book Card - Glass Style */
            .book-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-lg);
                overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: all 0.4s var(--transition-smooth);
                height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        
        /* Gradient border effect on hover */
        .book-card::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: var(--gradient-primary);
            border-radius: var(--radius-lg);
            opacity: 0;
            transition: opacity 0.4s;
            z-index: -1;
        }
        
        .book-card:hover::before {
            opacity: 1;
            }
            
            .book-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-strong);
            }
            
            .book-image-container {
                position: relative;
                overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            aspect-ratio: 3/4;
            }
            
            .book-image {
                width: 100%;
            height: 100%;
                object-fit: cover;
            transition: transform 0.4s;
            }
            
            .book-card:hover .book-image {
            transform: scale(1.1) rotate(2deg);
        }
        
        /* Availability Badge - Gradient Style */
        .badge {
            position: absolute;
            top: var(--space-3);
            right: var(--space-3);
            padding: var(--space-2) var(--space-4);
            font-size: var(--text-xs);
            font-weight: 700;
            border-radius: 999px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
            .badge-available {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                color: white;
            }
            
            .badge-unavailable {
            background: rgba(107, 114, 128, 0.9);
                color: white;
        }
        
        /* Book Content */
        .book-content {
            padding: var(--space-6);
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
            .book-title {
            font-size: var(--text-xl);
            font-weight: 800;
            color: var(--color-text-primary);
            margin-bottom: var(--space-4);
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .book-meta {
            display: flex;
            flex-direction: column;
            gap: var(--space-3);
            margin-bottom: var(--space-6);
            flex: 1;
        }
        
        .book-meta-item {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            font-size: var(--text-sm);
            color: var(--color-text-secondary);
            font-weight: 500;
        }
        
        .book-meta-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-success);
                color: white;
            border-radius: 6px;
            flex-shrink: 0;
            font-size: 11px;
        }
        
        /* Book Actions */
        .book-actions {
            display: flex;
            flex-direction: column;
            gap: var(--space-3);
        }
        
        .btn-borrow {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            padding: var(--space-4) var(--space-4);
            background: var(--gradient-success);
                color: white;
            font-size: var(--text-sm);
            font-weight: 700;
            border-radius: var(--radius-md);
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
            transition: all 0.3s;
                position: relative;
                overflow: hidden;
            }

        .btn-borrow::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-borrow:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-borrow:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.5);
        }
        
        .btn-details {
                width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            padding: var(--space-4) var(--space-4);
            background: white;
            color: #667eea;
            font-size: var(--text-sm);
            font-weight: 700;
            border: 2px solid #667eea;
            border-radius: var(--radius-md);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-details:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-disabled {
            width: 100%;
            padding: var(--space-4);
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
            color: #718096;
            font-size: var(--text-sm);
            font-weight: 700;
            border-radius: var(--radius-md);
            text-align: center;
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        /* ========================================
           📄 PAGINATION
           ======================================== */
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: var(--space-16);
        }
        
            .pagination-container {
                background: white;
            padding: var(--space-6);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-soft);
        }
        
        /* ========================================
           🎯 MODAL - Glass Style
           ======================================== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--space-4);
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-strong);
            max-width: 550px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlide 0.4s var(--transition-bounce);
        }
        
        @keyframes modalSlide {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .modal-header {
            padding: var(--space-6);
            border-bottom: 2px solid transparent;
            background: var(--gradient-primary);
            background-clip: padding-box;
                position: relative;
            }
            
        .modal-header::after {
                content: '';
                position: absolute;
            bottom: 0;
            left: var(--space-6);
            right: var(--space-6);
            height: 2px;
            background: var(--gradient-accent);
        }
        
        .modal-title {
            font-size: var(--text-2xl);
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .modal-close {
            position: absolute;
            top: var(--space-6);
            right: var(--space-6);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: none;
            border-radius: 50%;
            color: #667eea;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        
        .modal-close:hover {
            background: var(--gradient-primary);
            color: white;
            transform: rotate(90deg) scale(1.1);
        }
        
        .modal-body {
            padding: var(--space-8);
        }
        
        .modal-book-info {
            margin-bottom: var(--space-6);
            padding: var(--space-4);
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(240, 147, 251, 0.05));
            border-radius: var(--radius-md);
            border-left: 4px solid #667eea;
        }
        
        .modal-book-title {
            font-size: var(--text-xl);
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: var(--space-2);
        }
        
        .modal-book-author {
            font-size: var(--text-sm);
            color: var(--color-text-secondary);
            font-weight: 500;
        }
        
        /* Form Elements - Modern Style */
        .form-group {
            margin-bottom: var(--space-6);
        }
        
        .form-label {
            display: block;
            font-size: var(--text-sm);
            font-weight: 700;
            color: var(--color-text-primary);
            margin-bottom: var(--space-3);
        }
        
        .form-input {
                width: 100%;
            padding: var(--space-4) var(--space-4);
            border: 2px solid #e2e8f0;
            border-radius: var(--radius-md);
            font-size: var(--text-base);
            color: var(--color-text-primary);
            transition: all 0.3s;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }
        
        .modal-footer {
            padding: var(--space-6);
            background: rgba(247, 250, 252, 0.5);
            border-top: 1px solid rgba(226, 232, 240, 0.5);
                    display: flex;
            gap: var(--space-4);
            justify-content: flex-end;
        }
        
        /* ========================================
           📱 RESPONSIVE
           ======================================== */
        @media (min-width: 768px) {
            .books-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (min-width: 1024px) {
            .books-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (min-width: 1280px) {
            .books-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        @media (max-width: 767px) {
            .carousel-item.active {
                grid-template-columns: 1fr;
            }
            
            .carousel-image {
                height: 350px;
            }
            
            .carousel-title {
                font-size: var(--text-3xl);
            }
            
            .section-title {
                font-size: var(--text-3xl);
            }
            
            .modal-footer {
                    flex-direction: column;
                }
                
            .modal-footer button {
                    width: 100%;
                }
            
            .nav-links {
                justify-content: center;
            }
        }
        
        /* ========================================
           ♿ ACCESSIBILITY
           ======================================== */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
            }
        </style>
    @endpush
    
    {{-- Pattern Overlay --}}
    <div class="pattern-overlay" aria-hidden="true"></div>
    
    {{-- ═══════════════════════════════════════════════════════════════════
         🧭 HEADER
         ═══════════════════════════════════════════════════════════════════ --}}
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
                
                <a href="#books" class="nav-link">
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

    {{-- ═══════════════════════════════════════════════════════════════════
         🎪 HERO SECTION
         ═══════════════════════════════════════════════════════════════════ --}}
    <section class="hero-section" role="region" aria-label="Featured content">
        {{-- Animated Blobs --}}
        <div class="hero-blob hero-blob-1" aria-hidden="true"></div>
        <div class="hero-blob hero-blob-2" aria-hidden="true"></div>
        
        <div class="carousel-container">
            <div id="carouselContent" role="region" aria-label="Book carousel" aria-live="polite">
                @php $carouselIndex = 0; @endphp
                
                {{-- Events --}}
            @if (!empty($events) && $events->count())
                @foreach ($events as $event)
                        <div class="carousel-item {{ $carouselIndex === 0 ? 'active' : '' }}" data-index="{{ $carouselIndex }}">
                            <div class="carousel-image-wrapper">
                                <img src="{{ $event->thumbnail ? asset('storage/' . $event->thumbnail) : asset('assets/img/home/images/img1.png') }}" 
                                     alt="{{ $event->title }}"
                                     class="carousel-image"
                                     loading="lazy">
                            </div>
                            
                            <div class="carousel-content">
                                <span class="carousel-badge">
                                    <i class="fas fa-calendar-star" aria-hidden="true"></i>
                                    Event
                                </span>
                                <h1 class="carousel-title">{{ $event->title }}</h1>
                                <p class="carousel-description">
                                    {{ \Illuminate\Support\Str::limit($event->description, 220) }}
                                </p>
                                <div class="carousel-actions">
                                    <a href="{{ route('events.index') }}" class="btn-gradient-primary">
                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                        <span>Explore Events</span>
                                    </a>
                                    <a href="{{ route('events.index') }}" class="btn-gradient-secondary">
                                        <span>Learn More</span>
                                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </a>
                        </div>
                    </div>
                        </div>
                        @php $carouselIndex++; @endphp
                @endforeach
            @endif

                {{-- Books --}}
            @forelse($carouselBooks as $carouselBook)
                    <div class="carousel-item {{ $carouselIndex === 0 ? 'active' : '' }}" data-index="{{ $carouselIndex }}">
                        <div class="carousel-image-wrapper">
                            <img src="{{ $carouselBook->image_url }}" 
                                 alt="{{ $carouselBook->title }}"
                                 class="carousel-image"
                                 loading="lazy">
                        </div>
                        
                        <div class="carousel-content">
                            <span class="carousel-badge">
                                <i class="fas fa-book-open" aria-hidden="true"></i>
                                Featured Book
                            </span>
                            <h1 class="carousel-title">{{ $carouselBook->title }}</h1>
                            <p class="carousel-description">
                                By <strong>{{ $carouselBook->author }}</strong> • 
                                Published in {{ $carouselBook->published_year }} • 
                                @php
                                    $carouselCategoryName = 'Uncategorized';
                                    if ($carouselBook->category_id && $carouselBook->relationLoaded('category')) {
                                        $carouselCat = $carouselBook->getRelation('category');
                                        if ($carouselCat) {
                                            $carouselCategoryName = $carouselCat->category_name;
                                        }
                                    } elseif ($carouselBook->getAttribute('category')) {
                                        $carouselCategoryName = $carouselBook->getAttribute('category');
                                    }
                                @endphp
                                {{ $carouselCategoryName }}
                                • {{ $carouselBook->language }}
                            @if($carouselBook->isbn)
                                    <br>ISBN: {{ $carouselBook->isbn }}
                            @endif
                            </p>
                            <div class="carousel-actions">
                                <a href="{{ route('books.show', $carouselBook) }}" class="btn-gradient-primary">
                                    <i class="fas fa-book-reader" aria-hidden="true"></i>
                                    <span>Discover Book</span>
                                </a>
                                @guest
                                    <a href="{{ route('login') }}" class="btn-gradient-secondary">
                                        <span>Login to Borrow</span>
                                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </a>
                                @endguest
                        </div>
                        </div>
                    </div>
                    @php $carouselIndex++; @endphp
            @empty
                    <div class="carousel-item active" data-index="0">
                        <div class="carousel-image-wrapper">
                            <img src="{{ asset('assets/img/curved-images/curved14.jpg') }}" 
                                 alt="Library collection"
                                 class="carousel-image"
                                 loading="lazy">
                        </div>
                        
                        <div class="carousel-content">
                            <span class="carousel-badge">
                                <i class="fas fa-sparkles" aria-hidden="true"></i>
                                Coming Soon
                            </span>
                            <h1 class="carousel-title">Welcome to Atlas Roads</h1>
                            <p class="carousel-description">
                                Embark on a journey through our curated collection of books. Discover stories that inspire, educate, and transform. Our library is being prepared with love and care.
                            </p>
                            <div class="carousel-actions">
                                <a href="#books" class="btn-gradient-primary">
                                    <span>Explore Collection</span>
                                    <i class="fas fa-arrow-down" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('login') }}" class="btn-gradient-secondary">
                                    <span>Join Us</span>
                                </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
            
            {{-- Carousel Navigation --}}
            @if($carouselIndex > 1)
                <div class="carousel-nav">
                    <button id="prevBtn" class="carousel-arrow" aria-label="Previous slide" type="button">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button id="nextBtn" class="carousel-arrow" aria-label="Next slide" type="button">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>
        </div>
            @endif
    </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         🎉 EVENTS SECTION
         ═══════════════════════════════════════════════════════════════════ --}}
    <section id="events" class="events-section">
        <div class="container mx-auto px-8" style="max-width: 1400px;">
        <div class="text-center mb-12">
                <h2 class="events-title">Upcoming Events</h2>
                <p class="events-subtitle">Join our community activities, workshops, and literary gatherings</p>
        </div>

            @if(isset($events) && $events->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($events as $event)
                        <div class="event-card">
                            <div class="event-image-container">
                                <a href="{{ route('home.events.show', $event) }}">
                                    <img src="{{ $event->thumbnail_url }}"
                                         alt="{{ $event->title ?? 'Event' }}"
                                         class="event-image"
                                         loading="lazy"
                                         onerror="this.src='{{ asset('assets/img/curved-images/curved14.jpg') }}'">
                                </a>
                                
                                <div class="event-date">
                                    @php
                                        $eventDate = $event->start_date ?? now();
                                        $day = $eventDate->format('d');
                                        $month = $eventDate->format('M');
                                    @endphp
                                    <span class="event-day">{{ $day }}</span>
                                    <span class="event-month">{{ $month }}</span>
                                </div>
                            </div>
                            
                            <div class="event-content">
                                <h3 class="event-title">{{ $event->title ?? 'Event Title' }}</h3>

                                @if($event->communities && $event->communities->count())
                                    <div class="event-communities">
                                        @foreach($event->communities->take(3) as $comm)
                                            <a href="{{ route('communities.show', $comm) }}" class="community-badge" title="{{ $comm->name }}">
                                                <img src="{{ $comm->cover_image_url }}" alt="{{ $comm->name }}" />
                                                <span>{{ Str::limit($comm->name, 18) }}</span>
                                            </a>
                                        @endforeach
                                        @if($event->communities->count() > 3)
                                            <span class="community-badge">+{{ $event->communities->count() - 3 }} more</span>
                                        @endif
                                    </div>
                                @endif

                                <p class="event-description">
                                    {{ \Illuminate\Support\Str::limit($event->description ?? 'Event description coming soon.', 120) }}
                                </p>
                                
                                <div class="event-meta">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $event->start_date ? $event->start_date->format('M j, Y \a\t g:i A') : 'Date TBA' }}</span>
                                </div>
                                
                                <div class="flex gap-2 mt-4">
                                    <a href="{{ route('home.events.show', $event) }}" class="event-button flex-1" aria-label="View details for {{ $event->title }}">
                                        <i class="fas fa-calendar-alt mr-2"></i>
                                        View Details
                                    </a>

                                    @if($event->communities && $event->communities->count())
                                        <button onclick="showCommunities('{{ $event->id }}')" class="event-button bg-indigo-600 hover:bg-indigo-700 flex-1" aria-expanded="false" aria-controls="communities-modal-{{ $event->id }}">
                                            <i class="fas fa-users mr-2"></i>
                                            Communities ({{ $event->communities->count() }})
                                        </button>
                                    @endif
                                </div>

                                @if($event->communities && $event->communities->count())
                                    <div id="communities-modal-{{ $event->id }}" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center modal-backdrop">
                                        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 modal-content" tabindex="-1">
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-lg font-semibold">Available Communities</h3>
                                                <button onclick="hideCommunities('{{ $event->id }}')" class="text-gray-500 hover:text-gray-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <ul class="space-y-3">
                                                @foreach($event->communities as $community)
                                                    <li class="border border-gray-200 rounded-lg p-3" id="community-item-{{ $community->id }}">
                                                        <div class="flex items-start gap-3">
                                                            <img src="{{ $community->cover_image_url }}" alt="{{ $community->name }}"
                                                                 class="w-12 h-12 rounded object-cover flex-shrink-0">
                                                            <div class="flex-grow">
                                                                <a href="{{ route('communities.show', $community) }}" class="font-medium hover:text-blue-600">{{ $community->name }}</a>
                                                                <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                                                                    @if($community->is_public)
                                                                        <span class="text-green-600"><i class="fas fa-globe"></i> Public</span>
                                                                    @else
                                                                        <span><i class="fas fa-lock"></i> Private</span>
                                                                    @endif
                                                                    <span id="member-count-{{ $community->id }}">
                                                                        <i class="fas fa-user"></i> {{ $community->members }} members
                                                                    </span>
                                                                </div>
                                                                <div class="mt-2 flex gap-2 join-actions">
                                                                    @auth
                                                                        @php
                                                                            $isMember = $community->communityMembers->contains(auth()->id());
                                                                        @endphp
                                                                        @if($isMember)
                                                                            <a href="{{ route('communities.show', $community) }}" class="text-xs px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                                                                                <i class="fas fa-comments"></i> Join Discussion
                                                                            </a>
                                                                            <button onclick="joinCommunity({{ $community->id }}, '{{ $community->slug }}')" 
                                                                                    id="join-btn-{{ $community->id }}"
                                                                                    class="text-xs px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                                                                <i class="fas fa-user-minus"></i> Leave
                                                                            </button>
                                                                        @else
                                                                            <button onclick="joinCommunity({{ $community->id }}, '{{ $community->slug }}')" 
                                                                                    id="join-btn-{{ $community->id }}"
                                                                                    class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                                                                <i class="fas fa-user-plus"></i> Join
                                                                            </button>
                                                                        @endif
                                                                    @else
                                                                        <a href="{{ route('login') }}" class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                                                            <i class="fas fa-sign-in-alt"></i> Login to Join
                                                                        </a>
                                                                    @endauth
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="events-cta">
                    <a href="{{ route('events.index') }}" class="events-cta-button">
                        <i class="fas fa-calendar-week mr-2"></i>
                        View All Events
                    </a>
                </div>
            @else
                <div class="no-events">
                    <div class="no-events-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3 class="no-events-text">No upcoming events at the moment</h3>
                    <p class="text-white opacity-80 mb-4">Check back later for new events and activities</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         📚 BOOKS SECTION
         ═══════════════════════════════════════════════════════════════════ --}}
    <section id="books" class="books-section" role="region" aria-labelledby="books-title">
        <div class="section-container">
            <header class="section-header">
                <h2 id="books-title" class="section-title">Explore Our Collection</h2>
                <p class="section-subtitle">
                    {{ $books->total() }} {{ Str::plural('book', $books->total()) }} waiting to be discovered
                </p>
            </header>

        @if($books->count() > 0)
                <div class="books-grid">
                @foreach($books as $book)
                        <article class="book-card">
                            <div class="book-image-container">
                                <img src="{{ $book->image_url }}" 
                                     alt="Cover of {{ $book->title }}"
                                     class="book-image"
                                     loading="lazy"
                                     onerror="this.src='{{ asset('assets/img/curved-images/curved14.jpg') }}'">
                                
                                <span class="badge {{ $book->is_available ? 'badge-available' : 'badge-unavailable' }}">
                                    {{ $book->is_available ? '✓ Available' : 'Unavailable' }}
                                    </span>
                            </div>

                            <div class="book-content">
                                <h3 class="book-title">{{ Str::limit($book->title, 60) }}</h3>
                                
                                <div class="book-meta">
                                    <div class="book-meta-item">
                                        <span class="book-meta-icon"><i class="fas fa-user" aria-hidden="true"></i></span>
                                        <span>{{ Str::limit($book->author, 35) }}</span>
                                    </div>
                                    
                                    <div class="book-meta-item">
                                        <span class="book-meta-icon"><i class="fas fa-bookmark" aria-hidden="true"></i></span>
                                        <span>
                                            @php
                                                $bookCategoryName = 'Uncategorized';
                                                if ($book->category_id && $book->relationLoaded('category')) {
                                                    $bookCat = $book->getRelation('category');
                                                    if ($bookCat) {
                                                        $bookCategoryName = $bookCat->category_name;
                                                    }
                                                } elseif ($book->getAttribute('category')) {
                                                    $bookCategoryName = $book->getAttribute('category');
                                                }
                                            @endphp
                                            {{ $bookCategoryName }}
                                        </span>
                                    </div>
                                    
                                    <div class="book-meta-item">
                                        <span class="book-meta-icon"><i class="fas fa-calendar" aria-hidden="true"></i></span>
                                        <span>{{ $book->published_year }} • {{ $book->language }}</span>
                                    </div>
                                </div>

                                <div class="book-actions">
                                    @auth
                                        @if($book->is_available && $book->ownerId !== Auth::id())
                                            <button onclick="openBorrowModal('{{ $book->id }}', '{{ e($book->title) }}', '{{ e($book->author) }}')"
                                                    class="btn-borrow"
                                                    type="button">
                                                <i class="fas fa-hand-holding-heart" aria-hidden="true"></i>
                                                <span>Borrow Now</span>
                                            </button>
                                            
                                            @if($book->price && $book->price > 0)
                                                <form action="{{ route('books.purchase', $book) }}" method="POST" style="width: 100%;">
                                                    @csrf
                                                    <button type="submit" class="btn-borrow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                        <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                                        <span>Buy - ${{ number_format($book->price, 2) }}</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif($book->ownerId === Auth::id())
                                            <div class="btn-disabled">
                                                <i class="fas fa-crown" aria-hidden="true"></i>
                                                Your Book
                                            </div>
                                        @else
                                            <div class="btn-disabled">
                                                <i class="fas fa-ban" aria-hidden="true"></i>
                                                Unavailable
                                            </div>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn-borrow">
                                            <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                                            <span>Login to Borrow</span>
                                        </a>
                                    @endauth
                                    
                                    <a href="{{ route('books.show', $book) }}" class="btn-details">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                        <span>View Details</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                @endforeach
            </div>

                <div class="pagination-wrapper">
                <div class="pagination-container">
                    {{ $books->links() }}
                </div>
            </div>
        @else
                <div style="text-align: center; padding: var(--space-20) 0;">
                    <i class="fas fa-book-open" style="font-size: 4rem; color: #667eea; margin-bottom: var(--space-4);" aria-hidden="true"></i>
                    <p style="font-size: var(--text-xl); color: var(--color-text-secondary);">
                        No books available yet. Check back soon!
                    </p>
            </div>
        @endif
    </div>
</section>

    {{-- ═══════════════════════════════════════════════════════════════════
         🎯 BORROW MODAL
         ═══════════════════════════════════════════════════════════════════ --}}
    @auth
    <div id="borrowModal" class="modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title" style="display: none;">
        <div class="modal-content">
            <form id="borrowForm" method="POST" action="{{ route('borrow-requests.store') }}">
                @csrf
                <input type="hidden" id="modal_book_id" name="book_id">
                
                <div class="modal-header">
                    <h3 id="modal-title" class="modal-title">📚 Borrow Request</h3>
                    <button type="button" onclick="closeBorrowModal()" class="modal-close" aria-label="Close dialog">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="modal-book-info">
                        <h4 id="modal_book_title" class="modal-book-title"></h4>
                        <p id="modal_book_author" class="modal-book-author"></p>
                    </div>
                    
                    <div class="form-group">
                        <label for="start_date" class="form-label">
                            Start Date <span style="color: #f5576c;">*</span>
                        </label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   min="{{ now()->addDay()->toDateString() }}"
                                   required 
                               class="form-input"
                               aria-required="true">
                        </div>
                        
                    <div class="form-group">
                        <label for="end_date" class="form-label">
                            End Date <span style="color: #f5576c;">*</span>
                        </label>
                            <input type="date" 
                                   id="end_date" 
                                   name="end_date" 
                                   required 
                               class="form-input"
                               aria-required="true">
                        </div>
                        
                    <div class="form-group">
                        <label for="notes" class="form-label">
                            Message <span style="color: var(--color-text-light); font-weight: 500;">(optional)</span>
                        </label>
                            <textarea id="notes" 
                                      name="notes" 
                                  rows="4" 
                                      maxlength="500"
                                  placeholder="Tell us why you'd like to borrow this book..."
                                  class="form-input form-textarea"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" onclick="closeBorrowModal()" class="btn-gradient-secondary">
                        <i class="fas fa-times" aria-hidden="true"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn-gradient-primary">
                        <i class="fas fa-paper-plane" aria-hidden="true"></i>
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endauth

    {{-- ═══════════════════════════════════════════════════════════════════
         🎬 JAVASCRIPT
         ═══════════════════════════════════════════════════════════════════ --}}
    @push('scripts')
        <script>
            // Carousel
            let currentSlide = 0;
            const slides = document.querySelectorAll('.carousel-item');
            const totalSlides = slides.length;
            
            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.remove('active');
                    if (i === index) slide.classList.add('active');
                });
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }
            
            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
            }
            
            document.getElementById('nextBtn')?.addEventListener('click', nextSlide);
            document.getElementById('prevBtn')?.addEventListener('click', prevSlide);
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') nextSlide();
                if (e.key === 'ArrowLeft') prevSlide();
            });
            
            if (totalSlides > 1) {
                let autoPlayInterval = setInterval(nextSlide, 6000);
                document.querySelector('.carousel-container')?.addEventListener('mouseenter', () => clearInterval(autoPlayInterval));
                document.querySelector('.carousel-container')?.addEventListener('mouseleave', () => {
                    autoPlayInterval = setInterval(nextSlide, 6000);
                });
            }
            
            // Modal
            function openBorrowModal(bookId, bookTitle, bookAuthor) {
                const modal = document.getElementById('borrowModal');
                document.getElementById('modal_book_id').value = bookId;
                document.getElementById('modal_book_title').textContent = bookTitle;
                document.getElementById('modal_book_author').textContent = 'By ' + bookAuthor;
                modal.style.display = 'flex';
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeBorrowModal() {
                const modal = document.getElementById('borrowModal');
                modal.style.display = 'none';
                modal.classList.add('hidden');
                document.getElementById('borrowForm').reset();
                document.body.style.overflow = '';
            }
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeBorrowModal();
            });
            
            document.getElementById('borrowModal')?.addEventListener('click', (e) => {
                if (e.target.id === 'borrowModal') closeBorrowModal();
            });
            
            // Date validation
            document.getElementById('start_date')?.addEventListener('change', function () {
                const endDateInput = document.getElementById('end_date');
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = '';
                }
            });

            // Communities modal functions
            function showCommunities(eventId) {
                const modal = document.getElementById('communities-modal-' + eventId);
                if (!modal) return;
                modal.style.display = 'flex';
                // force flex centering in case global CSS overrides classes
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                // reset any internal scroll
                modal.scrollTop = 0;
                // focus for accessibility
                setTimeout(() => modal.querySelector('.modal-content')?.focus(), 80);
                document.body.style.overflow = 'hidden';
            }

            function hideCommunities(eventId) {
                const modal = document.getElementById('communities-modal-' + eventId);
                if (!modal) return;
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }

            // Close modals when clicking outside
            document.addEventListener('click', function(event) {
                const modals = document.querySelectorAll('[id^="communities-modal-"]');
                modals.forEach(modal => {
                    if (event.target === modal) {
                        const eventId = modal.id.replace('communities-modal-', '');
                        hideCommunities(eventId);
                    }
                });
            });

            // Toggle Community Membership (Join/Leave)
            async function joinCommunity(communityId, communitySlug) {
                const button = document.getElementById('join-btn-' + communityId);
                const originalText = button.innerHTML;
                const isJoining = button.classList.contains('bg-blue-600');
                
                // Disable button and show loading state
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + (isJoining ? 'Joining...' : 'Leaving...');
                
                try {
                    const response = await fetch('/communities/' + communitySlug + '/join', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        // Update member count
                        const memberCountSpan = document.getElementById('member-count-' + communityId);
                        memberCountSpan.innerHTML = '<i class="fas fa-user"></i> ' + data.member_count + ' members';
                        
                        if (data.is_member) {
                            // User just joined - show "Join Discussion" link
                            const buttonContainer = button.parentElement;
                            buttonContainer.innerHTML = '<a href="/communities/' + communitySlug + '" class="text-xs px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition-colors"><i class="fas fa-comments"></i> Join Discussion</a><button onclick="joinCommunity(' + communityId + ', \'' + communitySlug + '\')" id="join-btn-' + communityId + '" class="text-xs px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors"><i class="fas fa-user-minus"></i> Leave</button>';
                            showNotification('Successfully joined the community! Check your email for details.', 'success');
                        } else {
                            // User just left - show "Join Community" button
                            button.classList.remove('bg-red-600', 'hover:bg-red-700');
                            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                            button.innerHTML = '<i class="fas fa-user-plus"></i> Join';
                            button.disabled = false;
                            showNotification('Successfully left the community.', 'success');
                        }
                    } else {
                        if (response.status === 422) {
                            showNotification(data.message, 'error');
                        } else {
                            throw new Error('Failed to toggle community membership');
                        }
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }
                } catch (error) {
                    console.error('Error toggling community membership:', error);
                    button.disabled = false;
                    button.innerHTML = originalText;
                    showNotification('Failed to update community membership. Please try again.', 'error');
                }
            }
            
            // Show notification function
            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transition-all ' + 
                    (type === 'success' ? 'bg-green-500' : 'bg-red-500');
                notification.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' mr-2"></i>' + message;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        </script>
    @endpush
</x-layouts.base>

