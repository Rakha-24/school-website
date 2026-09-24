@props(['eyebrow' => null, 'title', 'lede' => null, 'align' => 'left', 'as' => 'h2', 'onDark' => false])

<div {{ $attributes->merge(['class' => 'mb-8 max-w-2xl ' . ($align === 'center' ? 'mx-auto text-center' : '')]) }}>
    @if ($eyebrow)
        <p class="mb-3 @if ($align === 'center') mx-auto @endif eyebrow {{ $onDark ? 'eyebrow-on-dark' : '' }}">{{ $eyebrow }}</p>
    @endif
    <{{ $as }} class="font-display text-2xl font-semibold tracking-tight sm:text-3xl {{ $onDark ? 'text-white' : 'text-brand-950' }}">{{ $title }}</{{ $as }}>
    @if ($lede)
        <p class="mt-3 text-base leading-relaxed {{ $onDark ? 'text-brand-200' : 'text-ink-soft' }}">{{ $lede }}</p>
    @endif
</div>