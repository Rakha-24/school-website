<x-layouts.portal :title="'Detail Materi'" :role="'student'">
    @php
        $bytes = $material->fileSize();
        $humanSize = null;
        if ($bytes !== null) {
            $humanSize = $bytes >= 1048576 ? number_format($bytes / 1048576, 1).' MB' : max(1, round($bytes / 1024)).' KB';
        }
    @endphp

    <x-page-header :title="$material->title" :description="'Materi '.$material->classSubject?->subject?->name.' — kelas '.$material->classSubject?->schoolClass?->name"
        :back="route('portal.student.materials.index')" />

    <article class="panel panel-pad">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line pb-5">
            <div class="flex flex-wrap items-center gap-2">
                <span class="badge badge-brand">{{ $material->classSubject?->subject?->name }}</span>
                @if ($material->file_path)
                    <span class="badge badge-slate">Ada lampiran</span>
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-ink-soft">
                <span class="inline-flex items-center gap-1.5">
                    <x-icon name="user" class="size-3.5" /> {{ $material->teacher?->user?->name ?? '—' }}
                </span>
                <time class="inline-flex items-center gap-1.5">
                    <x-icon name="calendar" class="size-3.5" /> {{ $material->published_at?->translatedFormat('d F Y') }}
                </time>
            </div>
        </div>

        @if ($material->description)
            <p class="mt-5 text-base font-medium text-brand-950">{{ $material->description }}</p>
        @endif

        @if ($material->content)
            <div class="article-prose prose-measure mt-5 whitespace-pre-line">
                {!! nl2br(e($material->content)) !!}
            </div>
        @endif

        @if ($material->fileUrl())
            <div class="mt-8 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-line bg-paper p-4">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                        <x-icon name="document" class="size-5" />
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-brand-950">Berkas materi</p>
                        @if ($humanSize !== null)
                            <p class="text-xs text-ink-soft">Ukuran {{ $humanSize }}</p>
                        @endif
                    </div>
                </div>
                <a href="{{ $material->fileUrl() }}" class="btn btn-outline btn-sm" target="_blank" rel="noopener">
                    <x-icon name="arrow-down-tray" class="size-4" /> Unduh / buka
                </a>
            </div>
        @endif
    </article>
</x-layouts.portal>