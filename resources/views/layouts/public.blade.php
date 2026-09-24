<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ ($seo ?? $metaDescription ?? '') ?: 'SMK Taruna Sains Kediri — sekolah menengah kejuruan dengan program unggulan Teknik Alat Berat.' }}">
    <title>{{ isset($title) ? $title.' — ' : '' }}SMK Taruna Sains Kediri</title>
    <link rel="icon" href="{{ asset('storage/favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-paper font-sans text-ink antialiased">

    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-brand-900 focus:px-4 focus:py-2 focus:text-white">Langsung ke konten</a>

    {{-- Top utility bar --}}
    <div class="bg-brand-950 text-brand-100">
        <div class="container-site flex h-9 items-center justify-between gap-4 text-xs">
            <div class="flex items-center gap-5">
                <span class="hidden items-center gap-1.5 sm:inline-flex"><x-icon name="map-pin" class="size-3.5 text-accent-400" /> Dusun Pule, Purwoasri, Kabupaten Kediri</span>
                <a href="tel:+62354512345" class="inline-flex items-center gap-1.5 transition-colors hover:text-accent-300"><x-icon name="phone" class="size-3.5 text-accent-400" /> (0354) 512345</a>
                <a href="mailto:smk.tarunasains@gmail.com" class="hidden items-center gap-1.5 transition-colors hover:text-accent-300 md:inline-flex"><x-icon name="mail" class="size-3.5 text-accent-400" /> smk.tarunasains@gmail.com</a>
            </div>
            @if (App\Models\Setting::get('ppdb_open', '1') === '1')
                <p class="inline-flex items-center gap-1.5 whitespace-nowrap">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent-400 opacity-60"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-accent-500"></span>
                    </span>
                    <strong class="font-medium text-white">PPDB 2026/2027 &nbsp;dibuka</strong>
                    <a href="{{ route('admission.show') }}" class="underline underline-offset-2 transition-colors hover:text-accent-300">Info pendaftaran</a>
                </p>
            @endif
        </div>
    </div>

    {{-- Main header --}}
    <header class="sticky top-0 z-40 border-b border-line bg-paper/95 backdrop-blur">
        <div class="container-site flex h-16 items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Beranda SMK Taruna Sains Kediri">
                <x-logo-mark class="size-10" />
                <span class="leading-tight">
                    <strong class="block font-display text-[15px] font-semibold uppercase tracking-tight text-brand-950">SMK Taruna Sains</strong>
                    <span class="block text-[11px] font-medium tracking-[0.18em] text-accent-600 uppercase">KEDIRI</span>
                </span>
            </a>

            <nav aria-label="Navigasi utama" class="hidden lg:flex">
                @php
                    $nav = [
                        ['label' => 'Beranda', 'route' => 'home'],
                        ['label' => 'Profil', 'route' => 'about.show'],
                        ['label' => 'Akademik', 'route' => 'academic.show'],
                        ['label' => 'Berita', 'route' => 'news.index'],
                        ['label' => 'Prestasi', 'route' => 'achievements.index'],
                        ['label' => 'Ekstrakurikuler', 'route' => 'extracurriculars.index'],
                        ['label' => 'Galeri', 'route' => 'gallery.index'],
                        ['label' => 'PPDB', 'route' => 'admission.show'],
                        ['label' => 'Kontak', 'route' => 'contact.show'],
                    ];
                @endphp
                <ul class="flex items-center gap-1">
                    @foreach ($nav as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-brand-800 transition-colors hover:text-accent-700 {{ request()->routeIs($item['route']) ? 'text-brand-950 underline decoration-accent-500 decoration-2 underline-offset-8' : '' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('portal.home') }}" class="btn btn-primary hidden sm:inline-flex"><x-icon name="shield-check" class="size-4" /> Member Area</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary hidden sm:inline-flex"><x-icon name="shield-check" class="size-4" /> Portal Siswa & Guru</a>
                @endauth
                <button type="button"
                        data-menu-toggle
                        class="inline-flex size-10 items-center justify-center rounded-md border border-line text-brand-900 transition-colors hover:bg-brand-50 lg:hidden"
                        aria-expanded="false" aria-controls="mobile-nav">
                    <span class="sr-only">Buka menu</span>
                    <x-icon name="bars" class="size-5" data-icon="open" />
                </button>
            </div>
        </div>

        {{-- Mobile nav --}}
        <div id="mobile-nav" data-menu-panel class="hidden border-t border-line bg-paper">
            <div class="container-site flex flex-col gap-1 py-4 lg:hidden">
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}" class="rounded-md px-3 py-2 text-sm font-medium text-brand-800 hover:bg-brand-50 {{ request()->routeIs($item['route']) ? 'bg-brand-50 text-brand-950' : '' }}">{{ $item['label'] }}</a>
                @endforeach
                @auth
                    <a href="{{ route('portal.home') }}" class="mt-2 btn btn-primary justify-center">Member Area</a>
                @else
                    <a href="{{ route('login') }}" class="mt-2 btn btn-primary justify-center">Portal Siswa & Guru</a>
                @endauth
            </div>
        </div>
    </header>

    <main id="main">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-brand-950 text-brand-200">
        <div class="container-site grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <div class="mb-4 flex items-center gap-3">
                    <x-logo-mark class="size-10" />
                    <span class="leading-tight">
                        <strong class="block font-display text-base font-semibold text-white">SMK Taruna Sains</strong>
                        <span class="block text-[11px] font-medium tracking-[0.18em] text-accent-400 uppercase">KEDIRI</span>
                    </span>
                </div>
                <p class="max-w-xs text-sm leading-relaxed text-brand-300">
                    Sekolah menengah kejuruan unggulan yang mencetak lulusan berkarakter, terampil, dan siap kerja melalui pembelajaran berbasis praktik dan industri.
                </p>
                <div class="mt-5 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-brand-800 px-3 py-1 text-xs font-medium text-brand-300">Terakreditasi A</span>
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-brand-800 px-3 py-1 text-xs font-medium text-brand-300">Guru Bersertifikasi</span>
                </div>
            </div>

            <nav aria-label="Navigasi halaman">
                <h3 class="mb-4 text-xs font-semibold tracking-[0.16em] text-white uppercase">Jelajahi</h3>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('about.show') }}" class="transition-colors hover:text-accent-300">Tentang Sekolah</a></li>
                    <li><a href="{{ route('academic.show') }}" class="transition-colors hover:text-accent-300">Akademik & Program</a></li>
                    <li><a href="{{ route('news.index') }}" class="transition-colors hover:text-accent-300">Berita & Kegiatan</a></li>
                    <li><a href="{{ route('achievements.index') }}" class="transition-colors hover:text-accent-300">Prestasi</a></li>
                    <li><a href="{{ route('extracurriculars.index') }}" class="transition-colors hover:text-accent-300">Ekstrakurikuler</a></li>
                    <li><a href="{{ route('gallery.index') }}" class="transition-colors hover:text-accent-300">Galeri</a></li>
                    <li><a href="{{ route('admission.show') }}" class="transition-colors hover:text-accent-300">PPDB {{ now()->year + 1 }}</a></li>
                </ul>
            </nav>

            <nav aria-label="Layanan">
                <h3 class="mb-4 text-xs font-semibold tracking-[0.16em] text-white uppercase">Layanan</h3>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('login') }}" class="transition-colors hover:text-accent-300">Portal Siswa</a></li>
                    <li><a href="{{ route('login') }}" class="transition-colors hover:text-accent-300">Portal Guru</a></li>
                    <li><a href="{{ route('login') }}" class="transition-colors hover:text-accent-300">Portal Admin</a></li>
                    <li><a href="{{ route('contact.show') }}" class="transition-colors hover:text-accent-300">Hubungi Kami</a></li>
                    <li><a href="{{ route('news.index') }}" class="transition-colors hover:text-accent-300">Pengumuman</a></li>
                </ul>
            </nav>

            <address class="not-italic">
                <h3 class="mb-4 text-xs font-semibold tracking-[0.16em] text-white uppercase">Kontak</h3>
                <ul class="space-y-3 text-sm text-brand-300">
                    <li class="flex items-start gap-3">
                        <x-icon name="map-pin" class="mt-0.5 size-4 text-accent-400" />
                        Dusun Pule, Desa Karangpakis, Kec. Purwoasri, <br>Kab. Kediri, Jawa Timur 64154
                    </li>
                    <li class="flex items-center gap-3">
                        <x-icon name="phone" class="size-4 text-accent-400" />
                        <a href="tel:+62354512345" class="transition-colors hover:text-accent-300">(0354) 512345</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <x-icon name="mail" class="size-4 text-accent-400" />
                        <a href="mailto:smk.tarunasains@gmail.com" class="transition-colors hover:text-accent-300">smk.tarunasains@gmail.com</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <x-icon name="clock" class="size-4 text-accent-400" />
                        Senin – Jumat, 07.00 – 16.00 WIB
                    </li>
                </ul>
            </address>
        </div>

        <div class="border-t border-brand-900">
            <div class="container-site flex flex-col items-center justify-between gap-2 py-5 text-xs text-brand-400 sm:flex-row">
                <p>Copyright © {{ now()->year }} SMK Taruna Sains Kediri. Seluruh hak cipta dilindungi.</p>
                <p class="font-display tracking-wide">Terwujudnya Insan Berkarakter & Siap Kerja</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>