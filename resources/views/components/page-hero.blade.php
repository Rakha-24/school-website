@props(['eyebrow' => null, 'title', 'lede' => null, 'image' => null, 'accent' => null])

<section class="relative overflow-hidden bg-brand-950 text-white">
    @if ($image)
        <x-image :src="$image" alt="" class="absolute inset-0 h-full w-full object-cover opacity-25" loading="eager" />
        <div class="absolute inset-0 bg-gradient-to-r from-brand-950 via-brand-950/80 to-brand-950/40"></div>
    @else
        <div class="absolute inset-0 bg-[radial-gradient(60rem_30rem_at_85%_-10%,rgba(185,106,23,0.28),transparent)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(40rem_22rem_at_-10%_120%,rgba(255,255,255,0.06),transparent)]"></div>
    @endif

    <div class="container-site relative py-16 sm:py-20">
        <div class="max-w-2xl">
            @if ($eyebrow)
                <p class="mb-3 eyebrow eyebrow-on-dark">{{ $eyebrow }}</p>
            @endif
            <h1 class="font-display text-3xl font-semibold tracking-tight text-white sm:text-4xl lg:text-[2.75rem] lg:leading-[1.1]">{{ $title }}</h1>
            @if ($lede)
                <p class="mt-4 max-w-xl text-base leading-relaxed text-brand-200 sm:text-lg">{{ $lede }}</p>
            @endif
            @isset($slot)
                <div class="mt-7">{{ $slot }}</div>
            @endisset
        </div>
    </div>

    @if ($accent)
        <div class="relative border-t border-brand-800 bg-brand-900/60">
            <div class="container-site flex flex-wrap items-center gap-x-10 gap-y-3 py-4">
                {{ $accent }}
            </div>
        </div>
    @endif
</section>