<x-layouts.portal :title="'Prestasi'" :role="'admin'">
    <x-page-header title="Prestasi" description="Catatan prestasi siswa dan sekolah yang ditampilkan di halaman publik."
        :actions="[['label' => 'Tambah prestasi', 'route' => 'portal.admin.achievements.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <section class="panel">
        <div class="overflow-x-auto">
            <table class="table-site">
                <thead>
                    <tr>
                        <th>Prestasi</th>
                        <th>Tingkat</th>
                        <th>Tahun</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th class="w-32"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($achievements as $achievement)
                        <tr>
                            <td class="max-w-xs">
                                <p class="truncate font-medium text-brand-950">{{ $achievement->title }}</p>
                                <p class="truncate text-xs text-ink-soft">{{ $achievement->participant }}</p>
                            </td>
                            <td><span class="badge badge-brand">{{ $levels[$achievement->level] ?? $achievement->level }}</span></td>
                            <td class="text-sm text-ink-900">{{ $achievement->year }}</td>
                            <td class="text-sm text-ink-soft">{{ $achievement->category }}</td>
                            <td>
                                @if ($achievement->isPublished())
                                    <span class="badge badge-green">Terbit</span>
                                @else
                                    <span class="badge badge-slate">Draf</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('portal.admin.achievements.edit', $achievement) }}" class="btn btn-ghost btn-xs">
                                        <x-icon name="pencil" class="size-4" /> Edit
                                    </a>
                                    <form action="{{ route('portal.admin.achievements.destroy', $achievement) }}" method="POST" onsubmit="return confirm('Hapus prestasi ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state title="Belum ada prestasi" description="Tambahkan prestasi pertama sekolah Anda." icon="trophy" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            {{ $achievements->links() }}
        </div>
    </section>
</x-layouts.portal>