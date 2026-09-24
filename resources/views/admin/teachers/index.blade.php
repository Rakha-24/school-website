<x-layouts.portal :title="'Manajemen Guru'" :role="'admin'">
    <x-page-header title="Guru" description="Data guru pengajar dan wali kelas beserta status keaktifannya."
        :actions="[['label' => 'Tambah guru', 'route' => 'portal.admin.teachers.create', 'icon' => 'plus']]" />

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
                        <th>Guru</th>
                        <th>NIP</th>
                        <th>Status</th>
                        <th class="w-32"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td>
                                <p class="font-medium text-brand-950">{{ $teacher->user->name }}</p>
                                <p class="text-xs text-ink-soft">{{ $teacher->user->email }}</p>
                            </td>
                            <td class="text-ink-700">{{ $teacher->teacher_number ?? '—' }}</td>
                            <td>
                                @if ($teacher->status === 'active')
                                    <span class="badge badge-green">Aktif</span>
                                @else
                                    <span class="badge badge-slate">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('portal.admin.teachers.edit', $teacher) }}" class="btn btn-ghost btn-xs">
                                        <x-icon name="pencil" class="size-4" /> Edit
                                    </a>
                                    <form action="{{ route('portal.admin.teachers.destroy', $teacher) }}" method="POST" onsubmit="return confirm('Hapus guru ini beserta akunnya?')">
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
                                <x-empty-state title="Belum ada guru" description="Tambah guru pertama lewat tombol Tambah guru." icon="user" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            {{ $teachers->links() }}
        </div>
    </section>
</x-layouts.portal>