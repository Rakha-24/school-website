<x-layouts.portal :title="'Berita'" :role="'admin'">
    <x-page-header title="Berita" description="Kelola berita, kegiatan, dan pengumuman untuk situs publik sekolah."
        :actions="[['label' => 'Tulis berita', 'route' => 'portal.admin.news.create', 'icon' => 'plus']]" />

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
                        <th>Sampul</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Penulis</th>
                        <th>Diterbitkan</th>
                        <th class="w-32"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($news as $item)
                        <tr>
                            <td>
                                <div class="flex size-14 overflow-hidden rounded-md border border-line bg-paper">
                                    @if ($item->cover_image)
                                        <img src="{{ asset('storage/'.$item->cover_image) }}" alt="" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex size-full items-center justify-center text-ink-200">
                                            <x-icon name="document" class="size-5" />
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="max-w-xs">
                                <p class="truncate font-medium text-brand-950">{{ $item->title }}</p>
                                <p class="truncate text-xs text-ink-soft">{{ $item->excerpt }}</p>
                            </td>
                            <td><span class="badge badge-brand">{{ $item->category }}</span></td>
                            <td>
                                @if ($item->isPublished())
                                    <span class="badge badge-green">Terbit</span>
                                @else
                                    <span class="badge badge-slate">Draf</span>
                                @endif
                            </td>
                            <td class="text-sm text-ink-soft">{{ $item->author?->name }}</td>
                            <td class="text-sm text-ink-soft">{{ $item->published_at?->translatedFormat('d M Y') ?? '—' }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('portal.admin.news.edit', $item) }}" class="btn btn-ghost btn-xs">
                                        <x-icon name="pencil" class="size-4" /> Edit
                                    </a>
                                    <form action="{{ route('portal.admin.news.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus berita ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state title="Belum ada berita" description="Tulis berita pertama sekolah Anda." icon="document" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            {{ $news->links() }}
        </div>
    </section>
</x-layouts.portal>