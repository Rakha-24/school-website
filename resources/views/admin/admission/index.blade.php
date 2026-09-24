<x-layouts.portal :title="'Info PPDB'" :role="'admin'">
    <x-page-header title="Info PPDB" description="Informasi penerimaan peserta didik baru yang tampil di halaman publik."
        :actions="[['label' => 'Tambah konten', 'route' => 'portal.admin.admission.create', 'icon' => 'plus']]" />

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
                        <th>Judul</th>
                        <th>Tipe</th>
                        <th>Urutan</th>
                        <th>Status</th>
                        <th class="w-32"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td class="max-w-sm">
                                <p class="truncate font-medium text-brand-950">{{ $item->title }}</p>
                                <p class="truncate text-xs text-ink-soft">{{ $item->body }}</p>
                            </td>
                            <td><span class="badge badge-brand">{{ $types[$item->type] ?? $item->type }}</span></td>
                            <td class="text-sm text-ink-soft">{{ $item->sort_order }}</td>
                            <td>
                                @if ($item->status === 'published')
                                    <span class="badge badge-green">Tampil</span>
                                @else
                                    <span class="badge badge-slate">Disembunyikan</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('portal.admin.admission.edit', $item) }}" class="btn btn-ghost btn-xs">
                                        <x-icon name="pencil" class="size-4" /> Edit
                                    </a>
                                    <form action="{{ route('portal.admin.admission.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus konten PPDB ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state title="Belum ada konten PPDB" description="Tambahkan informasi syarat, tahapan, atau jadwal pendaftaran." icon="flag" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            {{ $items->links() }}
        </div>
    </section>
</x-layouts.portal>