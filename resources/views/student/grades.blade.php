<x-layouts.portal :title="'Nilai'" :role="'student'">
    @php
        $groups = collect();
        foreach ($rows as $row) {
            $subject = $row['assignment']->classSubject?->subject?->name ?? 'Lainnya';
            if (! $groups->has($subject)) {
                $groups->put($subject, collect());
            }
            $groups->get($subject)->push($row);
        }
    @endphp

    <x-page-header title="Nilai" description="Rekap nilai tugas yang telah dinilai guru untuk kelas Anda." />

    @if (session('status'))
        <div class="mb-6 flex items-center gap-2 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="size-4" /> {{ session('status') }}
        </div>
    @endif

    @if ($groups->isEmpty())
        <x-empty-state icon="chart-bar" title="Belum ada nilai"
            description="Nilai yang diperoleh dari tugas yang dinilai guru akan tampil di sini." />
    @endif

    <div class="space-y-6">
        @foreach ($groups as $subject => $group)
            @php $average = round($group->avg(fn ($row) => $row['submission']->score)); @endphp
            <section class="panel">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">Mata pelajaran</p>
                        <h2 class="font-display text-lg font-semibold text-brand-950">{{ $subject }}</h2>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-ink-soft">{{ $group->count() }} tugas dinilai</span>
                        <span class="badge {{ $average >= 80 ? 'badge-green' : ($average >= 70 ? 'badge-amber' : 'badge-red') }}">Rata-rata {{ $average }}</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-site">
                        <thead>
                            <tr>
                                <th>Tugas</th>
                                <th>Pengajar</th>
                                <th>Dinilai</th>
                                <th class="w-24">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($group as $row)
                                @php
                                    $score = $row['submission']->score;
                                    $submission = $row['submission'];
                                @endphp
                                <tr>
                                    <td class="max-w-xs">
                                        <p class="truncate font-medium text-brand-950">{{ $row['assignment']->title }}</p>
                                        @if ($submission->feedback)
                                            <p class="mt-0.5 line-clamp-1 text-xs text-ink-soft">{{ $submission->feedback }}</p>
                                        @endif
                                    </td>
                                    <td class="text-ink-soft">{{ $row['assignment']->teacher?->user?->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap text-sm text-ink-soft">{{ $submission->graded_at?->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <span class="font-display text-2xl font-semibold {{ $score >= 80 ? 'text-emerald-600' : ($score >= 70 ? 'text-amber-600' : 'text-rose-600') }}">
                                            {{ $score }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach
    </div>
</x-layouts.portal>