<x-layouts.portal :title="'Tugas'" :role="'teacher'">
    <x-page-header title="Tugas pembelajaran" description="Kelola tugas yang diberikan kepada siswa per kelas dan mapel."
        :actions="[['label' => 'Buat tugas', 'route' => 'portal.teacher.assignments.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <section class="panel divide-y divide-line">
        @forelse ($assignments as $assignment)
            <div class="flex flex-wrap items-center justify-between gap-4 px-5 py-4">
                <div class="flex min-w-0 items-start gap-3">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                        <x-icon name="clipboard" class="size-5" />
                    </span>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-brand-950">{{ $assignment->title }}</p>
                        <p class="text-xs text-ink-soft">
                            {{ $assignment->classSubject?->schoolClass?->name }} · {{ $assignment->classSubject?->subject?->name }}
                        </p>
                        <div class="mt-1.5 flex flex-wrap items-center gap-2">
                            @if ($assignment->isOpen())
                                <span class="badge badge-green">Terbuka</span>
                            @elseif ($assignment->isOverdue())
                                <span class="badge badge-red">Berakhir</span>
                            @else
                                <span class="badge badge-slate">Tertutup</span>
                            @endif
                            <span class="text-xs text-ink-faint">Tenggat {{ $assignment->due_at?->translatedFormat('d M Y, H:i') }}</span>
                            <span class="text-xs text-ink-faint">{{ $assignment->submissions_count }} pengumpulan</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('portal.teacher.assignments.show', $assignment) }}" class="btn btn-ghost btn-xs">
                        <x-icon name="eye" class="size-4" /> Lihat
                    </a>
                    <a href="{{ route('portal.teacher.assignments.edit', $assignment) }}" class="btn btn-ghost btn-xs">
                        <x-icon name="pencil" class="size-4" /> Edit
                    </a>
                    <form action="{{ route('portal.teacher.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                    </form>
                </div>
            </div>
        @empty
            <x-empty-state title="Belum ada tugas" description="Buat tugas pertama untuk kelas Anda." icon="clipboard" />
        @endforelse
    </section>
</x-layouts.portal>