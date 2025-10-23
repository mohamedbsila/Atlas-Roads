<x-layouts.app>
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h5 class="mb-0 font-bold text-slate-700 text-xl">📚 Gestion des Sections</h5>
                <p class="text-sm text-slate-400 mt-1">Organisez les sections de vos bibliothèques</p>
            </div>
            <a href="{{ route('admin.sections.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 font-bold text-white rounded-lg shadow-md hover:shadow-lg transition-all"
               style="background:linear-gradient(135deg,#f97316 0%,#ea580c 100%)">
                <i class="fas fa-plus"></i>
                Nouvelle Section
            </a>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-100 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <div class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-slate-50">
                            <tr>
                                <th class="px-6 py-3">Nom</th>
                                <th class="px-6 py-3">Bibliothèque</th>
                                <th class="px-6 py-3">Description</th>
                                <th class="px-6 py-3">Salles</th>
                                <th class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium">{{ $section->name }}</td>
                                <td class="px-6 py-4">
                                    {{ $section->bibliotheque->name }}
                                    <span class="block text-xs text-slate-500">{{ $section->bibliotheque->city }}</span>
                                </td>
                                <td class="px-6 py-4">{{ Str::limit($section->description, 50) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                        {{ $section->rooms_count }} salles
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.sections.edit', $section) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Supprimer cette section?')" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    Aucune section créée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $sections->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

