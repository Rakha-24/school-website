<x-layouts.portal :title="'Mata Pelajaran'" :role="'admin'">
    <x-page-header title="Mata pelajaran" description="Katalog mapel yang diajarkan — dihubungkan dengan kelas dan pengajar di halaman Mapel & Pengajar."
        :actions="[['label' => 'Tambah mapel', 'route' => 'portal.admin.subjects.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div role="status" class="mb-6 flex items-start gap-2.5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="mt-0.5 size-4 shrink-0" />
            {{ session('status') }}
        </div>
    @endif

    <section class="panel">
        <div class="overflow-x-auto">
            <table class="table-site">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Mata pelajaran</th>
                        <th>Dipakai di kelas</th>
                        <th class="w-32"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subjects as $subject)
                        <tr>
                            <td>
                                @if ($subject->code)
                                    <span class="badge badge-brand">{{ $subject->code }}</span>
                                @else
                                    <span class="text-xs text-ink-faint">—</span>
                                @endif
                            </td>
                            <td class="max-w-md">
                                <p class="font-medium text-brand-950">{{ $subject->name }}</p>
                                @if ($subject->description)
                                    <p class="truncate text-xs text-ink-soft">{{ $subject->description }}</p>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-soft">{{ $subject->class_subjects_count }} kelas</span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('portal.admin.subjects.edit', $subject) }}" class="btn btn-ghost btn-xs">
                                        <x-icon name="pencil" class="size-4" /> Edit
                                    </a>
                                    <form action="{{ route('portal.admin.subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50">
                                            <x-icon name="trash" class="size-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-empty-state title="Belum ada mata pelajaran" description="Buat katalog mapel lewat tombol Tambah mapel." icon="academic-cap" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            {{ $subjects->links() }}
        </div>
    </section>
</x-layouts.portal>