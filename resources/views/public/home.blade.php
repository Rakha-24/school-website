<x-layouts.public title="Sekolah Menengah Kejuruan Unggulan">

    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-line bg-brand-950 text-white">
        <div class="absolute inset-0">
            <x-image :src="$heroImage" alt="" class="h-full w-full object-cover opacity-30" loading="eager" fetchpriority="high" />
            <div class="absolute inset-0 bg-gradient-to-r from-brand-950 via-brand-950/92 to-brand-950/55"></div>
            <div class="absolute inset-0 bg-[radial-gradient(42rem_26rem_at_90%_-10%,rgba(185,106,23,0.35),transparent)]"></div>
        </div>

        <div class="container-site relative py-20 sm:py-28">
            <div class="max-w-2xl">
                <p class="eyebrow eyebrow-on-dark">Sekolah Menengah Kejuruan Negeri</p>
                <h1 class="mt-4 font-display text-4xl font-semibold leading-[1.08] tracking-tight text-white sm:text-5xl lg:text-[3.4rem]">
                    Menghidupkan bakat, <span class="text-accent-400">menyiapkan kerja.</span>
                </h1>
                <p class="mt-5 max-w-xl text-base leading-relaxed text-brand-200 sm:text-lg">
                    SMK Taruna Sains Kediri membekali peserta didik dengan kompetensi kejuruan terkini
                    lewat praktik langsung, kemitraan industri, dan pembinaan karakter.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-3">
                    <a href="{{ route('academic.show') }}" class="btn btn-accent btn-lg">Jelajahi Program Keahlian <x-icon name="arrow-right" class="size-4" /></a>
                    <a href="{{ route('admission.show') }}" class="btn btn-lg border border-brand-700 bg-white/10 text-white transition-colors hover:border-accent-400 hover:text-accent-300">Info PPDB {{ now()->year + 1 }}</a>
                </div>
                <p class="mt-7 text-xs font-medium tracking-wide text-brand-400">
                    Tahun ajaran {{ now()->year }}/{{ now()->year + 1 }} · Pendaftaran dibuka bulan ini
                </p>
            </div>
        </div>

        <div class="relative border-t border-brand-800 bg-brand-900/85">
            <div class="container-site grid grid-cols-2 divide-x divide-brand-800 sm:grid-cols-4">
                <x-stat label="Program Keahlian" value="{{ $programCount }}" />
                <x-stat label="Guru Bersertifikasi" value="30+" />
                <x-stat label="Siswa Aktif" value="700+" />
                <x-stat label="Akreditasi" value="A" />
            </div>
        </div>
    </section>

    {{-- Tentang / sambutan --}}
    <section class="section bg-paper">
        <div class="container-site grid items-center gap-12 lg:grid-cols-2">
            <div class="relative">
                <div class="overflow-hidden rounded-lg shadow-card">
                    <x-image src="seeds/campus.jpg" alt="Gedung SMK Taruna Sains Kediri" class="aspect-[4/3] w-full object-cover" widths="760" sizes="(min-width: 1024px) 570px, 92vw" />
                </div>
                <div class="absolute -bottom-5 right-4 rounded-md border border-line bg-white px-5 py-3 shadow-card sm:-right-5">
                    <p class="font-display text-2xl font-semibold text-brand-950">{{ now()->year - 1994 }}</p>
                    <p class="text-xs font-medium uppercase tracking-wide text-ink-faint">Tahun mengabdi</p>
                </div>
            </div>

            <div>
                <x-section-heading
                    eyebrow="Tentang Kami"
                    title="Vokasi yang tumbuh bersama industri"
                    lede="Sejak berdirinya, kami berkomitmen mencetak lulusan yang siap kerja, siap berkarya, dan berkarakter kuat. Program keahlian keunggulan kami membuka jalan menuju industri, wirausaha, dan pendidikan lanjut."
                />
                <ul class="space-y-4">
                    <li class="flex items-start gap-3.5">
                        <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-md bg-brand-50 text-brand-700 ring-1 ring-inset ring-brand-100"><x-icon name="wrench" class="size-5" /></span>
                        <div>
                            <p class="font-semibold text-brand-950">Pembelajaran berbasis praktik</p>
                            <p class="mt-0.5 text-sm text-ink-soft">Bimbingan langsung di bengkel, ruang kerja komputer, dan laboratorium terkini.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5">
                        <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-md bg-brand-50 text-brand-700 ring-1 ring-inset ring-brand-100"><x-icon name="users" class="size-5" /></span>
                        <div>
                            <p class="font-semibold text-brand-950">Kemitraan industri & praktik kerja lapangan</p>
                            <p class="mt-0.5 text-sm text-ink-soft">Jaringan luas bersama perusahaan dan instansi untuk magang dan penyaluran kerja.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5">
                        <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-md bg-brand-50 text-brand-700 ring-1 ring-inset ring-brand-100"><x-icon name="shield-check" class="size-5" /></span>
                        <div>
                            <p class="font-semibold text-brand-950">Pembinaan karakter & keagamaan</p>
                            <p class="mt-0.5 text-sm text-ink-soft">Ekstrakurikuler dan program pembiasaan menumbuhkan disiplin dan akhlak mulia.</p>
                        </div>
                    </li>
                </ul>
                <a href="{{ route('about.show') }}" class="mt-8 inline-flex items-center gap-2 font-semibold text-brand-800 transition-colors hover:text-accent-700">
                    Lihat profil lengkap sekolah <x-icon name="arrow-right" class="size-4" />
                </a>
            </div>
        </div>
    </section>

    {{-- Program keahlian --}}
    <section class="section bg-white">
        <div class="container-site">
            <x-section-heading
                eyebrow="Akademik"
                title="Satu program keahlian unggulan"
                lede="Kurikulum yang disusun bersama dunia usaha dan dunia industri agar relevan dengan kebutuhan sektor alat berat."
                align="center"
            />
            <div class="mx-auto grid max-w-3xl gap-6 md:grid-cols-1">
                @foreach ([
                    ['code' => 'TAB', 'name' => 'Teknik Alat Berat', 'desc' => 'Pengoperasian, perawatan, dan perbaikan alat berat: mesin, hydraulic, chassis, hingga kelistrikan — bekerja sama dengan industri konstruksi, pertambangan, dan perkebunan.', 'img' => 'workshop.jpg'],
                ] as $p)
                    <article class="group overflow-hidden rounded-lg border border-line bg-paper shadow-card transition-shadow hover:shadow-card-lg">
                        <div class="relative aspect-[16/10] overflow-hidden">
                            <x-image :src="'seeds/'.$p['img']" :alt="$p['name']" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" />
                            <span class="absolute top-3 left-3 rounded-md bg-brand-950/85 px-2.5 py-1 font-display text-xs font-bold tracking-wide text-accent-300">{{ $p['code'] }}</span>
                        </div>
                        <div class="p-6">
                            <h3 class="font-display text-lg font-semibold text-brand-950">{{ $p['name'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-ink-soft">{{ $p['desc'] }}</p>
                            <a href="{{ route('academic.show') }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-800 transition-colors group-hover:text-accent-700">
                                Pelajari lebih lanjut <x-icon name="arrow-right" class="size-3.5" />
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Prestasi --}}
    @if ($featuredAchievements->isNotEmpty())
        <section class="relative overflow-hidden bg-brand-950 py-16 text-white sm:py-20">
            <div class="absolute inset-0 bg-[radial-gradient(46rem_26rem_at_15%_-10%,rgba(185,106,23,0.3),transparent)]"></div>
            <div class="container-site relative">
                <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                    <x-section-heading
                        eyebrow="Prestasi"
                        title="Kebanggaan yang kami raih bersama"
                        lede="Ajang lomba dan kompetisi menjadi ruang bagi siswa membuktikan kemampuannya di tingkat kabupaten, provinsi, dan nasional."
                        on-dark
                    />
                    <a href="{{ route('achievements.index') }}" class="btn border border-brand-700 bg-white/5 text-white hover:border-accent-400 hover:text-accent-300">Semua prestasi</a>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($featuredAchievements as $a)
                        <article class="rounded-lg border border-brand-800 bg-brand-900/80 p-6 transition-colors hover:border-accent-500/50">
                            <p class="flex items-center gap-2 text-xs">
                                <x-icon name="trophy" class="size-4 text-accent-400" />
                                <span class="font-semibold uppercase tracking-[0.12em] text-accent-300">{{ $a->level }}</span>
                            </p>
                            <h3 class="mt-3 font-display text-lg font-semibold text-white">{{ $a->title }}</h3>
                            <p class="mt-1 text-sm text-brand-300">{{ $a->participant }} · {{ $a->year }}</p>
                            <p class="mt-3 text-sm leading-relaxed text-brand-200">{{ Str::limit($a->description, 110) }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Berita --}}
    @if ($latestNews->isNotEmpty())
        <section class="section bg-paper">
            <div class="container-site">
                <div class="mb-10 flex flex-wrap items-end justify-between gap-4">
                    <x-section-heading eyebrow="Berita & Kegiatan" title="Kabar terbaru dari sekolah" />
                    <a href="{{ route('news.index') }}" class="inline-flex items-center gap-2 font-semibold text-brand-800 hover:text-accent-700">Semua berita <x-icon name="arrow-right" class="size-4" /></a>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($latestNews as $n)
                        <a href="{{ route('news.show', $n) }}" class="group overflow-hidden rounded-lg border border-line bg-white shadow-card transition-shadow hover:shadow-card-lg">
                            @if ($n->cover_image)
                                <div class="aspect-[16/9] overflow-hidden">
                                    <x-image :src="$n->cover_image" alt="" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" />
                                </div>
                            @endif
                            <div class="p-5">
                                <p class="flex items-center gap-2 text-xs text-ink-faint">
                                    <x-icon name="calendar" class="size-3.5" />
                                    {{ $n->published_at->translatedFormat('d F Y') }}
                                    <span class="text-line-strong">·</span>
                                    {{ $n->category }}
                                </p>
                                <h3 class="mt-2.5 font-display text-lg font-semibold leading-snug text-brand-950 group-hover:text-brand-700">{{ $n->title }}</h3>
                                <p class="mt-2 text-sm text-ink-soft">{{ Str::limit(strip_tags($n->excerpt ?? $n->content), 90) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Ekstrakurikuler --}}
    <section class="section bg-white">
        <div class="container-site">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <x-section-heading
                    eyebrow="Ekstrakurikuler"
                    title="Terus belajar di luar kelas"
                    lede="Organisasi dan kegiatan pembinaan minat serta bakat siswa di berbagai bidang."
                />
                <a href="{{ route('extracurriculars.index') }}" class="inline-flex items-center gap-2 font-semibold text-brand-800 hover:text-accent-700">Semua ekstrakurikuler <x-icon name="arrow-right" class="size-4" /></a>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($extracurriculars as $x)
                    <div class="flex items-center gap-3 rounded-lg border border-line bg-paper px-4 py-3.5">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-brand-900 text-accent-300">
                            @if (Str::contains(strtolower($x->name), ['basket', 'buli', 'voli', 'futsal', 'renang', 'silat', 'karate', 'badminton']))
                                <x-icon name="trophy" class="size-4" />
                            @else
                                <x-icon name="sparkles" class="size-4" />
                            @endif
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-brand-950">{{ $x->name }}</p>
                            <p class="truncate text-xs text-ink-faint">{{ $x->schedule }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA PPDB --}}
    <section class="border-t border-line bg-paper">
        <div class="container-site py-16 sm:py-20">
            <div class="relative overflow-hidden rounded-lg bg-brand-950 px-6 py-12 text-center shadow-card-lg sm:px-12">
                <div class="absolute inset-0 bg-[radial-gradient(40rem_20rem_at_50%_-20%,rgba(209,127,34,0.4),transparent)]"></div>
                <div class="relative mx-auto max-w-2xl">
                    <p class="eyebrow eyebrow-on-dark mx-auto justify-center">Penerimaan Peserta Didik Baru</p>
                    <h2 class="mt-4 font-display text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                        Raih masa depanmu di sini.
                    </h2>
                    <p class="mt-4 text-sm leading-relaxed text-brand-200 sm:text-base">
                        Pendaftaran PPDB tahun ajaran {{ now()->year + 1 }}/{{ now()->year + 2 }} telah dibuka.
                        Simak persyaratan, jadwal, dan tahapan pendaftaran.
                    </p>
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('admission.show') }}" class="btn btn-accent btn-lg">Lihat Info PPDB</a>
                        <a href="{{ route('contact.show') }}" class="btn btn-lg border border-brand-700 bg-white/5 text-white hover:border-accent-400 hover:text-accent-300">Hubungi Panitia</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.public>