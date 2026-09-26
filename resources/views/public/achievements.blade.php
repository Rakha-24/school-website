@php
    $levelLabels = [
        'city' => 'Kota',
        'district' => 'Kecamatan',
        'province' => 'Provinsi',
        'national' => 'Nasional',
        'international' => 'Internasional',
    ];
@endphp

<x-layouts.public title="Prestasi">
    <x-page-hero
        eyebrow="Prestasi"
        title="Kebanggaan yang kami raih bersama"
        lede="Rekam jejak peserta didik SMK Taruna Sains Kediri di ajang akademik, olahraga, seni, dan organisasi hingga tingkat internasional."
        image="seeds/robotics.jpg"
    />

    <section class="section bg-paper">
        <div class="container-site">
            {{-- Filter tahun --}}
            <div class="mb-10 flex flex-wrap items-center gap-2" role="navigation" aria-label="Filter tahun">
                <span class="mr-1 inline-flex items-center gap-1.5 text-xs font-semibold tracking-wide text-ink-faint uppercase">
                    <x-icon name="calendar" class="size-3.5" /> Tahun
                </span>
                <a href="{{ route('achievements.index') }}"
                   class="rounded-full border px-4 py-1.5 text-sm font-medium transition-colors {{ $activeYear ? 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' : 'border-brand-950 bg-brand-950 text-white' }}">
                    Semua
                </a>
                @foreach ($years as $year)
                    <a href="{{ route('achievements.index', ['year' => $year]) }}"
                       class="rounded-full border px-4 py-1.5 text-sm font-medium transition-colors {{ (string) $activeYear === (string) $year ? 'border-brand-950 bg-brand-950 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' }}">
                        {{ $year }}
                    </a>
                @endforeach
            </div>

            {{-- Kartu prestasi --}}
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($achievements as $achievement)
                    <article class="group flex flex-col overflow-hidden rounded-lg border border-line bg-white shadow-card transition-shadow hover:shadow-card-lg">
                        @if ($achievement->image)
                            <div class="relative aspect-[16/10] overflow-hidden">
                                <x-image :src="$achievement->image" :alt="$achievement->title" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" />
                                <span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full border border-accent-200 bg-accent-50 px-2.5 py-0.5 text-xs font-semibold text-accent-800">
                                    <x-icon name="trophy" class="size-3.5" />
                                    {{ $levelLabels[$achievement->level] ?? $achievement->level }}
                                </span>
                            </div>
                        @else
                            <div class="flex items-center justify-between border-b border-line bg-paper px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent-700 uppercase tracking-wide">
                                    <x-icon name="trophy" class="size-3.5" />
                                    {{ $levelLabels[$achievement->level] ?? $achievement->level }}
                                </span>
                                <span class="badge badge-brand">{{ $achievement->year }}</span>
                            </div>
                        @endif

                        <div class="flex flex-1 flex-col p-5">
                            <p class="flex flex-wrap items-center gap-2 text-xs text-ink-faint">
                                <span class="badge badge-brand">{{ $achievement->category }}</span>
                                <span class="inline-flex items-center gap-1">
                                    <x-icon name="calendar" class="size-3.5" />
                                    {{ $achievement->year }}
                                </span>
                            </p>
                            <h2 class="mt-3 font-display text-lg font-semibold leading-snug text-brand-950">{{ $achievement->title }}</h2>
                            @if ($achievement->participant)
                                <p class="mt-1.5 flex items-center gap-1.5 text-sm text-brand-800">
                                    <x-icon name="user" class="size-3.5" />
                                    {{ $achievement->participant }}
                                </p>
                            @endif
                            @if ($achievement->description)
                                <p class="mt-3 text-sm leading-relaxed text-ink-soft">{{ Str::limit($achievement->description, 120) }}</p>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3">
                        <x-empty-state
                            icon="trophy"
                            title="Belum ada prestasi"
                            description="Belum ada prestasi tercatat untuk filter yang dipilih. Coba pilih tahun lainnya."
                        >
                            @if ($activeYear)
                                <a href="{{ route('achievements.index') }}" class="btn btn-outline btn-sm mt-4">Tampilkan semua tahun</a>
                            @endif
                        </x-empty-state>
                    </div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $achievements->links() }}
            </div>
        </div>
    </section>
</x-layouts.public>
