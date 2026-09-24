<x-layouts.portal :title="'Ekstrakurikuler'" :role="'admin'">
    <x-page-header title="Ekstrakurikuler" description="Kegiatan ekstrakurikuler yang ditampilkan di halaman publik sekolah."
        :actions="[['label' => 'Tambah ekstrakurikuler', 'route' => 'portal.admin.extracurriculars.create', 'icon' => 'plus']]" />

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
                        <th>Nama</th>
                        <th>Pembina / Ketua</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th class="w-32"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($extracurriculars as $extracurricular)
                        <tr>
                            <td class="max-w-xs">
                                <div class="flex items-center gap-3">
                                    @if ($extracurricular->photo)
                                        <img src="{{ asset('storage/'.$extracurricular->photo) }}" alt="" class="size-10 shrink-0 rounded-md border border-line object-cover">
                                    @else
                                        <span class="flex size-10 shrink-0 items-center justify-center rounded-md bg-brand-50 text-brand-700">
                                            <x-icon name="sparkles" class="size-5" />
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate font-medium text-brand-950">{{ $extracurricular->name }}</p>
                                        <p class="truncate text-xs text-ink-soft">{{ $extracurricular->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm text-ink-soft">{{ $extracurricular->leader }}</td>
                            <td class="text-sm text-ink-soft">{{ $extracurricular->schedule }}</td>
                            <td>
                                @if ($extracurricular->status === 'active')
                                    <span class="badge badge-green">Aktif</span>
                                @else
                                    <span class="badge badge-slate">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('portal.admin.extracurriculars.edit', $extracurricular) }}" class="btn btn-ghost btn-xs">
                                        <x-icon name="pencil" class="size-4" /> Edit
                                    </a>
                                    <form action="{{ route('portal.admin.extracurriculars.destroy', $extracurricular) }}" method="POST" onsubmit="return confirm('Hapus ekstrakurikuler ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state title="Belum ada ekstrakurikuler" description="Tambahkan kegiatan ekstrakurikuler pertama." icon="sparkles" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            {{ $extracurriculars->links() }}
        </div>
    </section>
</x-layouts.portal>