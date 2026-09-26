@props([
    'src',
    'alt' => '',
    'class' => '',
    'loading' => 'lazy',
    'sizes' => null,
    'widths' => null,
    'fetchpriority' => null,
    'decoding' => 'async',
])

@php
    // Path gambar disimpan relatif terhadap storage/ dan bisa diganti lewat
    // panel admin, jadi varian WebP tidak selalu ada. Karena itu setiap file
    // dicek satu per satu: kalau WebP-nya tidak ada, tag <picture> tidak
    // emitted sama sekali dan browser memakai JPEG aslinya.
    //
    // Ekstensi diganti di akhir path, bukan lewat pathinfo(), supaya foldernya
    // tetap ikut (seeds/robotics.jpg -> seeds/robotics.webp).
    $stem = preg_replace('/\.[^.]+$/', '', $src);
    $original = public_path('storage/'.$src);
    $hasWebp = is_file(public_path('storage/'.$stem.'.webp'));

    $variants = collect(is_string($widths) ? explode(',', (string) $widths) : (array) ($widths ?: []))
        ->map(fn ($width) => trim((string) $width))
        ->filter()
        ->map(fn ($width) => ['w' => $width, 'file' => "{$stem}-{$width}.webp"])
        ->filter(fn ($variant) => is_file(public_path('storage/'.$variant['file'])))
        ->values();

    $webpSrcset = null;
    $descriptors = false;

    if ($hasWebp) {
        // <source> di dalam <picture> wajib punya srcset; kalau tidak, browser
        // mengabaikannya dan memakai <img> fallback — padahal preload sudah
        // mengambil WebP, hasilnya dua kali unduh.
        $candidates = $variants
            ->map(fn ($variant) => asset('storage/'.$variant['file']).' '.$variant['w'].'w');

        if ($candidates->isNotEmpty() && ($size = @getimagesize($original))) {
            $candidates->push(asset('storage/'.$stem.'.webp').' '.$size[0].'w');
            $descriptors = true;
        }

        $webpSrcset = $candidates->isNotEmpty()
            ? $candidates->implode(', ')
            : asset('storage/'.$stem.'.webp');
    }
@endphp

@if ($hasWebp)
    <picture>
        <source
            type="image/webp"
            srcset="{{ $webpSrcset }}"
            @if ($descriptors && $sizes) sizes="{{ $sizes }}" @endif
        >
        <img
            src="{{ asset('storage/'.$src) }}"
            alt="{{ $alt }}"
            class="{{ $class }}"
            loading="{{ $loading }}"
            decoding="{{ $decoding }}"
            @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
        >
    </picture>
@else
    <img
        src="{{ asset('storage/'.$src) }}"
        alt="{{ $alt }}"
        class="{{ $class }}"
        loading="{{ $loading }}"
        decoding="{{ $decoding }}"
        @if ($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
    >
@endif
