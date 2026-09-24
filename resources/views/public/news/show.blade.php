<x-layouts.public :title="$news->title">

    {{-- Hero artikel --}}
    <section class="relative overflow-hidden bg-brand-950 text-white">
        @if ($news->cover_image)
            <img src="{{ asset('storage/'.$news->cover_image) }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-30" loading="eager">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-950 via-brand-950/85 to-brand-950/55"></div>
        @else
            <div class="absolute inset-0 bg-[radial-gradient(60rem_30rem_at_85%_-10%,rgba(185,106,23,0.28),transparent)]"></div>
        @endif

        <div class="container-site relative py-16 sm:py-20">
            <div class="max-w-3xl">
                <p class="eyebrow eyebrow-on-dark">Berita & Kegiatan</p>
                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-brand-200">
                    <span class="badge border-brand-700 bg-brand-900/70 text-accent-300">{{ $news->category }}</span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-icon name="calendar" class="size-4 text-accent-400" />
                        {{ $news->published_at?->translatedFormat('d F Y') }}
                    </span>
                    <span class="text-brand-700">·</span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-icon name="user" class="size-4 text-accent-400" />
                        {{ $news->author?->name ?? 'Redaksi Sekolah' }}
                    </span>
                </div>
                <h1 class="mt-5 font-display text-3xl font-semibold leading-[1.12] tracking-tight text-white sm:text-4xl lg:text-[2.6rem]">
                    {{ $news->title }}
                </h1>
                @if ($news->excerpt)
                    <p class="mt-4 max-w-2xl text-base leading-relaxed text-brand-200 sm:text-lg">{{ $news->excerpt }}</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Isi artikel + sidebar --}}
    <section class="section bg-white">
        <div class="container-site grid gap-10 lg:grid-cols-[1.6fr_1fr]">
            <article class="prose-measure">
                <div class="article-prose">
                    {!! $news->content !!}
                </div>

                <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-line pt-6">
                    <span class="text-xs font-semibold tracking-wide text-ink-faint uppercase">Kategori</span>
                    <span class="badge badge-brand">{{ $news->category }}</span>
                    <a href="{{ route('news.index') }}" class="ml-auto inline-flex items-center gap-1.5 text-sm font-semibold text-brand-800 transition-colors hover:text-accent-700">
                        <x-icon name="chevron-left" class="size-3.5" /> Kembali ke berita
                    </a>
                </div>
            </article>

            <aside class="space-y-6">
                <div class="panel panel-pad">
                    <p class="eyebrow">Penulis</p>
                    <div class="mt-4 flex items-center gap-3.5">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-brand-900 font-display text-lg font-semibold text-accent-300">
                            {{ strtoupper(mb_substr($news->author?->name ?? 'R', 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate font-semibold text-brand-950">{{ $news->author?->name ?? 'Redaksi Sekolah' }}</p>
                            <p class="text-xs text-ink-soft">{{ $news->published_at?->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    <dl class="mt-5 space-y-2.5 border-t border-line pt-4 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-ink-soft">Kategori</dt>
                            <dd class="font-medium text-ink-900">{{ $news->category }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-ink-soft">Terbit</dt>
                            <dd class="font-medium text-ink-900">{{ $news->published_at?->translatedFormat('d F Y') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="panel panel-pad bg-paper">
                    <p class="eyebrow">Info PPDB</p>
                    <p class="mt-3 text-sm leading-relaxed text-ink-soft">
                        Pendaftaran peserta didik baru tahun ajaran {{ now()->year + 1 }}/{{ now()->year + 2 }} telah dibuka.
                    </p>
                    <a href="{{ route('admission.show') }}" class="btn btn-primary btn-sm mt-4 w-full justify-center">Lihat Info PPDB</a>
                </div>
            </aside>
        </div>
    </section>

    {{-- Berita terkait --}}
    <section class="section bg-paper">
        <div class="container-site">
            <x-section-heading
                eyebrow="Berita Terkait"
                title="Baca juga"
                lede="Berita lainnya pada kategori {{ $news->category }}."
            />

            <div class="grid gap-6 md:grid-cols-3">
                @forelse ($related as $item)
                    <a href="{{ route('news.show', $item) }}" class="group overflow-hidden rounded-lg border border-line bg-white shadow-card transition-shadow hover:shadow-card-lg">
                        @if ($item->cover_image)
                            <div class="aspect-[16/9] overflow-hidden">
                                <img src="{{ asset('storage/'.$item->cover_image) }}" alt="{{ $item->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" loading="lazy">
                            </div>
                        @endif
                        <div class="p-5">
                            <p class="flex items-center gap-2 text-xs text-ink-faint">
                                <x-icon name="calendar" class="size-3.5" />
                                {{ $item->published_at?->translatedFormat('d F Y') }}
                            </p>
                            <h3 class="mt-2.5 font-display text-lg font-semibold leading-snug text-brand-950 group-hover:text-brand-700">{{ $item->title }}</h3>
                            <p class="mt-2 text-sm text-ink-soft">{{ Str::limit(strip_tags($item->excerpt ?? $item->content), 90) }}</p>
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-3">
                        <x-empty-state
                            icon="book-open"
                            title="Belum ada berita terkait"
                            description="Belum ada berita lain pada kategori ini."
                        />
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.public>
