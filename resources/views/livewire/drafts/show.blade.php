<div class="w-full max-w-4xl mx-auto">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('drafts.index') }}" class="text-slate-600">← Retour</a>
        <div class="flex items-center gap-3">
            <a href="{{ route('drafts.edit', $draft) }}" class="px-3 py-1.5 border rounded">Modifier</a>
        </div>
    </div>

    <h1 class="text-2xl font-bold mb-2">{{ $draft->title }}</h1>
    <p class="text-slate-600 mb-4">{{ optional($draft->story_date)->format('d/m/Y') }}</p>

    @if($draft->cover_image)
        <img src="{{ asset('storage/'.$draft->cover_image) }}" class="rounded mb-4 max-h-80" />
    @endif

    <div class="prose max-w-none">
        {!! nl2br(e($draft->content)) !!}
    </div>
</div>
