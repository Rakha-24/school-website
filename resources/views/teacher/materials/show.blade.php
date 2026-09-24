<x-layouts.portal :title="'Detail Materi'" :role="'teacher'">
    <x-page-header :title="$material->title" :description="$material->classSubject?->schoolClass?->name.' · '.$material->classSubject?->subject?->name"
        :back="route('portal.teacher.materials.index')" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="panel panel-pad lg:col-span-2">
            <div class="flex flex-wrap items-center gap-2">
                @if ($material->isPublished())
                    <span class="badge badge-green">Terbit</span>
                @else
                    <span class="badge badge-slate">Draf</span>
                @endif
                <span class="text-xs text-ink-soft">Dibuat {{ $material->created_at?->translatedFormat('d M Y') }}</span>
            </div>

            @if ($material->description)
                <p class="mt-5 text-sm text-ink-700">{{ $material->description }}</p>
            @endif

            @if ($material->content)
                <div class="article-prose mt-6 border-t border-line pt-6">
                    {!! $material->content !!}
                </div>
            @endif

            @if ($material->file_path)
                <div class="mt-6 border-t border-line pt-6">
                    <a href="{{ $material->fileUrl() }}" target="_blank" class="btn btn-outline">
                        <x-icon name="arrow-down-tray" class="size-4" /> Unduh berkas materi
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
                        <dd class="font-medium text-ink-900">{{ $material->classSubject?->schoolClass?->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Mapel</dt>
                        <dd class="text-right font-medium text-ink-900">{{ $material->classSubject?->subject?->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Diterbitkan</dt>
                        <dd class="font-medium text-ink-900">{{ $material->published_at?->translatedFormat('d M Y') ?? '—' }}</dd>
                    </div>
                </dl>
            </section>

            <div class="flex items-center gap-3">
                <a href="{{ route('portal.teacher.materials.edit', $material) }}" class="btn btn-primary flex-1">
                    <x-icon name="pencil" class="size-4" /> Edit materi
                </a>
                <form action="{{ route('portal.teacher.materials.destroy', $material) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-ghost !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                </form>
            </div>
        </aside>
    </div>
</x-layouts.portal>