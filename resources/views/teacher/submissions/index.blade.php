<x-layouts.portal :title="'Penilaian'" :role="'teacher'">
    <x-page-header title="Penilaian tugas" description="Pantau pengumpulan tugas dan beri nilai untuk setiap kelas." />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <section class="panel divide-y divide-line">
        @forelse ($assignments as $assignment)
            <a href="{{ route('portal.teacher.submissions.assignment', $assignment) }}" class="flex flex-wrap items-center justify-between gap-4 px-5 py-4 transition-colors hover:bg-paper">
                <div class="flex min-w-0 items-start gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                        <x-icon name="clipboard" class="size-5" />
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-brand-950">{{ $assignment->title }}</p>
                        <p class="text-xs text-ink-soft">
                            {{ $assignment->classSubject?->schoolClass?->name }} · {{ $assignment->classSubject?->subject?->name }}
                        </p>
                        <div class="mt-1.5 flex flex-wrap gap-2">
                            <span class="text-xs text-ink-faint">{{ $assignment->submissions_count }} pengumpulan</span>
                            <span class="text-xs text-ink-faint">·</span>
                            <span class="text-xs text-ink-faint">Tenggat {{ $assignment->due_at?->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if ($assignment->pending_count > 0)
                        <span class="badge badge-amber">{{ $assignment->pending_count }} belum dinilai</span>
                    @else
                        <span class="badge badge-green">Selesai</span>
                    @endif
                    <span class="flex size-8 items-center justify-center rounded-md border border-line text-brand-700">
                        <x-icon name="chevron-right" class="size-4" />
                    </span>
                </div>
            </a>
        @empty
            <x-empty-state title="Belum ada tugas" description="Tugas yang Anda buat akan tampil di sini untuk dinilai." icon="check-circle" />
        @endforelse
    </section>
</x-layouts.portal>