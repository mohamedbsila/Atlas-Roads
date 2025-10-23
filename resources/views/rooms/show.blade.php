<x-layouts.base>
    @include('layouts.navbars.guest.nav')
    
    <div class="container mx-auto px-4 py-8 mt-20">
        <div class="flex items-center mb-6">
            <a href="{{ route('rooms.search') }}" 
               class="inline-flex items-center justify-center w-10 h-10 mr-4 text-white rounded-lg shadow-md"
               style="background:linear-gradient(135deg,#64748b 0%,#475569 100%)">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">🚪 {{ $room->name }}</h5>
                <p class="text-sm text-slate-400 mt-1">{{ $room->section->name }} - {{ $room->section->bibliotheque->name }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Room Details -->
            <div class="lg:col-span-2">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-slate-700">
                            <i class="fas fa-info-circle mr-2 text-cyan-500"></i>
                            Détails de la Salle
                        </h6>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-slate-500 mb-1">Type</p>
                                <p class="font-semibold text-slate-700">
                                    <span class="px-3 py-1 text-sm rounded
                                        {{ $room->style === 'individual' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $room->style === 'group' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $room->style === 'conference' ? 'bg-orange-100 text-orange-800' : '' }}">
                                        {{ ucfirst($room->style) }}
                                    </span>
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500 mb-1">Capacité</p>
                                <p class="font-semibold text-slate-700">
                                    <i class="fas fa-users text-cyan-500 mr-2"></i>{{ $room->capacity }} personnes
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500 mb-1">Prix</p>
                                <p class="font-bold text-2xl text-emerald-600">
                                    {{ number_format($room->price_per_hour, 2) }}€<span class="text-sm text-slate-500">/heure</span>
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-500 mb-1">Statut</p>
                                <p class="font-semibold">
                                    @if($room->is_active)
                                        <span class="px-3 py-1 text-sm bg-emerald-100 text-emerald-800 rounded">
                                            <i class="fas fa-check-circle mr-1"></i>Disponible
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded">
                                            <i class="fas fa-times-circle mr-1"></i>Indisponible
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="border-t pt-4 mt-4">
                            <p class="text-xs text-slate-500 mb-2">Équipements</p>
                            <div class="flex flex-wrap gap-2">
                                @if($room->has_pc)
                                <span class="px-3 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium">
                                    <i class="fas fa-desktop mr-2"></i>PC disponible
                                </span>
                                @endif
                                @if($room->has_wifi)
                                <span class="px-3 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm font-medium">
                                    <i class="fas fa-wifi mr-2"></i>WiFi
                                </span>
                                @endif
                                @if($room->equipments && count($room->equipments) > 0)
                                    @foreach($room->equipments as $equipment)
                                    <span class="px-3 py-2 bg-slate-50 text-slate-700 rounded-lg text-sm font-medium">
                                        <i class="fas fa-check mr-2"></i>{{ $equipment }}
                                    </span>
                                    @endforeach
                                @endif
                                @if(!$room->has_pc && !$room->has_wifi && (!$room->equipments || count($room->equipments) === 0))
                                <span class="text-slate-500 text-sm">Aucun équipement spécial</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Info -->
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-slate-700">
                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                            Localisation
                        </h6>
                    </div>
                    <div class="p-6">
                        <p class="font-semibold text-slate-700 mb-2">{{ $room->section->bibliotheque->name }}</p>
                        <p class="text-sm text-slate-600">
                            <i class="fas fa-map-pin mr-2"></i>{{ $room->section->bibliotheque->address }}<br>
                            {{ $room->section->bibliotheque->city }}
                            @if($room->section->bibliotheque->postal_code)
                                , {{ $room->section->bibliotheque->postal_code }}
                            @endif
                        </p>
                        @if($room->section->bibliotheque->phone)
                        <p class="text-sm text-slate-600 mt-2">
                            <i class="fas fa-phone mr-2"></i>{{ $room->section->bibliotheque->phone }}
                        </p>
                        @endif
                        @if($room->section->bibliotheque->email)
                        <p class="text-sm text-slate-600 mt-2">
                            <i class="fas fa-envelope mr-2"></i>{{ $room->section->bibliotheque->email }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Reservation Form -->
            <div class="lg:col-span-1">
                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border sticky top-4">
                    <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-slate-700">
                            <i class="fas fa-calendar-plus mr-2 text-emerald-500"></i>
                            Réserver
                        </h6>
                    </div>
                    <div class="p-6">
                        @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-100 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        </div>
                        @endif

                        <div id="availabilityAlert" class="hidden mb-4 p-3 rounded-lg">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-circle-notch fa-spin mr-2" id="checkingIcon"></i>
                                <i class="fas fa-check-circle mr-2 hidden text-emerald-600" id="availableIcon"></i>
                                <i class="fas fa-times-circle mr-2 hidden text-red-600" id="unavailableIcon"></i>
                                <span id="availabilityMessage">Vérification...</span>
                            </div>
                        </div>

                        <form action="{{ route('room-reservations.store') }}" method="POST" id="reservationForm">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">

                            <div class="mb-4">
                                <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Date <span class="text-red-500">*</span></label>
                                <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                                       class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                            </div>

                            <div class="mb-4">
                                <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Heure début <span class="text-red-500">*</span></label>
                                <input type="time" name="start_time" required
                                       class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                            </div>

                            <div class="mb-4">
                                <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Heure fin <span class="text-red-500">*</span></label>
                                <input type="time" name="end_time" required
                                       class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                            </div>

                            <div class="mb-4">
                                <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Notes (optionnel)</label>
                                <textarea name="notes" rows="2"
                                          class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all"
                                          placeholder="Précisions sur votre réservation..."></textarea>
                            </div>

                            <div id="priceEstimate" class="hidden mb-4 p-3 bg-emerald-50 rounded-lg">
                                <p class="text-sm text-slate-600">Prix estimé:</p>
                                <p class="text-2xl font-bold text-emerald-600"><span id="estimatedPrice">0</span>€</p>
                            </div>

                            <button type="submit" 
                                    id="reserveBtn"
                                    disabled
                                    class="w-full px-6 py-3 font-bold text-white rounded-lg shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                    style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                                <i class="fas fa-check-circle mr-2"></i>Réserver
                            </button>
                            <p class="text-xs text-slate-500 mt-2 text-center">
                                <i class="fas fa-info-circle mr-1"></i>Sélectionnez une date et un horaire pour vérifier la disponibilité
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const pricePerHour = {{ $room->price_per_hour }};
        const roomId = {{ $room->id }};
        const form = document.getElementById('reservationForm');
        const dateInput = form.querySelector('[name="date"]');
        const startTime = form.querySelector('[name="start_time"]');
        const endTime = form.querySelector('[name="end_time"]');
        const priceDiv = document.getElementById('priceEstimate');
        const priceSpan = document.getElementById('estimatedPrice');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        const availabilityAlert = document.getElementById('availabilityAlert');
        const checkingIcon = document.getElementById('checkingIcon');
        const availableIcon = document.getElementById('availableIcon');
        const unavailableIcon = document.getElementById('unavailableIcon');
        const availabilityMessage = document.getElementById('availabilityMessage');

        let checkTimeout;

        function calculatePrice() {
            if (startTime.value && endTime.value) {
                const start = new Date('2000-01-01 ' + startTime.value);
                const end = new Date('2000-01-01 ' + endTime.value);
                const hours = (end - start) / (1000 * 60 * 60);
                
                if (hours > 0) {
                    const price = (hours * pricePerHour).toFixed(2);
                    priceSpan.textContent = price;
                    priceDiv.classList.remove('hidden');
                } else {
                    priceDiv.classList.add('hidden');
                }
            }
        }

        async function checkAvailability() {
            if (!dateInput.value || !startTime.value || !endTime.value) {
                availabilityAlert.classList.add('hidden');
                return;
            }

            // Show checking state
            availabilityAlert.classList.remove('hidden');
            availabilityAlert.className = 'mb-4 p-3 rounded-lg bg-blue-50 border border-blue-200';
            checkingIcon.classList.remove('hidden');
            availableIcon.classList.add('hidden');
            unavailableIcon.classList.add('hidden');
            availabilityMessage.textContent = 'Vérification de la disponibilité...';
            availabilityMessage.className = 'text-blue-700';
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route('rooms.check-availability') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        room_id: roomId,
                        date: dateInput.value,
                        start_time: startTime.value,
                        end_time: endTime.value
                    })
                });

                const data = await response.json();

                checkingIcon.classList.add('hidden');

                if (data.available) {
                    // Available
                    availabilityAlert.className = 'mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200';
                    availableIcon.classList.remove('hidden');
                    unavailableIcon.classList.add('hidden');
                    availabilityMessage.textContent = '✓ Disponible! Prix: ' + data.price + '€';
                    availabilityMessage.className = 'text-emerald-700 font-semibold';
                    submitBtn.disabled = false;
                } else {
                    // Not available
                    availabilityAlert.className = 'mb-4 p-3 rounded-lg bg-red-50 border border-red-200';
                    availableIcon.classList.add('hidden');
                    unavailableIcon.classList.remove('hidden');
                    availabilityMessage.textContent = '✗ Non disponible pour ce créneau horaire';
                    availabilityMessage.className = 'text-red-700 font-semibold';
                    submitBtn.disabled = true;
                }
            } catch (error) {
                console.error('Error checking availability:', error);
                availabilityAlert.classList.add('hidden');
                submitBtn.disabled = false;
            }
        }

        function debounceCheck() {
            clearTimeout(checkTimeout);
            checkTimeout = setTimeout(checkAvailability, 500);
        }

        startTime.addEventListener('change', () => {
            calculatePrice();
            debounceCheck();
        });
        
        endTime.addEventListener('change', () => {
            calculatePrice();
            debounceCheck();
        });
        
        dateInput.addEventListener('change', debounceCheck);
    </script>
    @endpush
</x-layouts.base>

