<x-layouts.portal :title="'Dashboard'" :role="'admin'">
    <x-page-header title="Dashboard administrator" description="Ringkasan data sekolah, aktivitas terbaru, dan pengingat hari ini."
        :actions="[
            ['label' => 'Kelola Konten', 'route' => 'portal.admin.news.index', 'icon' => 'newspaper'],
            ['label' => 'Pesan Masuk', 'route' => 'portal.admin.messages.index', 'icon' => 'message'],
        ]"
    />

    {{-- Statistik inti --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-portal-stat label="Siswa aktif" :value="$counts['students']" icon="users" tone="brand" />
        <x-portal-stat label="Guru aktif" :value="$counts['teachers']" icon="user-check" tone="accent" />
        <x-portal-stat label="Kelas aktif" :value="$counts['classes']" icon="school" tone="brand" />
        <x-portal-stat label="Presensi hari ini" :value="$todayAttendance" icon="calendar-check" tone="accent" />
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Tugas menunggu penilaian --}}
        <section class="panel lg:col-span-2">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Penilaian</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Tugas terbaru</h2>
                </div>
                <a href="{{ route('portal.admin.attendance.index') }}" class="btn btn-ghost btn-xs">Kelola presensi</a>
            </div>
            <div class="divide-y divide-line">
                @forelse ($assignmentSummary as $assignment)
                    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3.5">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-brand-950">{{ $assignment->title }}</p>
                            <p class="text-xs text-ink-soft">
                                {{ $assignment->classSubject?->schoolClass?->name }} · {{ $assignment->classSubject?->subject?->name }}
                                · Estimasi terkirim {{ $assignment->submissions_count }} / {{ $assignment->submissions_count + $assignment->pending_count }}
                            </p>
                        </div>
                        <span class="badge {{ $assignment->pending_count ? 'badge-amber' : 'badge-green' }}">
                            {{ $assignment->pending_count ? $assignment->pending_count.' belum dinilai' : 'Selesai' }}
                        </span>
                    </div>
                @empty
                    <x-empty-state title="Belum ada tugas" description="Tugas yang dibuat guru akan tampil di sini." icon="clipboard" />
                @endforelse
            </div>
        </section>

        {{-- Pesan baru --}}
        <section class="panel">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Kontak</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Pesan belum dibaca</h2>
                </div>
                <a href="{{ route('portal.admin.messages.index') }}" class="text-sm font-medium text-accent-700 hover:text-brand-900">Semua pesan</a>
            </div>
            <div class="divide-y divide-line">
                @forelse ($unreadMessages as $message)
                    <a href="{{ route('portal.admin.messages.show', $message) }}" class="block px-5 py-3.5 transition-colors hover:bg-paper">
                        <div class="flex items-center justify-between gap-3">
                            <p class="truncate text-sm font-medium text-brand-950">{{ $message->name }}</p>
                            <time class="shrink-0 text-xs text-ink-soft">{{ $message->created_at->translatedFormat('d M') }}</time>
                        </div>
                        <p class="mt-0.5 truncate text-xs text-ink-soft">{{ $message->subject }}</p>
                    </a>
                @empty
                    <x-empty-state title="Tidak ada pesan baru" description="Semua pesan telah dibaca." icon="inbox" />
                @endforelse
            </div>
        </section>
    </div>

    {{-- Berita terbaru & sebaran kelas --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <section class="panel lg:col-span-2">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Publikasi</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Berita terbaru</h2>
                </div>
                <a href="{{ route('portal.admin.news.index') }}" class="btn btn-ghost btn-xs">Kelola berita</a>
            </div>
            <div class="divide-y divide-line">
                @forelse ($recentNews as $news)
                    <a href="{{ route('portal.admin.news.edit', $news) }}" class="flex items-start gap-3 px-5 py-3.5 transition-colors hover:bg-paper">
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                            <x-icon name="newspaper" class="size-4" />
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-brand-950">{{ $news->title }}</p>
                            <p class="text-xs text-ink-soft">{{ $news->published_at?->translatedFormat('d F Y') ?? 'Draf' }}</p>
                        </div>
                    </a>
                @empty
                    <x-empty-state title="Belum ada berita" description="Tulis berita pertama sekolah Anda." icon="newspaper" />
                @endforelse
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Data</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Jumlah siswa per kelas</h2>
                </div>
            </div>
            <div class="space-y-2.5 px-5 py-4">
                @foreach ($classDistribution as $class)
                    <div>
                        <div class="mb-1 flex items-center justify-between text-sm">
                            <span class="font-medium text-brand-950">{{ $class->name }}</span>
                            <span class="text-xs text-ink-soft">{{ $class->students_count }} siswa</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-line/70">
                            @php $pct = $classDistribution->max('students_count') > 0 ? round($class->students_count / $classDistribution->max('students_count') * 100) : 0; @endphp
                            <div class="h-full rounded-full bg-brand-700" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.portal>