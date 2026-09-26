<x-layouts.public title="Ekstrakurikuler">
    <x-page-hero
        eyebrow="Ekstrakurikuler"
        title="Terus belajar di luar kelas"
        lede="Organisasi dan kegiatan pembinaan minat, bakat, karakter, serta keterampilan hidup peserta didik di berbagai bidang."
        image="seeds/futsal.jpg"
    />

    <section class="section bg-paper">
        <div class="container-site">
            <x-section-heading
                eyebrow="Daftar Kegiatan"
                title="Pilih kegiatanmu"
                lede="Semua kegiatan pembinaan aktif diikuti peserta didik di luar jam pelajaran."
                align="center"
            />

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($extracurriculars as $item)
                    <a href="{{ route('extracurriculars.show', $item) }}" class="group flex flex-col overflow-hidden rounded-lg border border-line bg-white shadow-card transition-shadow hover:shadow-card-lg">
                        <div class="relative aspect-[16/10] overflow-hidden">
                            @if ($item->photo)
                                <x-image :src="$item->photo" :alt="$item->name" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" />
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-brand-50 text-brand-300">
                                    <x-icon name="sparkles" class="size-8" />
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 rounded-md bg-brand-950/85 px-2.5 py-1 text-xs font-semibold tracking-wide text-accent-300 uppercase">
                                Aktif
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h2 class="font-display text-lg font-semibold text-brand-950 group-hover:text-brand-700">{{ $item->name }}</h2>
                            @if ($item->schedule)
                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-ink-faint">
                                    <x-icon name="clock" class="size-3.5" />
                                    {{ $item->schedule }}
                                </p>
                            @endif
                            <p class="mt-3 text-sm leading-relaxed text-ink-soft">{{ Str::limit($item->description, 110) }}</p>
                            <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-800 group-hover:text-accent-700">
                                Lihat detail <x-icon name="arrow-right" class="size-3.5" />
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3">
                        <x-empty-state
                            icon="sparkles"
                            title="Belum ada ekstrakurikuler"
                            description="Daftar kegiatan ekstrakurikuler sedang diperbarui. Silakan kembali lagi nanti."
                        />
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="border-t border-line bg-white">
        <div class="container-site py-14 text-center">
            <h2 class="font-display text-2xl font-semibold tracking-tight text-brand-950 sm:text-3xl">
                Tertarik bergabung?
            </h2>
            <p class="mx-auto mt-3 max-w-xl text-base leading-relaxed text-ink-soft">
                Hubungi pembina kegiatan atau wali kelas kamu untuk informasi jadwal latihan dan pendaftaran anggota baru.
            </p>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('contact.show') }}" class="btn btn-primary">Hubungi Sekolah <x-icon name="arrow-right" class="size-4" /></a>
                <a href="{{ route('news.index') }}" class="btn btn-outline">Info Kegiatan Terbaru</a>
            </div>
        </div>
    </section>
</x-layouts.public>
