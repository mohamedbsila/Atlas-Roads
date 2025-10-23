<x-layouts.app>
    <div>
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.sections.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 mr-4 text-white rounded-lg shadow-md"
               style="background:linear-gradient(135deg,#64748b 0%,#475569 100%)">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">✏️ Modifier Section</h5>
            </div>
        </div>

        <form action="{{ route('admin.sections.update', $section) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <div class="mb-4">
                        <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Bibliothèque <span class="text-red-500">*</span></label>
                        <select name="bibliotheque_id" required class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                            @foreach($bibliotheques as $bib)
                            <option value="{{ $bib->id }}" {{ $section->bibliotheque_id == $bib->id ? 'selected' : '' }}>
                                {{ $bib->name }} - {{ $bib->city }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $section->name) }}" required
                               class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">
                    </div>

                    <div class="mb-4">
                        <label class="mb-2 ml-1 font-bold text-xs text-slate-700">Description</label>
                        <textarea name="description" rows="3"
                                  class="focus:shadow-soft-primary-outline text-sm leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 transition-all">{{ old('description', $section->description) }}</textarea>
                    </div>

                    <button type="submit" 
                            class="px-6 py-3 font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
                            style="background:linear-gradient(135deg,#10b981 0%,#059669 100%)">
                        <i class="fas fa-save mr-2"></i>Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.app>

