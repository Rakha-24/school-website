<x-layouts.public title="Akademik">
    <x-page-hero
        eyebrow="Akademik"
        title="Program keahlian & kurikulum"
        lede="Empat program keahlian yang dikemas dalam kurikulum berbasis praktik, didukung mitra industri dan laboratorium modern."
        image="seeds/campus.jpg"
    />

    {{-- Program keahlian --}}
    <section class="section bg-paper">
        <div class="container-site">
            <x-section-heading
                eyebrow="Program Keahlian"
                title="Empat konsentrasi kompetensi"
                lede="Setiap program keahlian mencakup mapel umum, kejuruan, dan muatan lokal dengan fokus praktikum."
            />
            <div class="grid gap-5 md:grid-cols-2">
                @foreach ($subjects as $subject)
                    <div class="panel p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex size-11 items-center justify-center rounded-full bg-brand-900 text-accent-300">
                                <x-icon :name="$subject->icon ?? 'book'" class="size-5" />
                            </div>
                            <span class="badge badge-soft">{{ $subject->code }}</span>
                        </div>
                        <h3 class="mt-4 font-display text-lg font-semibold text-brand-950">{{ $subject->name }}</h3>
                        <p class="mt-1.5 text-sm text-ink-soft">{{ $subject->description }}</p>
                        <p class="mt-4 text-xs text-ink-500">{{ $subject->class_subjects_count }} rombel menawarkan mapel ini</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Matriks pengampu --}}
    <section class="section bg-white">
        <div class="container-site">
            <x-section-heading
                eyebrow="Rombongan Belajar"
                title="Kelas, wali kelas & pengampu"
                lede="Struktur setiap rombel berikut wali kelas dan guru mata pelajaran."
            />

            <div class="space-y-4">
                @foreach ($classes as $class)
                    @php $rows = $matrix->get($class->name, collect())->filter(fn ($cs) => $cs->schoolClass && $cs->schoolClass->name === $class->name && $cs->subject); @endphp
                    <div class="panel overflow-hidden">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line bg-paper px-5 py-3.5">
                            <div>
                                <h3 class="font-display text-lg font-semibold text-brand-950">{{ $class->name }}</h3>
                                <p class="text-sm text-ink-soft">
                                    Wali kelas: <span class="font-medium text-ink-900">{{ $class->homeroomTeacher?->user?->name ?? '—' }}</span>
                                </p>
                            </div>
                            <span class="badge badge-brand">{{ $rows->count() }} mapel</span>
                        </div>
                        <div class="grid gap-x-8 gap-y-1 px-5 py-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($rows as $cs)
                                <div class="flex items-center justify-between gap-3 border-b border-line/60 py-2 text-sm last:border-0">
                                    <span class="shrink-0 text-xs font-semibold uppercase tracking-wide text-ink-500">{{ $cs->subject->code }}</span>
                                    <span class="min-w-0 flex-1 text-right font-medium text-ink-900">{{ $cs->subject->name }}</span>
                                    <span class="shrink-0 text-xs text-ink-soft max-w-28 truncate">{{ $cs->teacher?->user?->name ?? '—' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>