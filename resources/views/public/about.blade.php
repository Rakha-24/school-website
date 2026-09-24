<x-layouts.public title="Profil Sekolah">
    <x-page-hero
        eyebrow="Tentang Sekolah"
        title="Profil SMK Taruna Sains Kediri"
        lede="Sekolah menengah kejuruan dengan satu program keahlian unggulan: Teknik Alat Berat — menyiapkan lulusan siap kerja, siap berkarya, dan berkarakter kuat melalui pembelajaran berbasis praktik."
        image="seeds/campus.jpg"
    />

    {{-- Sejarah & visi --}}
    <section class="section bg-paper">
        <div class="container-site grid gap-10 lg:grid-cols-[1.4fr_1fr]">
            <div class="prose-measure">
                <x-section-heading eyebrow="Sejarah" title="Tumbuh bersama kebutuhan industri" />
                <div class="article-prose">
                    <p>
                        SMK Taruna Sains Kediri hadir untuk menjawab kebutuhan tenaga kerja terampil
                        di sektor alat berat: konstruksi, pertambangan, dan perkebunan. Sejak awal berdiri, sekolah ini menerapkan
                        pembelajaran yang menyeimbangkan teori di kelas dengan praktik langsung di bengkel dan laboratorium.
                    </p>
                    <p>
                        Dengan satu kompetensi keahlian, Teknik Alat Berat, fokus kami adalah kedalaman, bukan luas semu.
                        Kemitraan dengan dunia usaha dan dunia industri menjadi bagian penting dari kurikulum,
                        termasuk pelaksanaan Praktik Kerja Lapangan (PKL) bagi peserta didik kelas XI dan XII.
                    </p>
                    <blockquote>
                        "Siswa belajar dengan cara yang sama seperti dunia kerja: mencoba, mengoreksi, dan menguji karya."
                    </blockquote>
                </div>
            </div>

            <div class="space-y-6">
                <div class="panel panel-pad">
                    <p class="eyebrow">Visi</p>
                    <p class="mt-3 font-display text-lg leading-relaxed text-brand-950">
                        Menjadi sekolah kejuruan unggulan yang menghasilkan lulusan berkarakter, kompeten, mandiri, dan siap bersaing di tingkat nasional maupun internasional.
                    </p>
                </div>
                <div class="panel panel-pad">
                    <p class="eyebrow">Misi</p>
                    <ul class="mt-3 space-y-3 text-sm text-ink-soft">
                        <li class="flex gap-2.5"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-accent-500"></span> Menyelenggarakan pembelajaran berbasis praktik dan industri.</li>
                        <li class="flex gap-2.5"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-accent-500"></span> Menumbuhkan karakter religius, disiplin, dan integritas.</li>
                        <li class="flex gap-2.5"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-accent-500"></span> Mengembangkan potensi siswa melalui wirausaha dan ekstrakurikuler.</li>
                        <li class="flex gap-2.5"><span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-accent-500"></span> Membangun kemitraan berkelanjutan dengan dunia usaha dan industri.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Statistik sekolah --}}
    <section class="bg-brand-950 py-14 text-white">
        <div class="container-site grid grid-cols-2 divide-x divide-brand-800 sm:grid-cols-4">
            <x-stat label="Siswa Aktif" value="700+" />
            <x-stat label="Guru" value="30+" />
            <x-stat label="Rombel" value="{{ $classCount }}" />
            <x-stat label="Prestasi Terdaftar" value="{{ $achievementCount }}" />
        </div>
    </section>

    {{-- Program keahlian & mapel --}}
    <section class="section bg-white">
        <div class="container-site">
            <x-section-heading
                eyebrow="Struktur Akademik"
                title="Program keahlian & pembagian kelas"
                lede="Setiap kelas menjalankan kurikulum program keahlian yang didampingi wali kelas dan guru mapel pengampu."
            />

            <div class="space-y-8">
                @foreach ($classSubjects as $className => $rows)
                    <div class="panel overflow-hidden">
                        <div class="flex items-center justify-between gap-4 border-b border-line bg-paper px-5 py-3.5">
                            <h3 class="font-display text-lg font-semibold text-brand-950">{{ $className }}</h3>
                            <span class="badge badge-brand">{{ count($rows) }} mapel</span>
                        </div>
                        <div class="grid gap-x-8 gap-y-2 px-5 py-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($rows as $cs)
                                <p class="flex items-start justify-between gap-3 border-b border-line/70 py-2 text-sm last:border-0">
                                    <span class="font-medium text-ink-900">{{ $cs->subject?->name }}</span>
                                    <span class="shrink-0 text-right text-xs text-ink-soft">{{ $cs->teacher?->user?->name }}</span>
                                </p>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Guru --}}
    @if ($teachingStaff->isNotEmpty())
        <section class="section bg-paper">
            <div class="container-site">
                <x-section-heading
                    eyebrow="Tenaga Pendidik"
                    title="Guru & tenaga kependidikan"
                    lede="Guru bersertifikasi dengan pengalaman mengajar dan pendampingan praktik di bidangnya masing-masing."
                    align="center"
                />
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($teachingStaff as $t)
                        <div class="panel p-5 text-center">
                            <span class="mx-auto flex size-14 items-center justify-center rounded-full bg-brand-900 font-display text-xl font-semibold text-accent-300">
                                {{ strtoupper(mb_substr($t->user?->name ?? '?', 0, 1)) }}
                            </span>
                            <p class="mt-3 font-semibold text-brand-950">{{ $t->user?->name }}</p>
                            <p class="text-xs text-ink-soft">{{ $t->teacher_number }}</p>
                            <p class="mt-2 text-xs text-ink-faint">
                                {{ $t->classSubjects()->with('subject')->get()->pluck('subject.name')->unique()->take(3)->join(', ') ?: 'Guru mapel' }}
                            </p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 text-center">
                    <a href="{{ route('academic.show') }}" class="btn btn-outline">Lihat program keahlian <x-icon name="arrow-right" class="size-4" /></a>
                </div>
            </div>
        </section>
    @endif

    {{-- Kontak singkat --}}
    <section class="border-t border-line bg-white py-14">
        <div class="container-site grid gap-6 sm:grid-cols-3">
            <div class="flex items-start gap-3">
                <x-icon name="map-pin" class="mt-1 size-5 text-accent-600" />
                <div>
                    <p class="text-sm font-semibold text-brand-950">Alamat</p>
                    <p class="text-sm text-ink-soft">Dusun Pule, Desa Karangpakis, <br>Kab. Kediri, Jawa Timur 64154</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <x-icon name="phone" class="mt-1 size-5 text-accent-600" />
                <div>
                    <p class="text-sm font-semibold text-brand-950">Telepon</p>
                    <a href="tel:+62354512345" class="text-sm text-ink-soft hover:text-brand-800">(0354) 512345</a>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <x-icon name="mail" class="mt-1 size-5 text-accent-600" />
                <div>
                    <p class="text-sm font-semibold text-brand-950">Email</p>
                    <a href="mailto:smk.tarunasains@gmail.com" class="text-sm text-ink-soft hover:text-brand-800">smk.tarunasains@gmail.com</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>