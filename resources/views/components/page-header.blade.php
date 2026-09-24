@props(['title', 'description' => null, 'back' => null])

<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        @if ($back)
            <a href="{{ $back }}" class="mb-2 inline-flex items-center gap-1.5 text-xs font-semibold text-ink-500 transition-colors hover:text-brand-800">
                <x-icon name="arrow-right" class="size-3.5 rotate-180" /> Kembali
            </a>
        @endif
        <h1 class="font-display text-2xl font-semibold tracking-tight text-brand-950">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 max-w-2xl text-sm text-ink-soft">{{ $description }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endisset
</div>