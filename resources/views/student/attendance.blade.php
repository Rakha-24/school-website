<x-layouts.portal :title="'Presensi'" :role="'student'">
    @php
        $badgeMap = [
            'present' => 'badge-green',
            'sick' => 'badge-amber',
            'permission' => 'badge-brand',
            'absent' => 'badge-red',
        ];
    @endphp

    <x-page-header title="Presensi" description="Riwayat kehadiran Anda di kelas. Gunakan filter untuk memilih bulan dan tahun." />

    {{-- Filter bulan & tahun --}}
    <form method="GET" action="{{ route('portal.student.attendance.index') }}" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="label" for="month">Bulan</label>
            <select name="month" id="month" class="field">
                @foreach ($months as $m => $label)
                    <option value="{{ $m }}" @selected((int) $month === (int) $m)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label" for="year">Tahun</label>
            <select name="year" id="year" class="field">
                @foreach ($years as $y)
                    <option value="{{ $y }}" @selected((int) $year === (int) $y)>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-outline btn-sm">
            <x-icon name="filter" class="size-4" /> Tampilkan
        </button>
    </form>

    {{-- Rekap tahun berjalan --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($statuses as $statusKey => $statusLabel)
            <div class="panel p-5">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-ink-soft">{{ $statusLabel }}</p>
                    <span class="status-dot {{ $statusKey === 'present' ? 'bg-emerald-500' : ($statusKey === 'sick' ? 'bg-amber-500' : ($statusKey === 'permission' ? 'bg-brand-500' : 'bg-rose-500')) }}"></span>
                </div>
                <p class="mt-2 font-display text-3xl font-semibold tracking-tight text-brand-950">{{ $summary->get($statusKey, 0) }}</p>
                <p class="mt-0.5 text-xs text-ink-faint">kali · tahun berjalan</p>
            </div>
        @endforeach
    </section>

    {{-- Rincian bulanan --}}
    <section class="panel mt-6">
        <div class="panel-head">
            <div>
                <p class="eyebrow">Rincian</p>
                <h2 class="font-display text-lg font-semibold text-brand-950">{{ $months[$month] }} {{ $year }}</h2>
            </div>
            <span class="badge badge-brand">{{ $records->count() }} catatan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="table-site">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <td class="whitespace-nowrap font-medium text-brand-950">{{ $record->date->translatedFormat('l, d F Y') }}</td>
                            <td>
                                <span class="badge {{ $badgeMap[$record->status] ?? 'badge-slate' }}">{{ $record->statusLabel() }}</span>
                            </td>
                            <td class="text-ink-soft">{{ $record->note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <x-empty-state icon="check-circle" title="Tidak ada catatan presensi"
                                    :description="'Belum ada catatan kehadiran untuk bulan '.$months[$month].' '.$year.'.'" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.portal>