@props(['icon' => null, 'label' => null, 'value'])

<div {{ $attributes->merge(['class' => 'p-6 first:pl-0']) }}>
    @if ($label)
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-brand-300">{{ $label }}</p>
    @endif
    <p class="mt-2 font-display text-3xl font-semibold tracking-tight text-white sm:text-4xl">{{ $value }}</p>
</div>