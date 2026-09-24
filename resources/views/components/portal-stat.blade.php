@props(['label', 'value', 'icon' => null, 'tone' => 'brand'])

<div {{ $attributes->merge(['class' => 'flex items-center gap-3.5 rounded-xl border border-line bg-white px-4 py-4 shadow-sm']) }}>
    @if ($icon)
        <span class="flex size-10 shrink-0 items-center justify-center rounded-full {{ $tone === 'accent' ? 'bg-accent-50 text-accent-600' : 'bg-brand-50 text-brand-700' }}">
            <x-icon :name="$icon" class="size-5" />
        </span>
    @endif
    <div class="min-w-0">
        <p class="truncate text-xs font-medium text-slate-500">{{ $label }}</p>
        <p class="font-display text-2xl font-bold tracking-tight text-slate-900">{{ $value }}</p>
    </div>
</div>