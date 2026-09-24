@props(['title', 'description' => null, 'icon' => null, 'children' => true])

<div {{ $attributes->merge(['class' => 'group rounded-lg border-t-2 border-brand-800 bg-white p-6 shadow-card transition-shadow hover:shadow-card-lg']) }}>
    @if ($icon)
        <div class="mb-4 inline-flex size-11 items-center justify-center rounded-md bg-brand-50 text-brand-800 ring-1 ring-inset ring-brand-100">
            <x-icon :name="$icon" class="size-5" />
        </div>
    @endif
    @isset($title)
        <h3 class="font-display text-lg font-semibold tracking-tight text-brand-950">{{ $title }}</h3>
    @endisset
    @if ($description)
        <p class="mt-2 text-sm leading-relaxed text-ink-soft">{{ $description }}</p>
    @endif
    {{ $slot }}
</div>