<x-layouts.public :title="$extracurricular->name">

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-brand-950 text-white">
        @if ($extracurricular->photo)
            <x-image :src="$extracurricular->photo" alt="" class="absolute inset-0 h-full w-full object-cover opacity-30" loading="eager" />
            <div class="absolute inset-0 bg-gradient-to-r from-brand-950 via-brand-950/90 to-brand-950/50"></div>
        @else
            <div class="absolute inset-0 bg-[radial-gradient(60rem_30rem_at_85%_-10%,rgba(185,106,23,0.28),transparent)]"></div>
        @endif

        <div class="container-site relative py-16 sm:py-20">
            <div class="max-w-2xl">
                <p class="eyebrow eyebrow-on-dark">Ekstrakurikuler</p>
                <h1 class="mt-4 font-display text-3xl font-semibold leading-[1.12] tracking-tight text-white sm:text-4xl lg:text-[2.75rem]">
                    {{ $extracurricular->name }}
                </h1>
                @if ($extracurricular->schedule)
                    <p class="mt-4 inline-flex items-center gap-2 text-sm text-brand-200">
                        <x-icon name="clock" class="size-4 text-accent-400" />
                        {{ $extracurricular->schedule }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    {{-- Detail --}}
    <section class="section bg-paper">
        <div class="container-site grid gap-10 lg:grid-cols-[1.6fr_1fr]">
            <div class="prose-measure">
                <x-section-heading
                    eyebrow="Tentang Kegiatan"
                    title="Mengenal {{ $extracurricular->name }}"
                />
                <div class="article-prose">
                    {!! nl2br(e($extracurricular->description)) !!}
                </div>
            </div>

            <aside class="space-y-6">
                <div class="panel panel-pad">
                    <p class="eyebrow">Informasi Kegiatan</p>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex items-start justify-between gap-3 border-b border-line/70 pb-3">
                            <dt class="flex items-center gap-2 text-ink-soft"><x-icon name="calendar" class="size-4 text-accent-600" /> Jadwal</dt>
                            <dd class="text-right font-medium text-ink-900">{{ $extracurricular->schedule ?? '—' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3 border-b border-line/70 pb-3">
                            <dt class="flex items-center gap-2 text-ink-soft"><x-icon name="user" class="size-4 text-accent-600" /> Pembina</dt>
                            <dd class="text-right font-medium text-ink-900">{{ $extracurricular->leader ?? '—' }}</dd>
                        </div>
                        <div class="flex items-start justify-between gap-3">
                            <dt class="flex items-center gap-2 text-ink-soft"><x-icon name="check-circle" class="size-4 text-accent-600" /> Status</dt>
                            <dd><span class="badge badge-green">Aktif</span></dd>
                        </div>
                    </dl>
                </div>

                <div class="panel panel-pad bg-paper">
                    <p class="eyebrow">Ikut Bergabung</p>
                    <p class="mt-3 text-sm leading-relaxed text-ink-soft">
                        Tertarik mengikuti kegiatan ini? Sampaikan minatmu kepada wali kelas atau hubungi sekolah.
                    </p>
                    <a href="{{ route('contact.show') }}" class="btn btn-primary btn-sm mt-4 w-full justify-center">Hubungi Sekolah</a>
                </div>
            </aside>
        </div>
    </section>

    {{-- Kegiatan lainnya --}}
    <section class="section bg-white">
        <div class="container-site">
            <x-section-heading
                eyebrow="Kegiatan Lain"
                title="Ekstrakurikuler lainnya"
                lede="Jelajahi kegiatan pembinaan minat dan bakat lainnya di sekolah."
            />

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($others as $other)
                    <a href="{{ route('extracurriculars.show', $other) }}" class="group flex items-center gap-3.5 rounded-lg border border-line bg-paper px-4 py-3.5 transition-colors hover:border-brand-500">
                        <span class="flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-brand-900 text-accent-300">
                            @if ($other->photo)
                                <x-image :src="$other->photo" alt="" class="h-full w-full object-cover" />
                            @else
                                <x-icon name="sparkles" class="size-4" />
                            @endif
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-brand-950 group-hover:text-brand-700">{{ $other->name }}</p>
                            <p class="truncate text-xs text-ink-faint">{{ $other->schedule ?? 'Jadwal menyusul' }}</p>
                        </div>
                        <x-icon name="chevron-right" class="ml-auto size-4 shrink-0 text-ink-300 group-hover:text-brand-700" />
                    </a>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3">
                        <x-empty-state
                            icon="sparkles"
                            title="Belum ada kegiatan lain"
                            description="Belum ada ekstrakurikuler aktif lainnya saat ini."
                        />
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.public>
