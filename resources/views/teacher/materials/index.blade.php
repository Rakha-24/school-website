<x-layouts.portal :title="'Materi'" :role="'teacher'">
    <x-page-header title="Materi pembelajaran" description="Dokumen dan catatan materi yang dibagikan ke siswa."
        :actions="[['label' => 'Buat materi', 'route' => 'portal.teacher.materials.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <section class="panel divide-y divide-line">
        @forelse ($materials as $material)
            <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4">
                <div class="flex min-w-0 items-start gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                        <x-icon name="document" class="size-5" />
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-brand-950">{{ $material->title }}</p>
                        <p class="text-xs text-ink-soft">
                            {{ $material->classSubject?->schoolClass?->name }} · {{ $material->classSubject?->subject?->name }}
                        </p>
                        <div class="mt-1.5 flex flex-wrap items-center gap-2">
                            @if ($material->isPublished())
                                <span class="badge badge-green">Terbit</span>
                            @else
                                <span class="badge badge-slate">Draf</span>
                            @endif
                            <span class="text-xs text-ink-faint">{{ $material->published_at?->translatedFormat('d M Y') ?? 'Belum terbit' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('portal.teacher.materials.show', $material) }}" class="btn btn-ghost btn-xs">
                        <x-icon name="eye" class="size-4" /> Lihat
                    </a>
                    <a href="{{ route('portal.teacher.materials.edit', $material) }}" class="btn btn-ghost btn-xs">
                        <x-icon name="pencil" class="size-4" /> Edit
                    </a>
                    <form action="{{ route('portal.teacher.materials.destroy', $material) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                    </form>
                </div>
            </div>
        @empty
            <x-empty-state title="Belum ada materi" description="Buat dan bagikan materi pertama ke kelas Anda." icon="book-open" />
        @endforelse
    </section>
</x-layouts.portal>