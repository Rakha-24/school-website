<x-layouts.portal :title="'Ringkasan Siswa'" :role="'student'">
    @php
        $attendance = $statistics['attendance'] ?? collect();
        $attTotal = $attendance->sum();
        $attPresent = $attendance->get('present', 0);
        $attPct = $attTotal > 0 ? round($attPresent / $attTotal * 100) : null;
        $className = $student->schoolClass?->name;
    @endphp

    <x-page-header title="Ringkasan siswa"
        :description="'Selamat datang kembali, '.$student->user->name.($className ? ' dari kelas '.$className : '').' — '.$todayLabel.'.'" />

    {{-- Hero / sambutan --}}
    <section class="mb-6 overflow-hidden rounded-panel bg-brand-950">
        <div class="flex flex-wrap items-center justify-between gap-4 p-6 sm:p-8">
            <div class="min-w-0">
                <p class="eyebrow eyebrow-on-dark">Portal Siswa</p>
                <h2 class="mt-2 font-display text-2xl font-semibold tracking-tight text-white">Halo, {{ $student->user->name }}</h2>
                <p class="mt-1 text-sm text-brand-300">
                    {{ $className ? 'Kelas '.$className.' · ' : '' }}NIS {{ $student->student_number }} · {{ $student->schoolClass?->academic_year }}
                </p>
            </div>
            <span class="flex size-14 shrink-0 items-center justify-center rounded-full border border-brand-800 bg-brand-800 font-display text-xl font-semibold text-accent-300">
                {{ strtoupper(substr($student->user->name, 0, 1)) }}
            </span>
        </div>
    </section>

    {{-- Statistik inti --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-portal-stat label="Kehadiran bulan ini" :value="$attPct !== null ? $attPct.'%' : '—'" icon="check-circle" tone="brand" />
        <x-portal-stat label="Materi tersedia" :value="$statistics['materials']" icon="book-open" tone="accent" />
        <x-portal-stat label="Tugas dinilai" :value="$statistics['grades']" icon="chart-bar" tone="brand" />
        <x-portal-stat label="Tugas mendatang" :value="$upcomingAssignments->count()" icon="clipboard" tone="accent" />
    </section>

    <div class="mt-6 grid items-stretch gap-6 lg:grid-cols-2">
        {{-- Jadwal hari ini --}}
        <section class="panel flex h-full flex-col">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Hari ini</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Jadwal pelajaran</h2>
                </div>
                <a href="{{ route('portal.student.schedule.index') }}" class="text-sm font-medium text-accent-700 hover:text-brand-900">Jadwal lengkap</a>
            </div>
            <div class="flex-1 divide-y divide-line">
                @forelse ($todaySchedule as $item)
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3.5">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-brand-950">{{ $item->subject?->name }}</p>
                            <p class="text-xs text-ink-soft">
                                {{ $item->teacher?->user?->name ?? '—' }}
                                @if ($item->room)<span class="text-ink-faint">· Ruang {{ $item->room }}</span>@endif
                            </p>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-brand-700">
                            <x-icon name="clock" class="size-3.5 text-accent-600" />
                            {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                        </div>
                    </div>
                @empty
                    <x-empty-state icon="calendar" title="Tidak ada jadwal" description="Belum ada jadwal pelajaran untuk hari ini." />
                @endforelse
            </div>
        </section>

        {{-- Tugas mendatang --}}
        <section class="panel flex h-full flex-col">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Tenggat</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Tugas akan datang</h2>
                </div>
                <a href="{{ route('portal.student.assignments.index') }}" class="text-sm font-medium text-accent-700 hover:text-brand-900">Semua tugas</a>
            </div>
            <div class="flex-1 divide-y divide-line">
                @forelse ($upcomingAssignments as $assignment)
                    <a href="{{ route('portal.student.assignments.show', $assignment) }}" class="block px-5 py-3.5 transition-colors hover:bg-paper">
                        <div class="flex items-center justify-between gap-3">
                            <p class="min-w-0 truncate text-sm font-medium text-brand-950">{{ $assignment->title }}</p>
                            @if ($assignment->isOpen())
                                <span class="badge badge-accent">Terbuka</span>
                            @else
                                <span class="badge badge-amber">Tenggat dekat</span>
                            @endif
                        </div>
                        <p class="mt-0.5 text-xs text-ink-soft">
                            {{ $assignment->classSubject?->subject?->name }}
                            · Tenggat {{ $assignment->due_at->translatedFormat('d F Y, H:i') }}
                        </p>
                    </a>
                @empty
                    <x-empty-state icon="clipboard" title="Tidak ada tugas" description="Tidak ada tugas dengan tenggat mendatang." />
                @endforelse
            </div>
        </section>
    </div>

    <div class="mt-6 grid items-stretch gap-6 lg:grid-cols-2">
        {{-- Nilai terbaru --}}
        <section class="panel flex h-full flex-col">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Progress</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Nilai terbaru</h2>
                </div>
                <a href="{{ route('portal.student.grades.index') }}" class="text-sm font-medium text-accent-700 hover:text-brand-900">Semua nilai</a>
            </div>
            <div class="flex flex-1 flex-col divide-y divide-line py-1">
                @forelse ($recentGrades as $submission)
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3.5">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-brand-950">{{ $submission->assignment->title }}</p>
                            <p class="text-xs text-ink-soft">
                                {{ $submission->assignment->classSubject?->subject?->name }}
                                @if ($submission->graded_at)<span class="text-ink-faint">· dinilai {{ $submission->graded_at->translatedFormat('d M Y') }}</span>@endif
                            </p>
                        </div>
                        <span class="badge shrink-0 {{ $submission->score >= 80 ? 'badge-green' : ($submission->score >= 70 ? 'badge-amber' : 'badge-red') }}">
                            {{ $submission->score }}
                        </span>
                    </div>
                @empty
                    <x-empty-state icon="chart-bar" title="Belum ada nilai" description="Nilai tugas yang telah dinilai guru akan tampil di sini." />
                @endforelse
            </div>
        </section>

        {{-- Pengumuman terbaru --}}
        <section class="panel flex h-full flex-col">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Info</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Pengumuman terbaru</h2>
                </div>
                <a href="{{ route('portal.student.announcements.index') }}" class="text-sm font-medium text-accent-700 hover:text-brand-900">Semua pengumuman</a>
            </div>
            <div class="flex-1 divide-y divide-line">
                @forelse ($announcements as $announcement)
                    <a href="{{ route('portal.student.announcements.show', $announcement) }}" class="block px-5 py-3.5 transition-colors hover:bg-paper">
                        <div class="flex items-center justify-between gap-3">
                            <p class="truncate text-sm font-medium text-brand-950">{{ $announcement->title }}</p>
                            <time class="shrink-0 text-xs text-ink-soft">{{ $announcement->published_at?->translatedFormat('d M Y') }}</time>
                        </div>
                        <p class="mt-0.5 line-clamp-2 text-xs text-ink-soft">{{ $announcement->content }}</p>
                    </a>
                @empty
                    <x-empty-state icon="megaphone" title="Tidak ada pengumuman" description="Pengumuman untuk siswa akan tampil di sini." />
                @endforelse
            </div>
        </section>
    </div>
</x-layouts.portal>