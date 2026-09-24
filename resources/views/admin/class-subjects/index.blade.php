<x-layouts.portal :title="'Mapel & Pengajar'" :role="'admin'">
    <x-page-header title="Mapel & pengajar" description="Relasi mata pelajaran, kelas, dan guru pengajar per rombongan belajar."
        :actions="[['label' => 'Tambah relasi', 'route' => 'portal.admin.class-subjects.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div role="status" class="mb-6 flex items-start gap-2.5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="mt-0.5 size-4 shrink-0" />
            {{ session('status') }}
        </div>
    @endif

    @forelse ($rows as $className => $items)
        <section class="panel mb-6">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Rombel</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">{{ $className }}</h2>
                </div>
                <span class="badge badge-soft">{{ $items->count() }} mapel</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-site">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Mata pelajaran</th>
                            <th>Pengajar</th>
                            <th>JPM</th>
                            <th>Materi</th>
                            <th>Tugas</th>
                            <th class="w-32"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $cs)
                            <tr>
                                <td>
                                    @if ($cs->subject?->code)
                                        <span class="badge badge-brand">{{ $cs->subject->code }}</span>
                                    @else
                                        <span class="text-xs text-ink-faint">—</span>
                                    @endif
                                </td>
                                <td class="font-medium text-ink-900">{{ $cs->subject?->name ?? '—' }}</td>
                                <td class="text-ink-700">{{ $cs->teacher?->user?->name ?? '—' }}</td>
                                <td class="text-ink-700">{{ $cs->hours_per_week }} JP</td>
                                <td class="text-ink-700">{{ $cs->materials_count }}</td>
                                <td class="text-ink-700">{{ $cs->assignments_count }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('portal.admin.class-subjects.edit', $cs) }}" class="btn btn-ghost btn-xs">
                                            <x-icon name="pencil" class="size-4" /> Edit
                                        </a>
                                        <form action="{{ route('portal.admin.class-subjects.destroy', $cs) }}" method="POST" onsubmit="return confirm('Hapus relasi kelas–mapel ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50">
                                                <x-icon name="trash" class="size-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @empty
        <section class="panel">
            <x-empty-state title="Belum ada relasi kelas–mapel" description="Hubungkan kelas dengan mata pelajaran dan guru pengajar lewat tombol Tambah relasi." icon="academic-cap" />
        </section>
    @endforelse
</x-layouts.portal>