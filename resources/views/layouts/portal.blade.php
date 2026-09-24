@props(['title' => null, 'role' => null])

@php
    $roleNavs = [
        'student' => [
            'group' => 'Akademik',
            'items' => [
                ['route' => 'portal.student.dashboard', 'label' => 'Ringkasan', 'icon' => 'home'],
                ['route' => 'portal.student.materials.index', 'label' => 'Materi', 'icon' => 'book-open'],
                ['route' => 'portal.student.assignments.index', 'label' => 'Tugas', 'icon' => 'clipboard'],
                ['route' => 'portal.student.schedule.index', 'label' => 'Jadwal', 'icon' => 'calendar'],
                ['route' => 'portal.student.attendance.index', 'label' => 'Presensi', 'icon' => 'check-circle'],
                ['route' => 'portal.student.grades.index', 'label' => 'Nilai', 'icon' => 'chart-bar'],
            ],
        ],
        'teacher' => [
            'group' => 'Pengajaran',
            'items' => [
                ['route' => 'portal.teacher.dashboard', 'label' => 'Ringkasan', 'icon' => 'home'],
                ['route' => 'portal.teacher.materials.index', 'label' => 'Materi', 'icon' => 'book-open'],
                ['route' => 'portal.teacher.assignments.index', 'label' => 'Tugas', 'icon' => 'clipboard'],
                ['route' => 'portal.teacher.submissions.index', 'label' => 'Penilaian', 'icon' => 'check-circle'],
                ['route' => 'portal.teacher.attendance.index', 'label' => 'Presensi', 'icon' => 'calendar'],
                ['route' => 'portal.teacher.schedule.index', 'label' => 'Jadwal', 'icon' => 'clock'],
                ['route' => 'portal.teacher.announcements.index', 'label' => 'Pengumuman', 'icon' => 'megaphone'],
            ],
        ],
        'admin' => [
            'group' => 'Ringkasan',
            [
                ['route' => 'portal.admin.dashboard', 'label' => 'Dashboard Admin', 'icon' => 'home'],
                ['route' => 'portal.admin.messages.index', 'label' => 'Pesan Masuk', 'icon' => 'inbox'],
            ],
            'group2' => 'Manajemen',
            [
                ['route' => 'portal.admin.users.index', 'label' => 'Pengguna', 'icon' => 'users'],
                ['route' => 'portal.admin.teachers.index', 'label' => 'Guru', 'icon' => 'user'],
                ['route' => 'portal.admin.students.index', 'label' => 'Siswa', 'icon' => 'users'],
                ['route' => 'portal.admin.classes.index', 'label' => 'Kelas', 'icon' => 'building-library'],
                ['route' => 'portal.admin.subjects.index', 'label' => 'Mapel & Pengajar', 'icon' => 'academic-cap'],
                ['route' => 'portal.admin.schedules.index', 'label' => 'Jadwal', 'icon' => 'calendar'],
                ['route' => 'portal.admin.attendance.index', 'label' => 'Presensi', 'icon' => 'check-circle'],
                ['route' => 'portal.admin.announcements.index', 'label' => 'Pengumuman', 'icon' => 'megaphone'],
            ],
            'group3' => 'Konten Publik',
            [
                ['route' => 'portal.admin.news.index', 'label' => 'Berita', 'icon' => 'document'],
                ['route' => 'portal.admin.achievements.index', 'label' => 'Prestasi', 'icon' => 'trophy'],
                ['route' => 'portal.admin.extracurriculars.index', 'label' => 'Ekstrakurikuler', 'icon' => 'sparkles'],
                ['route' => 'portal.admin.gallery.index', 'label' => 'Galeri', 'icon' => 'eye'],
                ['route' => 'portal.admin.admission.index', 'label' => 'Info PPDB', 'icon' => 'flag'],
                ['route' => 'portal.admin.faqs.index', 'label' => 'FAQ', 'icon' => 'chat'],
                ['route' => 'portal.admin.settings.index', 'label' => 'Pengaturan', 'icon' => 'cog'],
            ],
        ],
    ];
    $nav = $roleNavs[$role] ?? [];
    $unread = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
    $title = $title ?? ($role ? ucfirst($role).' Portal' : 'Portal');
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ ucfirst($role ?? '') }} Portal — SMK Taruna Sains Kediri</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-paper font-sans text-ink antialiased">

    {{-- Sidebar drawer (mobile + desktop) --}}
    <div x-data="{ open: false }" class="min-h-screen lg:flex">
        <div x-cloak x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 z-40 bg-brand-950/60 lg:hidden"></div>

        <aside x-cloak :class="open ? 'translate-x-0' : '-translate-x-full'" x-transition
               class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-brand-800 bg-brand-950 text-brand-200 transition-transform lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
            <a href="{{ route('portal.home') }}" class="flex items-center gap-3 border-b border-brand-900 px-5 py-4">
                <x-logo-mark class="size-9" />
                <span class="leading-tight">
                    <strong class="block font-display text-sm font-semibold text-white">SMK Taruna Sains</strong>
                    <span class="block text-[10px] font-medium tracking-[0.18em] text-accent-400 uppercase">KEDIRI</span>
                </span>
            </a>

            <nav class="flex-1 overflow-y-auto px-3 py-5" aria-label="Navigasi portal">
                @php $groupLabels = ['group' => null, 'group2' => 'Manajemen', 'group3' => 'Konten Publik', 'group4' => 'Lainnya']; @endphp
                @foreach ($nav as $key => $block)
                    @if (is_string($key) && str_starts_with($key, 'group'))
                        <p class="mt-5 mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-brand-400">{{ $block }}</p>
                    @else
                        <ul class="space-y-1">
                            @foreach ($block as $item)
                                @php $active = request()->routeIs($item['route'].'*'); @endphp
                                <li>
                                    <a href="{{ route($item['route']) }}"
                                       class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors {{ $active ? 'bg-accent-500/15 text-accent-300' : 'text-brand-300 hover:bg-brand-900 hover:text-white' }}">
                                        <x-icon :name="$item['icon']" class="size-[18px] {{ $active ? 'text-accent-400' : 'text-brand-500 group-hover:text-accent-400' }}" />
                                        {{ $item['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </nav>

            <div class="border-t border-brand-900 px-4 py-4">
                <div class="flex items-center gap-3">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-brand-800 font-display text-sm font-semibold text-accent-300">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs capitalize text-brand-400">{{ auth()->user()->roleLabel() }}</p>
                    </div>
                </div>
                <a href="{{ route('home') }}" class="mt-3 flex items-center gap-2 rounded-md px-2 py-1.5 text-xs font-medium text-brand-400 transition-colors hover:text-white">
                    <x-icon name="arrow-right" class="size-3.5 rotate-180" /> Kembali ke situs publik
                </a>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Top bar --}}
            <header class="sticky top-0 z-30 border-b border-line bg-white">
                <div class="flex h-14 items-center justify-between gap-3 px-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="open = !open" class="inline-flex size-9 items-center justify-center rounded-md border border-line text-brand-900 lg:hidden" aria-label="Buka menu navigasi">
                            <x-icon name="bars" class="size-5" />
                        </button>
                        <p class="hidden text-sm font-display font-semibold tracking-tight text-brand-950 sm:block">{{ $title }}</p>
                        @isset($breadcrumb)
                            <p class="text-xs text-ink-faint">{{ $breadcrumb }}</p>
                        @endisset
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('portal.notifications') }}" class="relative inline-flex size-9 items-center justify-center rounded-md border border-line text-brand-700 transition-colors hover:bg-brand-50" aria-label="Notifikasi">
                            <x-icon name="bell" class="size-[18px]" />
                            @if ($unread > 0)
                                <span class="absolute -top-1 -right-1 flex size-4 items-center justify-center rounded-full bg-accent-600 text-[10px] font-bold text-white">{{ min($unread, 9) }}</span>
                            @endif
                        </a>
                        <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                            <button type="button" @click="open = !open"
                                    class="flex items-center gap-2 rounded-md border border-line py-1.5 pr-2 pl-1.5 transition-colors hover:bg-brand-50" aria-haspopup="menu" :aria-expanded="open">
                                <span class="flex size-7 items-center justify-center rounded-full bg-brand-900 font-display text-xs font-semibold text-accent-300">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                <x-icon name="chevron-down" class="hidden size-3.5 text-brand-400 sm:block" />
                            </button>
                            <div x-cloak x-show="open" @click.outside="open = false" x-transition
                                 class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-md border border-line bg-white shadow-pop">
                                <p class="border-b border-line px-4 py-2.5">
                                    <span class="block truncate text-sm font-medium text-brand-950">{{ auth()->user()->name }}</span>
                                    <span class="block truncate text-xs text-ink-faint">{{ auth()->user()->email }}</span>
                                </p>
                                <div class="py-1">
                                    <a href="{{ route('portal.profile') }}" class="block px-4 py-2 text-sm text-brand-800 hover:bg-brand-50">Profil saya</a>
                                    <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-brand-800 hover:bg-brand-50">Situs publik</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center px-4 py-2 text-left text-sm text-red-700 hover:bg-red-50">Keluar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8">
                <div class="mx-auto w-full max-w-6xl">
                    {{ $slot }}
                </div>
            </main>

            <footer class="border-t border-line px-6 py-4">
                <p class="text-center text-xs text-ink-faint">SMK Taruna Sains Kediri — Portal {{ ucfirst($role ?? '') }} · © {{ now()->year }}</p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>