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
                <h5 class="mb-0 font-bold text-slate-700 text-xl">✏️ Modifier la Bibliothèque</h5>
                <p class="text-sm text-slate-400 mt-1">Mettre à jour les informations de la bibliothèque</p>
            </div>
        </div>

        <form action="{{ route('admin.bibliotheques.update', $bibliotheque) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
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
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Nom <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $bibliotheque->name) }}" required
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('name') border-red-500 @enderror"
                                           placeholder="Ex: Bibliothèque Centrale">
                                    @error('name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Adresse <span class="text-red-500">*</span></label>
                                    <input type="text" name="address" value="{{ old('address', $bibliotheque->address) }}" required
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('address') border-red-500 @enderror"
                                           placeholder="Ex: 123 Rue de la République">
                                    @error('address')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Ville <span class="text-red-500">*</span></label>
                                    <input type="text" name="city" value="{{ old('city', $bibliotheque->city) }}" required
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('city') border-red-500 @enderror"
                                           placeholder="Ex: Paris">
                                    @error('city')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Postal Code -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Code Postal</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code', $bibliotheque->postal_code) }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('postal_code') border-red-500 @enderror"
                                           placeholder="Ex: 75001">
                                    @error('postal_code')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Téléphone</label>
                                    <input type="tel" name="phone" value="{{ old('phone', $bibliotheque->phone) }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('phone') border-red-500 @enderror"
                                           placeholder="Ex: +33 1 23 45 67 89">
                                    @error('phone')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $bibliotheque->email) }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('email') border-red-500 @enderror"
                                           placeholder="Ex: contact@bibliotheque.fr">
                                    @error('email')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Website -->
                                <div class="md:col-span-2">
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Site Web</label>
                                    <input type="url" name="website" value="{{ old('website', $bibliotheque->website) }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('website') border-red-500 @enderror"
                                           placeholder="Ex: https://www.bibliotheque.fr">
                                    @error('website')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Description</label>
                                    <textarea name="description" rows="3"
                                              class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow @error('description') border-red-500 @enderror"
                                              placeholder="Description de la bibliothèque...">{{ old('description', $bibliotheque->description) }}</textarea>
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
                                    <input type="time" name="opening_time" value="{{ old('opening_time', $bibliotheque->opening_time ? date('H:i', strtotime($bibliotheque->opening_time)) : '') }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow">
                                </div>

                                <!-- Closing Time -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Heure de fermeture</label>
                                    <input type="time" name="closing_time" value="{{ old('closing_time', $bibliotheque->closing_time ? date('H:i', strtotime($bibliotheque->closing_time)) : '') }}"
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
                                                       {{ in_array($day, old('opening_days', $bibliotheque->opening_days ?? [])) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">{{ $day }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Capacity -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Capacité (nombre de livres)</label>
                                    <input type="number" name="capacity" value="{{ old('capacity', $bibliotheque->capacity) }}" min="0"
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
                                    <input type="number" step="0.00000001" name="latitude" value="{{ old('latitude', $bibliotheque->latitude) }}"
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow"
                                           placeholder="Ex: 48.8566">
                                </div>

                                <!-- Longitude -->
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Longitude</label>
                                    <input type="number" step="0.00000001" name="longitude" value="{{ old('longitude', $bibliotheque->longitude) }}"
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
                            <div class="mb-4">
                                <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Photo de la bibliothèque</label>
                                <input type="file" name="image" accept="image/*" id="image-upload"
                                       class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all focus:border-emerald-300 focus:outline-none focus:transition-shadow">
                                <p class="text-xs text-slate-500 mt-1">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
                            </div>
                            <div id="image-preview" class="mt-4 {{ $bibliotheque->image ? '' : 'hidden' }}">
                                <img src="{{ $bibliotheque->image ? Storage::url($bibliotheque->image) : '' }}" alt="Preview" class="w-full rounded-lg shadow-md">
                            </div>
                            @if($bibliotheque->image)
                                <p class="text-xs text-slate-500 mt-2">Image actuelle affichée ci-dessus. Téléchargez une nouvelle image pour la remplacer.</p>
                            @endif
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
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $bibliotheque->is_active) ? 'checked' : '' }}
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
                            Mettre à jour
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
    </script>
    @endpush
</x-layouts.app>

