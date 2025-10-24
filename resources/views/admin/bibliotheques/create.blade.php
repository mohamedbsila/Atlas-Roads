<x-layouts.app>
    <div>
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.bibliotheques.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 mr-4 text-white rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
               style="background:linear-gradient(135deg,#64748b 0%,#475569 100%)">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">➕ Nouvelle Bibliothèque</h5>
                <p class="text-sm text-slate-400 mt-1">Ajouter une nouvelle bibliothèque au système</p>
            </div>
        </div>

        <form action="{{ route('admin.bibliotheques.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <!-- Basic Information -->
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-slate-700">
                                <i class="fas fa-info-circle mr-2 text-emerald-500"></i>
                                Informations Générales
                            </h6>
                        </div>
                        <div class="flex-auto p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Name -->
                                <div class="md:col-span-2">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="ml-1 font-bold text-xs text-slate-700">Nom <span class="text-red-500">*</span></label>
                                        <button type="button" id="suggest-names-btn"
                                                class="px-3 py-1 text-xs font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200"
                                                style="background:linear-gradient(135deg,#06b6d4 0%,#0891b2 100%)">
                                            <i class="fas fa-lightbulb mr-1"></i> Suggérer des noms
                                        </button>
                                    </div>
                                    <input type="text" name="name" id="name-field" value="{{ old('name') }}" required
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('name') border-red-500 @enderror"
                                           placeholder="Ex: Bibliothèque Centrale">
                                    <div id="name-suggestions" class="hidden mt-2"></div>
                                    @error('name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Adresse <span class="text-red-500">*</span></label>
                                    <input type="text" name="address" value="{{ old('address') }}" required
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('address') border-red-500 @enderror"
                                           placeholder="Ex: 123 Rue de la République">
                                    @error('address')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Ville <span class="text-red-500">*</span></label>
                                    <input type="text" name="city" value="{{ old('city') }}" required
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('city') border-red-500 @enderror"
                                           placeholder="Ex: Paris">
                                    @error('city')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Postal Code -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Code Postal</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('postal_code') border-red-500 @enderror"
                                           placeholder="Ex: 75001">
                                    @error('postal_code')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Téléphone</label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('phone') border-red-500 @enderror"
                                           placeholder="Ex: +33 1 23 45 67 89">
                                    @error('phone')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('email') border-red-500 @enderror"
                                           placeholder="Ex: contact@bibliotheque.fr">
                                    @error('email')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Website -->
                                <div class="md:col-span-2">
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Site Web</label>
                                    <input type="url" name="website" value="{{ old('website') }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('website') border-red-500 @enderror"
                                           placeholder="Ex: https://www.bibliotheque.fr">
                                    @error('website')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="ml-1 font-bold text-xs text-slate-700">Description</label>
                                        <button type="button" id="generate-description-btn"
                                                class="px-3 py-1 text-xs font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200"
                                                style="background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%)">
                                            <i class="fas fa-magic mr-1"></i> Générer avec AI
                                        </button>
                                    </div>
                                    <textarea name="description" id="description-field" rows="3"
                                              class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('description') border-red-500 @enderror"
                                              placeholder="Description de la bibliothèque...">{{ old('description') }}</textarea>
                                    <div id="ai-loading" class="hidden mt-2 text-xs text-purple-600">
                                        <i class="fas fa-spinner fa-spin mr-1"></i> Génération en cours avec Gemini AI...
                                    </div>
                                    @error('description')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hours & Capacity -->
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-slate-700">
                                <i class="fas fa-clock mr-2 text-blue-500"></i>
                                Horaires & Capacité
                            </h6>
                        </div>
                        <div class="flex-auto p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Opening Time -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Heure d'ouverture</label>
                                    <input type="time" name="opening_time" value="{{ old('opening_time') }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow">
                                </div>

                                <!-- Closing Time -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Heure de fermeture</label>
                                    <input type="time" name="closing_time" value="{{ old('closing_time') }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow">
                                </div>

                                <!-- Opening Days -->
                                <div class="md:col-span-2">
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Jours d'ouverture</label>
                                    <div class="flex flex-wrap gap-3 mt-2">
                                        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $day)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="opening_days[]" value="{{ $day }}"
                                                       class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500"
                                                       {{ in_array($day, old('opening_days', [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">{{ $day }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Capacity -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Capacité (nombre de livres)</label>
                                    <input type="number" name="capacity" value="{{ old('capacity') }}" min="0"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow"
                                           placeholder="Ex: 10000">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Coordinates -->
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-slate-700">
                                <i class="fas fa-map-marked-alt mr-2 text-purple-500"></i>
                                Coordonnées GPS
                            </h6>
                        </div>
                        <div class="flex-auto p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Latitude -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Latitude</label>
                                    <input type="number" step="0.00000001" name="latitude" value="{{ old('latitude') }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow"
                                           placeholder="Ex: 48.8566">
                                </div>

                                <!-- Longitude -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Longitude</label>
                                    <input type="number" step="0.00000001" name="longitude" value="{{ old('longitude') }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow"
                                           placeholder="Ex: 2.3522">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Image Upload -->
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-slate-700">
                                <i class="fas fa-image mr-2 text-pink-500"></i>
                                Image
                            </h6>
                        </div>
                        <div class="flex-auto p-6">
                            <!-- AI Image Prompt -->
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="ml-1 font-bold text-xs text-slate-700">🎨 Description pour l'image AI</label>
                                    <button type="button" id="auto-prompt-btn"
                                            class="px-2 py-1 text-xs font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200"
                                            style="background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%)">
                                        <i class="fas fa-wand-magic-sparkles text-xs"></i> Auto
                                    </button>
                                </div>
                                <textarea id="ai-image-prompt" rows="2"
                                          class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow"
                                          placeholder="Ex: modern library with wooden shelves and natural light"></textarea>
                                <button type="button" id="generate-image-btn"
                                        class="mt-2 w-full px-4 py-2 text-sm font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200"
                                        style="background:linear-gradient(135deg,#ec4899 0%,#d946ef 100%)">
                                    <i class="fas fa-magic mr-1"></i> Générer Image AI
                                </button>
                            </div>
                            
                            <!-- OR divider -->
                            <div class="flex items-center my-4">
                                <div class="flex-1 border-t border-gray-300"></div>
                                <span class="px-3 text-xs text-gray-500 font-semibold">OU</span>
                                <div class="flex-1 border-t border-gray-300"></div>
                            </div>
                            
                            <!-- File upload -->
                            <div class="mb-4">
                                <label class="mb-2 ml-1 font-bold text-xs text-slate-700">📁 Uploader une image</label>
                                <input type="file" name="image" accept="image/*" id="image-upload"
                                       class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow">
                                <p class="text-xs text-slate-500 mt-1">Formats: JPG, PNG, GIF (max 2MB)</p>
                                <input type="hidden" name="image_url" id="image-url-field">
                            </div>
                            
                            <!-- Image preview -->
                            <div id="image-preview" class="mt-4 hidden">
                                <p class="text-xs font-semibold text-slate-600 mb-2">Aperçu:</p>
                                <img src="" alt="Preview" class="w-full rounded-lg shadow-md">
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-slate-700">
                                <i class="fas fa-toggle-on mr-2 text-emerald-500"></i>
                                Statut
                            </h6>
                        </div>
                        <div class="flex-auto p-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked
                                       class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-3 text-sm font-semibold text-gray-700">Bibliothèque active</span>
                            </label>
                            <p class="text-xs text-slate-500 mt-2">Une bibliothèque inactive n'apparaîtra pas dans les recherches publiques.</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-3">
                        <button type="submit" 
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 font-bold text-center text-white uppercase rounded-lg cursor-pointer text-sm shadow-soft-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                                style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                            <i class="fas fa-save"></i>
                            Créer la bibliothèque
                        </button>
                        <a href="{{ route('admin.bibliotheques.index') }}" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-3 font-bold text-center text-slate-700 uppercase rounded-lg cursor-pointer text-sm bg-white border-2 border-slate-300 hover:bg-slate-50 transition-all duration-200">
                            <i class="fas fa-times"></i>
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Image preview
        document.getElementById('image-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    preview.querySelector('img').src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // AI Description Generator
        document.getElementById('generate-description-btn').addEventListener('click', async function() {
            const name = document.querySelector('input[name="name"]').value;
            const city = document.querySelector('input[name="city"]').value;
            const capacity = document.querySelector('input[name="capacity"]').value;
            
            if (!name || !city) {
                alert('Veuillez remplir le nom et la ville d\'abord!');
                return;
            }

            const loadingDiv = document.getElementById('ai-loading');
            const descField = document.getElementById('description-field');
            const btn = this;

            btn.disabled = true;
            loadingDiv.classList.remove('hidden');

            try {
                const response = await fetch('{{ route('admin.bibliotheques.ai.description') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name, city, capacity })
                });

                const data = await response.json();

                if (data.success) {
                    descField.value = data.description;
                    loadingDiv.innerHTML = '<i class="fas fa-check mr-1"></i> Description générée avec succès!';
                    loadingDiv.classList.add('text-emerald-600');
                    loadingDiv.classList.remove('text-purple-600');
                    setTimeout(() => {
                        loadingDiv.classList.add('hidden');
                        loadingDiv.classList.remove('text-emerald-600');
                        loadingDiv.classList.add('text-purple-600');
                        loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Génération en cours avec Gemini AI...';
                    }, 3000);
                } else {
                    alert('Erreur: ' + (data.message || 'Impossible de générer la description'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Une erreur s\'est produite lors de la génération de la description');
            } finally {
                btn.disabled = false;
                loadingDiv.classList.add('hidden');
            }
        });

        // Auto-generate prompt using Gemini AI
        document.getElementById('auto-prompt-btn').addEventListener('click', async function() {
            const name = document.querySelector('input[name="name"]').value;
            const city = document.querySelector('input[name="city"]').value;
            const description = document.querySelector('textarea[name="description"]').value;
            
            if (!name || !city) {
                alert('Veuillez remplir le nom et la ville d\'abord!');
                return;
            }

            const btn = this;
            const promptField = document.getElementById('ai-image-prompt');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const response = await fetch('{{ route('admin.bibliotheques.ai.prompt') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name, city, description })
                });

                const data = await response.json();

                if (data.success) {
                    promptField.value = data.prompt;
                    // Flash effect
                    promptField.classList.add('bg-purple-50');
                    setTimeout(() => promptField.classList.remove('bg-purple-50'), 1000);
                } else {
                    alert('Erreur: ' + (data.message || 'Impossible de générer le prompt'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Une erreur s\'est produite');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-wand-magic-sparkles text-xs"></i> Auto';
            }
        });

        // Pollinations AI Image Generator
        document.getElementById('generate-image-btn').addEventListener('click', function() {
            const prompt = document.getElementById('ai-image-prompt').value.trim();
            
            if (!prompt) {
                alert('Veuillez entrer une description pour l\'image!');
                return;
            }

            const btn = this;
            const previewDiv = document.getElementById('image-preview');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Génération en cours...';

            // Encode prompt for URL
            const encodedPrompt = encodeURIComponent(prompt);
            const imageUrl = `https://image.pollinations.ai/prompt/${encodedPrompt}?width=800&height=600&nologo=true`;

            // Show preview
            previewDiv.classList.remove('hidden');
            const img = previewDiv.querySelector('img');
            img.src = imageUrl;
            document.getElementById('image-url-field').value = imageUrl;

            // Reset button after image loads
            img.onload = function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-magic mr-1"></i> Générer Image AI';
            };

            img.onerror = function() {
                alert('Erreur lors du chargement de l\'image');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-magic mr-1"></i> Générer Image AI';
                previewDiv.classList.add('hidden');
            };
        });

        // AI Name Suggestions
        document.getElementById('suggest-names-btn').addEventListener('click', async function() {
            const city = document.querySelector('input[name="city"]').value;
            
            if (!city) {
                alert('Veuillez remplir la ville d\'abord!');
                return;
            }

            const btn = this;
            const suggestionsDiv = document.getElementById('name-suggestions');

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Génération...';

            try {
                const response = await fetch('{{ route('admin.bibliotheques.ai.names') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ city })
                });

                const data = await response.json();

                if (data.success && data.names) {
                    suggestionsDiv.classList.remove('hidden');
                    suggestionsDiv.innerHTML = `
                        <p class="text-xs font-semibold text-slate-600 mb-2">💡 Suggestions Gemini AI:</p>
                        <div class="flex flex-wrap gap-2">
                            ${data.names.map(name => `
                                <button type="button" class="px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors suggestion-name">
                                    ${name}
                                </button>
                            `).join('')}
                        </div>
                    `;

                    // Add click handlers to suggestions
                    suggestionsDiv.querySelectorAll('.suggestion-name').forEach(suggestionBtn => {
                        suggestionBtn.addEventListener('click', function() {
                            document.getElementById('name-field').value = this.textContent.trim();
                            suggestionsDiv.classList.add('hidden');
                        });
                    });
                } else {
                    alert('Erreur: ' + (data.message || 'Impossible de générer des suggestions'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Une erreur s\'est produite lors de la génération des suggestions');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-lightbulb mr-1"></i> Suggérer des noms';
            }
        });
    </script>
    @endpush
</x-layouts.app>

