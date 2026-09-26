<x-layouts.public title="Galeri">
    <x-page-hero
        eyebrow="Galeri"
        title="Momen dari lingkungan sekolah"
        lede="Dokumentasi kegiatan pembelajaran, praktik, dan kehidupan sehari-hari peserta didik SMK Taruna Sains Kediri."
        image="seeds/classroom.jpg"
    />

    <section class="section bg-paper">
        <div class="container-site">
            {{-- Filter kategori --}}
            <div class="mb-8 flex flex-wrap items-center gap-2" role="navigation" aria-label="Filter kategori">
                <span class="mr-1 inline-flex items-center gap-1.5 text-xs font-semibold tracking-wide text-ink-faint uppercase">
                    <x-icon name="filter" class="size-3.5" /> Kategori
                </span>
                <a href="{{ route('gallery.index') }}"
                   class="rounded-full border px-4 py-1.5 text-sm font-medium transition-colors {{ $activeCategory ? 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' : 'border-brand-950 bg-brand-950 text-white' }}">
                    Semua
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('gallery.index', ['category' => $category]) }}"
                       class="rounded-full border px-4 py-1.5 text-sm font-medium transition-colors {{ $activeCategory === $category ? 'border-brand-950 bg-brand-950 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>

            {{-- Grid foto --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @forelse ($items as $item)
                    <figure class="group relative aspect-square overflow-hidden rounded-lg border border-line bg-white shadow-card">
                        <x-image :src="$item->image" :alt="$item->title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.05]" />
                        <figcaption class="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-brand-950/95 via-brand-950/75 to-transparent px-4 pt-10 pb-4 transition-transform duration-300 group-hover:translate-y-0">
                            <p class="text-xs font-semibold tracking-wide text-accent-300 uppercase">{{ $item->category }}</p>
                            <p class="mt-0.5 text-sm font-semibold text-white">{{ $item->title }}</p>
                        </figcaption>
                    </figure>
                @empty
                    <div class="col-span-2 sm:col-span-3 lg:col-span-4">
                        <x-empty-state
                            icon="eye"
                            title="Belum ada foto"
                            description="Belum ada dokumentasi foto untuk kategori ini. Silakan pilih kategori lainnya."
                        >
                            @if ($activeCategory)
                                <a href="{{ route('gallery.index') }}" class="btn btn-outline btn-sm mt-4">Tampilkan semua foto</a>
                            @endif
                        </x-empty-state>
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $items->links() }}
            </div>
        </div>
    </section>
</x-layouts.public>
