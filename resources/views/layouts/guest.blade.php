<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' — ' : '' }}SMK Taruna Sains Kediri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-brand-950 font-sans text-ink antialiased">
    <div class="flex min-h-screen">

        {{-- Institutional panel --}}
        <aside class="relative hidden w-1/2 overflow-hidden border-r border-brand-800 lg:block xl:w-[55%]">
            <x-image src="seeds/campus.jpg" alt="" class="absolute inset-0 h-full w-full object-cover" loading="eager" />
            <div class="absolute inset-0 bg-gradient-to-t from-brand-950 via-brand-950/85 to-brand-950/40"></div>
            <div class="relative flex h-full flex-col justify-between p-12 xl:p-16">
                <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Kembali ke beranda">
                    <x-logo-mark class="size-11" />
                    <span class="leading-tight">
                        <strong class="block font-display text-base font-semibold text-white">SMK Taruna Sains</strong>
                        <span class="block text-[11px] font-medium tracking-[0.18em] text-accent-400 uppercase">Kediri</span>
                    </span>
                </a>
                <div class="max-w-md">
                    <p class="eyebrow eyebrow-on-dark mb-4">Portal Sekolah Terpadu</p>
                    <h2 class="font-display text-3xl font-semibold leading-tight tracking-tight text-white">
                        Satu titik masuk untuk siswa, guru, dan tenaga kependidikan.
                    </h2>
                    <p class="mt-4 text-sm leading-relaxed text-brand-200">
                        Akses materi pembelajaran, jadwal, presensi, penilaian tugas, dan pengumuman sekolah
                        dalam satu aplikasi yang aman.
                    </p>
                    <ul class="mt-8 space-y-3 text-sm text-brand-100">
                        <li class="flex items-center gap-3"><x-icon name="check-circle" class="size-5 text-accent-400" /> Materi & tugas digital per kelas</li>
                        <li class="flex items-center gap-3"><x-icon name="check-circle" class="size-5 text-accent-400" /> Presensi dan jadwal terpadu</li>
                        <li class="flex items-center gap-3"><x-icon name="check-circle" class="size-5 text-accent-400" /> Nilai dan umpan balik guru</li>
                        <li class="flex items-center gap-3"><x-icon name="check-circle" class="size-5 text-accent-400" /> Pengumuman yang relevan</li>
                    </ul>
                </div>
                <p class="text-xs text-brand-400">© {{ now()->year }} SMK Taruna Sains Kediri</p>
            </div>
        </aside>

        {{-- Form panel --}}
        <main class="flex w-full flex-col bg-paper md:bg-white lg:w-1/2 xl:w-[45%]">
            <div class="flex items-center justify-between border-b border-line px-6 py-4 lg:hidden">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <x-logo-mark class="size-9" />
                    <span class="leading-tight">
                        <strong class="block font-display text-sm font-semibold text-brand-950">SMK Taruna Sains</strong>
                        <span class="block text-[10px] font-medium tracking-[0.18em] text-accent-600 uppercase">KEDIRI</span>
                    </span>
                </a>
                <a href="{{ route('home') }}" class="text-sm font-medium text-brand-700 hover:text-accent-700">← Beranda</a>
            </div>

            <div class="flex flex-1 items-center justify-center px-6 py-12 sm:px-12">
                <div class="w-full max-w-md">
                    <h1 class="font-display text-2xl font-semibold tracking-tight text-brand-950 sm:text-[1.75rem]">{{ $title ?? 'Masuk' }}</h1>
                    @isset($intro)
                        <p class="mt-2 text-sm leading-relaxed text-ink-soft">{{ $intro }}</p>
                    @endisset
                    <div class="mt-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>