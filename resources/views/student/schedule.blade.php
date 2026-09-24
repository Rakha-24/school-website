<x-layouts.portal :title="'Jadwal Pelajaran'" :role="'student'">
    @php
        $todayKey = \Carbon\Carbon::now()->isoWeekday();
        $summaryRows = collect();
        foreach ($week as $day) {
            foreach ($day['items'] as $item) {
                $key = $item->subject?->id ?? $item->subject_id;
                $row = $summaryRows->get($key);
                $summaryRows->put($key, [
                    'subject' => $item->subject?->name ?? '—',
                    'teacher' => $item->teacher?->user?->name ?? '—',
                    'count' => ($row['count'] ?? 0) + 1,
                ]);
            }
        }
    @endphp

    <x-page-header title="Jadwal pelajaran" :description="'Jadwal mingguan kelas '.($class?->name ?? '—').' (Senin–Jumat).'" />

    {{-- Navigasi hari --}}
    <nav class="no-scrollbar mb-6 flex gap-2 overflow-x-auto" aria-label="Hari dalam seminggu">
        @foreach ($week as $day)
            <a href="#day-{{ $day['number'] }}"
               class="inline-flex shrink-0 flex-col rounded-md border px-4 py-2.5 transition-colors {{ $day['number'] === $todayKey ? 'border-brand-800 bg-brand-800 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' }}">
                <span class="text-[11px] font-semibold uppercase tracking-wide {{ $day['number'] === $todayKey ? 'text-accent-300' : 'text-ink-faint' }}">{{ $day['label'] }}</span>
                <span class="text-xs font-medium">{{ $day['date']->translatedFormat('d M') }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Agenda per hari --}}
    <div class="space-y-6">
        @foreach ($week as $day)
            <section id="day-{{ $day['number'] }}" class="panel scroll-mt-20">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">Hari {{ $day['number'] }}</p>
                        <h2 class="font-display text-lg font-semibold text-brand-950">{{ $day['label'] }}, {{ $day['date']->translatedFormat('d F Y') }}</h2>
                    </div>
                    <span class="badge badge-brand">{{ $day['items']->count() }} pertemuan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-site">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Mata pelajaran</th>
                                <th>Pengajar</th>
                                <th>Ruang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($day['items'] as $item)
                                <tr>
                                    <td class="whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-700">
                                            <x-icon name="clock" class="size-3.5 text-accent-600" />
                                            {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="font-medium text-brand-950">{{ $item->subject?->name }}</td>
                                    <td class="text-ink-soft">{{ $item->teacher?->user?->name ?? '—' }}</td>
                                    <td class="text-ink-soft">{{ $item->room ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <x-empty-state title="Tidak ada jadwal" description="Belum ada pelajaran pada hari ini." icon="calendar" />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach
    </div>

    {{-- Rekap per mata pelajaran --}}
    <section class="panel mt-6">
        <div class="panel-head">
            <div>
                <p class="eyebrow">Rekap</p>
                <h2 class="font-display text-lg font-semibold text-brand-950">Ringkasan mata pelajaran minggu ini</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table-site">
                <thead>
                    <tr>
                        <th>Mata pelajaran</th>
                        <th>Pengajar</th>
                        <th class="w-40">Pertemuan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($summaryRows->sortByDesc('count') as $row)
                        <tr>
                            <td class="font-medium text-brand-950">{{ $row['subject'] }}</td>
                            <td class="text-ink-soft">{{ $row['teacher'] }}</td>
                            <td><span class="badge badge-brand">{{ $row['count'] }}× minggu ini</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <x-empty-state title="Belum ada jadwal" description="Jadwal kelas akan tampil setelah guru melengkapi data." icon="calendar" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.portal>