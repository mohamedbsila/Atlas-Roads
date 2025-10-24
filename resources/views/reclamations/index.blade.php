<x-layouts.reclamations>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.5s ease-out forwards;
        }

        .animate-delay-100 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .animate-delay-200 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .animate-delay-300 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .animate-delay-400 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(217, 70, 239, 0.1);
        }

        .priority-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .priority-card:hover {
            transform: translateX(10px);
            border-color: rgba(217, 70, 239, 0.3);
            box-shadow: 0 4px 12px rgba(217, 70, 239, 0.2);
        }

        .priority-card input:checked ~ * {
            background: linear-gradient(135deg, rgba(217, 70, 239, 0.1), rgba(236, 72, 153, 0.1));
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

        .floating-icon {
            animation: bounce 2s infinite;
        }
    </style>

<div class="container mx-auto px-4 max-w-7xl">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 text-white rounded-xl flex items-center justify-between animate-fade-in-up" 
                 style="background:linear-gradient(to right,#84cc16,#4ade80)">
                <span class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 text-white rounded-xl flex items-center justify-between animate-fade-in-up" 
                 style="background:linear-gradient(to right,#ef4444,#dc2626)">
                <span class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-wrap justify-between items-center mb-6">
            <div>
                <h5 class="mb-0 font-bold text-2xl gradient-text">📋 My Reclamations</h5>
                <p class="text-sm text-slate-500 mt-2 flex items-center">
                    <i class="fas fa-list-ul mr-2 text-purple-500"></i>
                    Track and manage your reported issues
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reclamations.chatbot') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg relative overflow-hidden group"
                   style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">
                    <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100"></div>
                    <span class="relative z-10 flex items-center">
                        <i class="fas fa-robot mr-2"></i> AI Assistant
                    </span>
                </a>
                <a href="{{ route('reclamations.create') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg relative overflow-hidden group"
                   style="background:linear-gradient(135deg,#d946ef,#ec4899)">
                    <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100"></div>
                    <span class="relative z-10 flex items-center">
                        <i class="fas fa-plus mr-2"></i> New Reclamation
                    </span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="relative flex flex-col min-w-0 mb-8 break-words bg-white border-0 shadow-2xl rounded-3xl bg-clip-border overflow-hidden animate-fade-in-up">
            <div class="absolute top-0 left-0 right-0 h-2" style="background:linear-gradient(90deg,#d946ef,#ec4899,#f97316,#06b6d4,#3b82f6)"></div>
            <div class="absolute right-8 top-8 floating-icon text-5xl opacity-10">🔍</div>
            <div class="p-6">
                <form method="GET" action="{{ route('reclamations.index') }}" class="flex gap-4 flex-wrap items-end" id="filterForm">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block mb-2 text-sm font-bold text-slate-700 flex items-center">
                            <i class="fas fa-filter text-blue-500 mr-2"></i> Status
                        </label>
                        <div class="relative">
                            <select name="status" class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-500">
                                <option value="">All Statuses</option>
                                <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>🔄 In Progress</option>
                                <option value="resolue" {{ request('status') == 'resolue' ? 'selected' : '' }}>✅ Resolved</option>
                                <option value="rejetee" {{ request('status') == 'rejetee' ? 'selected' : '' }}>❌ Rejected</option>
                            </select>
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-300"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <label class="block mb-2 text-sm font-bold text-slate-700 flex items-center">
                            <i class="fas fa-flag text-orange-500 mr-2"></i> Priority
                        </label>
                        <div class="relative">
                            <select name="priority" class="input-focus block w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-orange-500">
                                <option value="">All Priorities</option>
                                <option value="haute" {{ request('priority') == 'haute' ? 'selected' : '' }}>🔴 High</option>
                                <option value="moyenne" {{ request('priority') == 'moyenne' ? 'selected' : '' }}>🟡 Medium</option>
                                <option value="basse" {{ request('priority') == 'basse' ? 'selected' : '' }}>🟢 Low</option>
                            </select>
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-300"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="px-6 py-3 font-bold text-white rounded-xl text-sm shadow-xl hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300 relative overflow-hidden group"
                                style="background:linear-gradient(135deg,#d946ef,#ec4899,#f97316)">
                            <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100"></div>
                            <span class="relative z-10 flex items-center">
                                <i class="fas fa-filter mr-2"></i> Apply Filters
                            </span>
                        </button>
                        <a href="{{ route('reclamations.index') }}"
                           class="px-6 py-3 font-bold text-gray-700 bg-gradient-to-r from-gray-100 to-gray-200 rounded-xl text-sm shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-300 flex items-center border-2 border-gray-300">
                            <i class="fas fa-sync-alt mr-2"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reclamations List -->
        @if($reclamations->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach($reclamations as $reclamation)
                    <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-2xl rounded-3xl bg-clip-border hover:shadow-xl transition-all overflow-hidden animate-fade-in-up animate-delay-{{ $loop->iteration * 100 }}"
                        <div class="absolute top-0 left-0 right-0 h-2" style="background:linear-gradient(90deg,#d946ef,#ec4899,#f97316,#06b6d4,#3b82f6)"></div>
                        <div class="p-6 relative">
                            <!-- Status Badge -->
                            <div class="absolute top-6 right-6">
                                @php
                                    $statusStyles = [
                                        'en_attente' => 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white',
                                        'en_cours' => 'bg-gradient-to-r from-blue-400 to-blue-600 text-white',
                                        'resolue' => 'bg-gradient-to-r from-green-400 to-green-600 text-white',
                                        'rejetee' => 'bg-gradient-to-r from-red-400 to-red-600 text-white'
                                    ];
                                    $statusIcons = [
                                        'en_attente' => '⏳',
                                        'en_cours' => '🔄',
                                        'resolue' => '✅',
                                        'rejetee' => '❌'
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusStyles[$reclamation->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusIcons[$reclamation->statut] ?? '' }} {{ ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                                </span>
                            </div>

                            <!-- Priority Badge -->
                            <div class="mb-3">
                                @php
                                    $priorityStyles = [
                                        'haute' => 'bg-gradient-to-r from-red-400 to-red-600 text-white',
                                        'moyenne' => 'bg-gradient-to-r from-yellow-400 to-orange-500 text-white',
                                        'basse' => 'bg-gradient-to-r from-green-400 to-green-600 text-white'
                                    ];
                                    $priorityIcons = [
                                        'haute' => '🔴',
                                        'moyenne' => '🟡',
                                        'basse' => '🟢'
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded {{ $priorityStyles[$reclamation->priorite] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $priorityIcons[$reclamation->priorite] ?? '' }} {{ ucfirst($reclamation->priorite) }}
                                </span>
                            </div>

                            <!-- Reclamation Details -->
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-slate-700 mb-2">{{ $reclamation->titre }}</h4>
                                <p class="text-slate-500 text-sm mb-4">
                                    <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-xs font-semibold text-gray-700 mr-2 mb-2">
                                        {{ ucfirst($reclamation->categorie) }}
                                    </span>
                                    <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-xs font-semibold text-gray-700">
                                        {{ $reclamation->created_at->diffForHumans() }}
                                    </span>
                                </p>
                                
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($reclamation->description, 200) }}</p>
                                
                                @if($reclamation->solution)
                                    <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded-r-lg mb-3 mt-3">
                                        <p class="text-sm text-gray-800">
                                            <i class="fas fa-check-circle text-blue-500 mr-1"></i>
                                            <span class="font-medium">Réponse :</span> {{ Str::limit($reclamation->solution->contenu, 150) }}
                                        </p>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="far fa-user mr-1"></i>
                                            {{ $reclamation->solution->admin->name }} • 
                                            {{ $reclamation->solution->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="flex items-center text-xs text-gray-500 mt-2">
                                    <span class="flex items-center">
                                        <i class="far fa-user mr-1"></i>
                                        {{ $reclamation->user->name }}
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span class="flex items-center">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $reclamation->created_at->format('d/m/Y H:i') }}
                                    </span>
                                    @if($reclamation->solution)
                                        <span class="mx-2">•</span>
                                        <span class="flex items-center text-green-600 font-medium">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Répondu
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 mt-6">
                             
                                @if($reclamation->statut === 'en_attente')
                                    <a href="{{ route('reclamations.edit', $reclamation) }}" 
                                       class="flex-1 min-w-[120px] px-4 py-3 font-bold text-white rounded-xl text-sm shadow-xl hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300 relative overflow-hidden group"
                                       style="background:linear-gradient(135deg,#f59e0b,#f97316)">
                                        <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100"></div>
                                        <span class="relative z-10 flex items-center justify-center">
                                            <i class="fas fa-edit mr-2"></i> Edit
                                        </span>
                                    </a>
                                    <form action="{{ route('reclamations.destroy', $reclamation) }}" method="POST" class="flex-1 min-w-[120px]">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full px-4 py-3 font-bold text-white rounded-xl text-sm shadow-xl hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300 relative overflow-hidden group"
                                                style="background:linear-gradient(135deg,#ef4444,#dc2626)"
                                                onclick="return confirm('Are you sure you want to delete this reclamation?')">
                                            <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100"></div>
                                            <span class="relative z-10 flex items-center justify-center">
                                                <i class="fas fa-trash-alt mr-2"></i> Delete
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center animate-fade-in-up animate-delay-400">
                {{ $reclamations->links('pagination::tailwind') }}
                <style>
                    .pagination a, .pagination span {
                        @apply px-4 py-2 mx-1 text-sm font-semibold rounded-xl transition-all duration-300 hover:scale-105;
                    }
                    .pagination .current {
                        @apply bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-xl;
                    }
                    .pagination a {
                        @apply bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 hover:shadow-lg;
                    }
                </style>
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-3xl shadow-2xl relative overflow-hidden animate-fade-in-up">
                <div class="absolute top-0 left-0 right-0 h-2" style="background:linear-gradient(90deg,#d946ef,#ec4899,#f97316,#06b6d4,#3b82f6)"></div>
                <div class="absolute right-8 top-8 floating-icon text-5xl opacity-10">📝</div>
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                    <i class="fas fa-inbox text-4xl text-purple-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-700 mb-2">No Reclamations Found</h3>
                <p class="text-slate-500 mb-6 max-w-md mx-auto">
                    You haven't created any reclamations yet. Click the button below to report a new issue.
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('reclamations.create') }}" 
                       class="px-6 py-3 font-bold text-white rounded-xl text-sm shadow-xl hover:shadow-2xl transform hover:scale-105 active:scale-95 transition-all duration-300 relative overflow-hidden group"
                       style="background:linear-gradient(135deg,#d946ef,#ec4899,#f97316)">
                        <div class="absolute inset-0 shimmer-effect opacity-0 group-hover:opacity-100"></div>
                        <span class="relative z-10 flex items-center">
                            <i class="fas fa-plus mr-2"></i> Create New Reclamation
                        </span>
                    </a>
                    <a href="{{ route('home') }}" 
                       class="px-6 py-3 font-bold text-gray-700 bg-gradient-to-r from-gray-100 to-gray-200 rounded-xl text-sm shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 transition-all duration-300 flex items-center border-2 border-gray-300">
                        <i class="fas fa-home mr-2"></i> Back to Home
                    </a>
                </div>
            </div>
        @endif

        <!-- Additional Tip Section -->
        <div class="mt-8 text-center animate-fade-in-up animate-delay-500">
            <p class="text-sm text-gray-500 flex items-center justify-center gap-2">
                <i class="fas fa-users text-purple-500"></i>
                <span>Your feedback helps us improve our services!</span>
                <i class="fas fa-thumbs-up text-blue-500"></i>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading state to filter form
            const form = document.getElementById('filterForm');
            if (form) {
                const submitBtn = form.querySelector('button[type="submit"]');
                
                form.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Filtering...';
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                });

                // Add interactive feedback to inputs
                const inputs = form.querySelectorAll('select, input');
                inputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        this.parentElement.classList.add('ring-2', 'ring-purple-200', 'rounded-xl');
                    });
                    
                    input.addEventListener('blur', function() {
                        this.parentElement.classList.remove('ring-2', 'ring-purple-200');
                    });
                });
            }

            // Add animation to cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reclamation-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</x-layouts.reclamations>
