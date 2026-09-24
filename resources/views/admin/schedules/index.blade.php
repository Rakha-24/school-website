<x-layouts.portal :title="'Jadwal Pelajaran'" :role="'admin'">
    <x-page-header title="Jadwal pelajaran" description="Jadwal mengajar per hari di seluruh rombongan belajar."
        :actions="[['label' => 'Tambah jadwal', 'route' => 'portal.admin.schedules.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div role="status" class="mb-6 flex items-start gap-2.5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="mt-0.5 size-4 shrink-0" />
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-6 flex items-center gap-2">
        @if ($total > 0)
            <span class="badge badge-brand">{{ $total }} sesi jadwal</span>
        @endif
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        @forelse ($week as $dayBlock)
            <section class="panel">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">Hari</p>
                        <h2 class="font-display text-lg font-semibold text-brand-950">{{ $dayBlock['label'] }}</h2>
                    </div>
                    <span class="badge badge-soft">{{ $dayBlock['items']->count() }} sesi</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-site">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Kelas</th>
                                <th>Mapel / Pengajar</th>
                                <th>Ruang</th>
                                <th class="w-24"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dayBlock['items'] as $schedule)
                                <tr>
                                    <td class="whitespace-nowrap font-medium text-ink-900">
                                        {{ \Illuminate\Support\Str::substr($schedule->start_time, 0, 5) }}–{{ \Illuminate\Support\Str::substr($schedule->end_time, 0, 5) }}
                                    </td>
                                    <td class="text-ink-700">{{ $schedule->schoolClass?->name ?? '—' }}</td>
                                    <td>
                                        <p class="font-medium text-ink-900">{{ $schedule->subject?->name ?? '—' }}</p>
                                        <p class="text-xs text-ink-soft">{{ $schedule->teacher?->user?->name ?? '—' }}</p>
                                    </td>
                                    <td class="text-ink-700">{{ $schedule->room ?? '—' }}</td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ route('portal.admin.schedules.edit', $schedule) }}" class="btn btn-ghost btn-xs !p-1.5" aria-label="Edit jadwal">
                                                <x-icon name="pencil" class="size-4" />
                                            </a>
                                            <form action="{{ route('portal.admin.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-ghost btn-xs !p-1.5 !text-red-600 hover:!bg-red-50" aria-label="Hapus jadwal">
                                                    <x-icon name="trash" class="size-4" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="!py-10">
                                        <div class="flex flex-col items-center gap-2 py-6 text-center">
                                            <x-icon name="calendar" class="size-6 text-ink-300" />
                                            <p class="text-sm text-ink-soft">Tidak ada jadwal di hari ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @empty
            <section class="panel xl:col-span-2">
                <x-empty-state title="Belum ada jadwal" description="Buat jadwal pertama lewat tombol Tambah jadwal." icon="calendar" />
            </section>
        @endforelse
    </div>
</x-layouts.portal>