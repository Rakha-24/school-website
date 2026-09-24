<x-layouts.portal :title="'Manajemen Siswa'" :role="'admin'">
    <x-page-header title="Siswa" description="Data siswa per rombongan belajar beserta status keaktifannya."
        :actions="[['label' => 'Tambah siswa', 'route' => 'portal.admin.students.create', 'icon' => 'plus']]" />

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
                        <th>Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th class="w-32"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr>
                            <td>
                                <p class="font-medium text-brand-950">{{ $student->user->name }}</p>
                                <p class="text-xs text-ink-soft">{{ $student->user->email }}</p>
                            </td>
                            <td class="text-ink-700">{{ $student->student_number }}</td>
                            <td class="text-ink-700">{{ $student->schoolClass?->name ?? '—' }}</td>
                            <td>
                                @php
                                    $statusMap = ['active' => 'badge-green', 'inactive' => 'badge-slate', 'graduate' => 'badge-amber', 'dropped' => 'badge-red'];
                                @endphp
                                <span class="badge {{ $statusMap[$student->status] ?? 'badge-slate' }}">{{ $statuses[$student->status] ?? $student->status }}</span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('portal.admin.students.edit', $student) }}" class="btn btn-ghost btn-xs">
                                        <x-icon name="pencil" class="size-4" /> Edit
                                    </a>
                                    <form action="{{ route('portal.admin.students.destroy', $student) }}" method="POST" onsubmit="return confirm('Hapus siswa ini beserta akunnya?')">
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
                            <td colspan="5">
                                <x-empty-state title="Belum ada siswa" description="Tambah siswa pertama lewat tombol Tambah siswa." icon="users" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            {{ $students->links() }}
        </div>
    </section>
</x-layouts.portal>