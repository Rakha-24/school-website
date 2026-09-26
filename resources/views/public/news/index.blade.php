<x-layouts.public title="Berita & Kegiatan">
    <x-page-hero
        eyebrow="Berita & Kegiatan"
        title="Kabar terbaru dari sekolah"
        lede="Informasi kegiatan, pengumuman resmi, dan cerita-cerita inspiratif dari lingkungan SMK Taruna Sains Kediri."
        image="seeds/campus.jpg"
    />

    <section class="section bg-paper">
        <div class="container-site">
            {{-- Pencarian & filter kategori --}}
            <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <form method="GET" action="{{ route('news.index') }}" class="flex w-full flex-wrap gap-2 lg:max-w-md">
                    @if ($activeCategory)
                        <input type="hidden" name="category" value="{{ $activeCategory }}">
                    @endif
                    <div class="relative min-w-0 flex-1">
                        <x-icon name="magnifying-glass" class="pointer-events-none absolute top-1/2 left-3.5 size-4 -translate-y-1/2 text-ink-300" />
                        <input type="search" name="q" value="{{ $query }}" placeholder="Cari judul atau isi berita..."
                               aria-label="Cari berita" class="field pl-10">
                    </div>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>

                <div class="flex flex-wrap gap-2" role="navigation" aria-label="Filter kategori">
                    <a href="{{ route('news.index', array_filter(['q' => $query])) }}"
                       class="rounded-full border px-4 py-1.5 text-sm font-medium transition-colors {{ $activeCategory ? 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' : 'border-brand-950 bg-brand-950 text-white' }}">
                        Semua
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('news.index', array_filter(['category' => $category, 'q' => $query])) }}"
                           class="rounded-full border px-4 py-1.5 text-sm font-medium transition-colors {{ $activeCategory === $category ? 'border-brand-950 bg-brand-950 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Daftar berita --}}
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($news as $item)
                    <a href="{{ route('news.show', $item) }}" class="group flex flex-col overflow-hidden rounded-lg border border-line bg-white shadow-card transition-shadow hover:shadow-card-lg">
                        @if ($item->cover_image)
                            <div class="aspect-[16/9] overflow-hidden">
                                <x-image :src="$item->cover_image" :alt="$item->title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" />
                            </div>
                        @endif
                        <div class="flex flex-1 flex-col p-5">
                            <p class="flex flex-wrap items-center gap-2 text-xs text-ink-faint">
                                <span class="badge badge-brand">{{ $item->category }}</span>
                                <x-icon name="calendar" class="size-3.5" />
                                {{ $item->published_at?->translatedFormat('d F Y') }}
                            </p>
                            <h2 class="mt-3 font-display text-lg font-semibold leading-snug text-brand-950 group-hover:text-brand-700">{{ $item->title }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-ink-soft">{{ Str::limit(strip_tags($item->excerpt ?? $item->content), 110) }}</p>
                            <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-800 group-hover:text-accent-700">
                                Baca selengkapnya <x-icon name="arrow-right" class="size-3.5" />
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-2 lg:col-span-3">
                        <x-empty-state
                            icon="magnifying-glass"
                            title="Berita tidak ditemukan"
                            description="Coba ubah kata kunci pencarian atau pilih kategori lainnya."
                        >
                            <a href="{{ route('news.index') }}" class="btn btn-outline btn-sm mt-4">Tampilkan semua berita</a>
                        </x-empty-state>
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $news->links() }}
            </div>
        </div>
    </section>
</x-layouts.public>
