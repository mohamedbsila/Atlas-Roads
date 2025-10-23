<x-layouts.app>
    <div>
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.rooms.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 mr-4 text-white rounded-lg shadow-md"
               style="background:linear-gradient(135deg,#64748b 0%,#475569 100%)">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">✏️ Modifier Salle</h5>
            </div>
        </div>

        <form action="{{ route('admin.rooms.update', $room) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-slate-700">Informations Générales</h6>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Section <span class="text-red-500">*</span></label>
                                <select name="section_id" required class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                                    @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ $room->section_id == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }} - {{ $section->bibliotheque->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Nom <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $room->name) }}" required
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                                </div>

                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Type <span class="text-red-500">*</span></label>
                                    <select name="style" required class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                                        <option value="individual" {{ $room->style == 'individual' ? 'selected' : '' }}>Individuel</option>
                                        <option value="group" {{ $room->style == 'group' ? 'selected' : '' }}>Groupe</option>
                                        <option value="conference" {{ $room->style == 'conference' ? 'selected' : '' }}>Conférence</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Capacité <span class="text-red-500">*</span></label>
                                    <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" required
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                                </div>

                                <div>
                                    <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Prix/heure (€) <span class="text-red-500">*</span></label>
                                    <input type="number" name="price_per_hour" value="{{ old('price_per_hour', $room->price_per_hour) }}" min="0" step="0.01" required
                                           class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-slate-700">Équipements</h6>
                        </div>
                        <div class="p-6">
                            <label class="flex items-center mb-3">
                                <input type="checkbox" name="has_pc" value="1" {{ $room->has_pc ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-700">PC disponible</span>
                            </label>
                            <label class="flex items-center mb-3">
                                <input type="checkbox" name="has_wifi" value="1" {{ $room->has_wifi ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-700">WiFi</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ $room->is_active ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="ml-2 text-sm text-gray-700">Salle active</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full px-6 py-3 font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                            style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                        <i class="fas fa-save mr-2"></i>Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>

