@props(['icon' => null, 'title' => 'Belum ada data', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center rounded-lg border border-dashed border-line bg-white px-6 py-14 text-center']) }}>
    @if ($icon)
        <div class="mb-4 inline-flex size-12 items-center justify-center rounded-full bg-brand-50 text-brand-300">
            <x-icon :name="$icon" class="size-6" />
        </div>
    @endif
    <p class="font-display text-base font-semibold text-brand-950">{{ $title }}</p>
    @if ($description)
        <p class="mt-1.5 max-w-sm text-sm text-ink-soft">{{ $description }}</p>
    @endif
    {{ $slot }}
</div>