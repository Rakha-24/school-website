<x-layouts.portal :title="'Dashboard Guru'" :role="'teacher'">
    <x-page-header title="Ringkasan" description="Selamat datang, {{ $teacher?->user?->name ?? auth()->user()->name }}. Berikut ringkasan aktivitas mengajar {{ $todayLabel }}."
        :actions="[
            ['label' => 'Beri nilai', 'route' => 'portal.teacher.submissions.index', 'icon' => 'check-circle'],
            ['label' => 'Buat materi', 'route' => 'portal.teacher.materials.create', 'icon' => 'book-open'],
        ]"
    />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    {{-- Statistik inti --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-portal-stat label="Jam mengajar hari ini" :value="$todaySchedule->count()" icon="clock" tone="brand" />
        <x-portal-stat label="Materi" :value="$materialCount" icon="book-open" tone="accent" />
        <x-portal-stat label="Tugas" :value="$assignmentCount" icon="clipboard" tone="brand" />
        <x-portal-stat label="Presensi tercatat" :value="$todayAttendance" icon="check-circle" tone="accent" />
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Tugas menunggu penilaian --}}
        <section class="panel lg:col-span-2">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Penilaian</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Perlu dinilai</h2>
                </div>
                <a href="{{ route('portal.teacher.submissions.index') }}" class="text-sm font-medium text-accent-700 hover:text-brand-900">Semua penilaian</a>
            </div>
            <div class="divide-y divide-line">
                @forelse ($pendingGrading as $assignment)
                    <a href="{{ route('portal.teacher.submissions.assignment', $assignment) }}" class="flex flex-wrap items-center justify-between gap-3 px-5 py-3.5 transition-colors hover:bg-paper">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-brand-950">{{ $assignment->title }}</p>
                            <p class="text-xs text-ink-soft">
                                {{ $assignment->classSubject?->schoolClass?->name }} · {{ $assignment->classSubject?->subject?->name }}
                            </p>
                        </div>
                        <span class="badge badge-amber">{{ $assignment->submitted_count }} belum dinilai</span>
                    </a>
                @empty
                    <x-empty-state title="Tidak ada tugas tertunda" description="Semua pengumpulan tugas telah dinilai." icon="check-circle" />
                @endforelse
            </div>
        </section>

        {{-- Jadwal hari ini --}}
        <section class="panel">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Jadwal</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Mengajar hari ini</h2>
                </div>
                <a href="{{ route('portal.teacher.schedule.index') }}" class="text-sm font-medium text-accent-700 hover:text-brand-900">Jadwal lengkap</a>
            </div>
            <div class="divide-y divide-line">
                @forelse ($todaySchedule as $item)
                    <div class="flex items-center gap-4 px-5 py-3.5">
                        <span class="shrink-0 rounded-md bg-brand-50 px-2.5 py-1 text-xs font-semibold text-brand-800">
                            {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-brand-950">{{ $item->subject?->name }}</p>
                            <p class="truncate text-xs text-ink-soft">{{ $item->schoolClass?->name }}{{ $item->room ? ' · Ruang '.$item->room : '' }}</p>
                        </div>
                    </div>
                @empty
                    <x-empty-state title="Tidak ada jadwal" description="Tidak ada jam mengajar hari ini." icon="calendar" />
                @endforelse
            </div>
        </section>
    </div>

    {{-- Pengumuman --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <section class="panel lg:col-span-2">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Informasi</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Pengumuman terbaru</h2>
                </div>
                <a href="{{ route('portal.teacher.announcements.index') }}" class="text-sm font-medium text-accent-700 hover:text-brand-900">Semua pengumuman</a>
            </div>
            <div class="divide-y divide-line">
                @forelse ($announcements as $announcement)
                    <a href="{{ route('portal.teacher.announcements.show', $announcement) }}" class="block px-5 py-3.5 transition-colors hover:bg-paper">
                        <div class="flex items-center justify-between gap-3">
                            <p class="truncate text-sm font-medium text-brand-950">{{ $announcement->title }}</p>
                            <time class="shrink-0 text-xs text-ink-soft">{{ $announcement->published_at?->translatedFormat('d M Y') }}</time>
                        </div>
                        <p class="mt-0.5 truncate text-xs text-ink-soft">{{ $announcement->author?->name }}</p>
                    </a>
                @empty
                    <x-empty-state title="Belum ada pengumuman" description="Pengumuman untuk guru akan tampil di sini." icon="megaphone" />
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.portal>