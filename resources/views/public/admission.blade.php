@php
    $typeIcons = [
        'info' => 'academic-cap',
        'requirement' => 'clipboard',
        'stage' => 'flag',
        'schedule' => 'calendar',
        'document' => 'document',
    ];
    $ppdbYear = App\Models\Setting::get('ppdb_year', now()->year.'/'.(now()->year + 1));
@endphp

<x-layouts.public title="Info PPDB">
    <x-page-hero
        eyebrow="Penerimaan Peserta Didik Baru"
        title="Info PPDB {{ $ppdbYear }}"
        lede="Semua yang perlu kamu ketahui untuk mendaftar sebagai peserta didik baru di SMK Taruna Sains Kediri: persyaratan, tahapan, jadwal, dan dokumen."
        image="seeds/classroom.jpg"
    >
        <div class="flex flex-wrap items-center gap-3">
            @if ($groups->has('requirement'))
                <a href="#persyaratan" class="btn btn-accent">Lihat Persyaratan <x-icon name="chevron-down" class="size-4" /></a>
            @endif
            <a href="{{ route('contact.show') }}" class="btn border border-brand-700 bg-white/5 text-white backdrop-blur transition-colors hover:border-accent-400 hover:text-accent-300">
                Hubungi Panitia
            </a>
        </div>
    </x-page-hero>

    {{-- Informasi PPDB per kelompok --}}
    @if ($groups->isNotEmpty())
        @php $sectionIndex = 0; @endphp
        @foreach ($groups as $type => $rows)
            @php
                $sectionIndex++;
                $label = \App\Models\AdmissionInformation::TYPES[$type] ?? ucfirst($type);
                $anchor = $type === 'requirement' ? 'persyaratan' : null;
                $bg = $sectionIndex % 2 === 1 ? 'bg-paper' : 'bg-white';
            @endphp
            <section class="section {{ $bg }}" @if ($anchor) id="{{ $anchor }}" @endif>
                <div class="container-site grid gap-8 lg:grid-cols-[1fr_1.8fr]">
                    <div>
                        <x-section-heading
                            :eyebrow="'Bagian '.$sectionIndex"
                            :title="$label"
                        />
                        <span class="-mt-4 inline-flex size-11 items-center justify-center rounded-full bg-brand-900 text-accent-300">
                            <x-icon :name="$typeIcons[$type] ?? 'document'" class="size-5" />
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach ($rows as $i => $row)
                            <article class="panel p-6">
                                <div class="flex items-start gap-4">
                                    <span class="flex size-9 shrink-0 items-center justify-center rounded-md bg-brand-50 font-display text-sm font-bold text-brand-700 ring-1 ring-brand-100 ring-inset">
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div class="min-w-0">
                                        <h3 class="font-display text-lg font-semibold text-brand-950">{{ $row->title }}</h3>
                                        <div class="article-prose mt-2 text-[0.975rem] leading-7">
                                            {!! nl2br(e($row->body)) !!}
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    @else
        <section class="section bg-paper">
            <div class="container-site">
                <x-empty-state
                    icon="document"
                    title="Informasi PPDB sedang disiapkan"
                    description="Detail pendaftaran peserta didik baru akan segera dipublikasikan. Silakan hubungi sekolah untuk informasi awal."
                >
                    <a href="{{ route('contact.show') }}" class="btn btn-primary btn-sm mt-4">Hubungi Sekolah</a>
                </x-empty-state>
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    @if ($faqs->isNotEmpty())
        <section class="section bg-brand-950 text-white">
            <div class="container-site">
                <div class="mb-10 max-w-2xl">
                    <p class="eyebrow eyebrow-on-dark">Pertanyaan Umum</p>
                    <h2 class="mt-3 font-display text-2xl font-semibold tracking-tight text-white sm:text-3xl">
                        FAQ PPDB
                    </h2>
                    <p class="mt-3 text-base leading-relaxed text-brand-200">
                        Pertanyaan yang sering diajukan calon peserta didik dan orang tua.
                    </p>
                </div>

                <div class="mx-auto max-w-3xl space-y-3">
                    @foreach ($faqs as $faq)
                        <details class="group rounded-lg border border-brand-800 bg-brand-900/60 backdrop-blur">
                            <summary class="flex cursor-pointer items-center justify-between gap-4 px-5 py-4 text-sm font-semibold text-white sm:text-base">
                                {{ $faq->question }}
                                <x-icon name="chevron-down" class="size-4 shrink-0 text-accent-400 transition-transform duration-200 group-open:rotate-180" />
                            </summary>
                            <div class="border-t border-brand-800 px-5 py-4 text-sm leading-relaxed text-brand-200">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </details>
                    @endforeach
                </div>

                <div class="mt-10 text-center">
                    <p class="text-sm text-brand-300">Masih ada pertanyaan lain?</p>
                    <a href="{{ route('contact.show') }}" class="btn btn-accent mt-4">Hubungi Panitia PPDB <x-icon name="arrow-right" class="size-4" /></a>
                </div>
            </div>
        </section>
    @endif
</x-layouts.public>
