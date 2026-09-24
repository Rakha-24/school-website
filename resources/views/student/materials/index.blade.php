<x-layouts.portal :title="'Materi Pelajaran'" :role="'student'">
    <x-page-header title="Materi pelajaran" description="Kumpulan materi yang dibagikan guru untuk kelas Anda." />

    {{-- Filter per mata pelajaran --}}
    <nav class="no-scrollbar mb-6 flex flex-wrap gap-2 overflow-x-auto" aria-label="Filter mata pelajaran">
        <a href="{{ route('portal.student.materials.index') }}"
           class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ $activeSubject === null ? 'border-brand-800 bg-brand-800 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' }}">
            Semua
        </a>
        @foreach ($classSubjects as $classSubject)
            <a href="{{ route('portal.student.materials.index', ['subject' => $classSubject->subject_id]) }}"
               class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ (string) $activeSubject === (string) $classSubject->subject_id ? 'border-brand-800 bg-brand-800 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' }}">
                {{ $classSubject->subject?->name }}
            </a>
        @endforeach
    </nav>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($materials as $material)
            <article class="panel group flex flex-col overflow-hidden transition-shadow hover:shadow-card-lg">
                <a href="{{ route('portal.student.materials.show', $material) }}" class="flex h-full flex-col p-5">
                    <div class="flex items-start justify-between gap-3">
                        <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                            <x-icon name="{{ $material->file_path ? 'document' : 'book-open' }}" class="size-5" />
                        </span>
                        <span class="badge badge-brand">{{ $material->classSubject?->subject?->name }}</span>
                    </div>
                    <h2 class="mt-4 font-display text-base font-semibold text-brand-950 group-hover:text-brand-700">{{ $material->title }}</h2>
                    @if ($material->description)
                        <p class="mt-1.5 line-clamp-3 text-sm text-ink-soft">{{ $material->description }}</p>
                    @endif
                    <div class="mt-auto pt-4">
                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-ink-soft">
                            <span class="inline-flex items-center gap-1">
                                <x-icon name="user" class="size-3.5" /> {{ $material->teacher?->user?->name ?? 'Guru' }}
                            </span>
                            <time>{{ $material->published_at?->translatedFormat('d M Y') }}</time>
                        </div>
                    </div>
                </a>
            </article>
        @empty
            <div class="sm:col-span-2 lg:col-span-3">
                <x-empty-state icon="book-open" title="Belum ada materi"
                    :description="$activeSubject !== null ? 'Belum ada materi untuk mata pelajaran ini. Silakan pilih mata pelajaran lain.' : 'Materi yang dibagikan guru belum tersedia. Silakan cek kembali nanti.'" />
            </div>
        @endforelse
    </div>
</x-layouts.portal>