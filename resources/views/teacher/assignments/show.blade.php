<x-layouts.portal :title="'Detail Tugas'" :role="'teacher'">
    <x-page-header :title="'Detail Tugas'" :description="'Detail tugas berisi ringkasan dan daftar pengumpulan siswa.'"
        :back="route('portal.teacher.assignments.index')" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="panel panel-pad lg:col-span-2">
            <div class="flex flex-wrap items-center gap-2">
                @if ($assignment->isOpen())
                    <span class="badge badge-green">Terbuka</span>
                @elseif ($assignment->isOverdue())
                    <span class="badge badge-red">Berakhir</span>
                @else
                    <span class="badge badge-slate">Tertutup</span>
                @endif
                <span class="text-xs text-ink-soft">{{ $assignment->submissions->count() }} pengumpulan</span>
                <span class="badge badge-amber">{{ $assignment->submissions->whereNull('score')->count() }} belum dinilai</span>
            </div>

            @if ($assignment->description)
                <div class="article-prose mt-6 border-t border-line pt-6">
                    {!! $assignment->description !!}
                </div>
            @endif

            @if ($assignment->attachment)
                <div class="mt-6 border-t border-line pt-6">
                    <a href="{{ $assignment->attachmentUrl() }}" target="_blank" class="btn btn-outline">
                        <x-icon name="arrow-down-tray" class="size-4" /> Unduh berkas tugas
                    </a>
                </div>
            @endif
        </section>

        <aside class="space-y-6">
            <section class="panel panel-pad">
                <p class="eyebrow">Kelas & mapel</p>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Kelas</dt>
                        <dd class="font-medium text-ink-900">{{ $assignment->classSubject?->schoolClass?->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Mapel</dt>
                        <dd class="text-right font-medium text-ink-900">{{ $assignment->classSubject?->subject?->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Tenggat</dt>
                        <dd class="text-right font-medium text-ink-900">{{ $assignment->due_at?->translatedFormat('d M Y, H:i') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Pengumpulan</dt>
                        <dd class="font-medium text-ink-900">{{ $assignment->submissions->count() }}</dd>
                    </div>
                </dl>
            </section>

            <div class="flex flex-col gap-3">
                <a href="{{ route('portal.teacher.submissions.assignment', $assignment) }}" class="btn btn-primary w-full">
                    <x-icon name="check-circle" class="size-4" /> Beri nilai pengumpulan
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('portal.teacher.assignments.edit', $assignment) }}" class="btn btn-outline flex-1">
                        <x-icon name="pencil" class="size-4" /> Edit
                    </a>
                    <form action="{{ route('portal.teacher.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</x-layouts.portal>