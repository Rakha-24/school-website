<x-layouts.portal :title="'Galeri'" :role="'admin'">
    <x-page-header title="Galeri" description="Dokumentasi foto kegiatan yang tampil di halaman galeri publik."
        :actions="[['label' => 'Tambah foto', 'route' => 'portal.admin.gallery.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
        @forelse ($items as $item)
            <figure class="panel overflow-hidden">
                <div class="aspect-[4/3] overflow-hidden bg-paper">
                    <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}" class="h-full w-full object-cover" loading="lazy">
                </div>
                <figcaption class="p-4">
                    <p class="truncate text-sm font-semibold text-brand-950">{{ $item->title }}</p>
                    <div class="mt-1.5 flex items-center justify-between gap-2">
                        <span class="badge badge-brand">{{ $item->category }}</span>
                        @if ($item->isPublished())
                            <span class="badge badge-green">Terbit</span>
                        @else
                            <span class="badge badge-slate">Draf</span>
                        @endif
                    </div>
                    <div class="mt-3 flex items-center gap-2 border-t border-line pt-3">
                        <a href="{{ route('portal.admin.gallery.edit', $item) }}" class="btn btn-ghost btn-xs flex-1">
                            <x-icon name="pencil" class="size-4" /> Edit
                        </a>
                        <form action="{{ route('portal.admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus foto galeri ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                        </form>
                    </div>
                </figcaption>
            </figure>
        @empty
            <div class="col-span-full">
                <x-empty-state title="Galeri masih kosong" description="Unggah foto pertama dari kegiatan sekolah." icon="eye" />
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $items->links() }}</div>
</x-layouts.portal>