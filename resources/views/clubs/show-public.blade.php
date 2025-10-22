<x-layouts.base>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Club Header -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <!-- Club Image/Header -->
            <div class="relative h-64 overflow-hidden">
                @if($club->image)
                    <img src="{{ asset('storage/' . $club->image) }}" alt="{{ $club->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-users text-white text-6xl"></i>
                    </div>
                @endif
                <!-- Back Button -->
                <div class="absolute top-4 left-4">
                    <a href="{{ route('home') }}" class="bg-white/90 backdrop-blur-sm text-gray-600 p-3 rounded-full shadow-lg hover:bg-gray-600 hover:text-white transition-all duration-200" title="Back to Home">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>

            <!-- Club Info -->
            <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $club->name }}</h1>
                        @if($club->location)
                            <div class="flex items-center text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <span>{{ $club->location }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ $club->meetings_count }} meetings
                        </div>
                    </div>
                </div>

                @if($club->description)
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($club->description)) !!}
                    </div>
                @endif
            </div>
        </div>

        <!-- Meetings Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                    Meetings for {{ $club->name }}
                </h2>
                <a href="{{ route('home') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    <span class="font-semibold">Back to Home</span>
                </a>
            </div>

            @if($club->meetings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($club->meetings as $meeting)
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-800 line-clamp-1">{{ $meeting->title }}</h3>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                                    <span class="text-sm font-medium">{{ $meeting->scheduled_at->format('M d, Y - H:i') }}</span>
                                </div>

                                @if($meeting->location)
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                                        <span class="text-sm">{{ $meeting->location }}</span>
                                    </div>
                                @endif

                                @if($meeting->agenda)
                                    <div class="mt-4">
                                        <p class="text-gray-700 text-sm leading-relaxed line-clamp-3">{{ $meeting->agenda }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Status Badge -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                @if($meeting->scheduled_at->isFuture())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Upcoming
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Past
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Meetings State -->
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-plus text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No meetings yet</h3>
                    <p class="text-gray-600 mb-6">Meetings will be scheduled soon!</p>
                </div>
            @endif
        </div>

        <!-- Club Chat Section -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mt-8">
            <h2 class="text-3xl font-bold text-gray-800 flex items-center mb-6">
                <i class="fas fa-comments text-green-600 mr-3"></i>
                Discussion du club
            </h2>

            <!-- Messages List -->
            <div id="chatMessages" class="h-72 overflow-y-auto border border-gray-200 rounded-xl p-4 bg-gray-50">
                <div id="chatEmpty" class="text-center text-gray-500">Chargement des messages...</div>
            </div>

            <!-- Send Message -->
            <form id="chatForm" class="mt-4 flex gap-3" onsubmit="return false;">
                <input type="text" id="chatInput" maxlength="500" placeholder="Écrire un message..." class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <button id="chatSend" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-green-700 hover:to-emerald-700 transition">Envoyer</button>
            </form>

            <script>
                (function() {
                    const clubId = {{ $club->id }};
                    const list = document.getElementById('chatMessages');
                    const empty = document.getElementById('chatEmpty');
                    const input = document.getElementById('chatInput');
                    const sendBtn = document.getElementById('chatSend');
                    const token = '{{ csrf_token() }}';

                    function render(messages) {
                        list.innerHTML = '';
                        if (!messages || messages.length === 0) {
                            const d = document.createElement('div');
                            d.className = 'text-center text-gray-500';
                            d.textContent = 'Aucun message pour le moment.';
                            list.appendChild(d);
                            return;
                        }
                        messages.forEach(m => {
                            const item = document.createElement('div');
                            item.className = 'mb-3';
                            item.innerHTML = `
                                <div class="bg-white border border-gray-200 rounded-lg p-3">
                                    <div class="text-sm text-gray-600 mb-1"><strong>${m.user ?? 'Utilisateur'}</strong> • <span>${m.created_at}</span></div>
                                    <div class="text-gray-800">${escapeHtml(m.message)}</div>
                                </div>
                            `;
                            list.appendChild(item);
                        });
                        list.scrollTop = list.scrollHeight;
                    }

                    function escapeHtml(str) {
                        return (str || '').replace(/[&<>"]+/g, function(s) {
                            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' };
                            return map[s] || s;
                        });
                    }

                    async function load() {
                        try {
                            const res = await fetch(`/clubs/${clubId}/messages`);
                            const data = await res.json();
                            empty && (empty.style.display = 'none');
                            render(data.data || []);
                        } catch (e) {
                            // silent fail
                        }
                    }

                    async function send() {
                        const msg = input.value.trim();
                        if (!msg) return;
                        try {
                            const res = await fetch(`/clubs/${clubId}/messages`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                },
                                body: JSON.stringify({ message: msg })
                            });
                            if (res.ok) {
                                input.value = '';
                                await load();
                            }
                        } catch (e) {}
                    }

                    sendBtn.addEventListener('click', send);
                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            send();
                        }
                    });

                    load();
                    setInterval(load, 8000);
                })();
            </script>
        </div>
    </div>
</div>
</x-layouts.base>
