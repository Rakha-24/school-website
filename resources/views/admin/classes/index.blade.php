<x-layouts.portal :title="'Manajemen Kelas'" :role="'admin'">
    <x-page-header title="Rombongan belajar" description="Kelola kelas, wali kelas, kapasitas, dan status."
        :actions="[['label' => 'Tambah kelas', 'route' => 'portal.admin.classes.create', 'icon' => 'plus']]" />

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($classes as $class)
            <div class="panel p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-display text-lg font-semibold text-brand-950">{{ $class->name }}</h3>
                        <p class="mt-0.5 text-xs font-medium tracking-wide {{ $class->status === 'active' ? 'text-accent-700' : 'text-ink-500' }}">
                            {{ $class->status === 'active' ? 'Aktif' : 'Arsip' }}
                        </p>
                    </div>
                    <span class="badge badge-soft">{{ $class->students_count }} siswa</span>
                </div>

                <dl class="mt-4 space-y-2 border-t border-line pt-4 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Wali kelas</dt>
                        <dd class="text-right font-medium text-ink-900">{{ $class->homeroomTeacher?->user?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Rombel</dt>
                        <dd class="font-medium text-ink-900">{{ $class->girls_count }} P {{ $class->boys_count }} L</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Mapel</dt>
                        <dd class="font-medium text-ink-900">{{ $class->class_subjects_count }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Jadwal</dt>
                        <dd class="font-medium text-ink-900">{{ $class->schedules_count }}</dd>
                    </div>
                </dl>

                <div class="mt-5 grid grid-cols-2 gap-2 border-t border-line pt-4">
                    <a href="{{ route('portal.admin.classes.edit', $class) }}"
                       class="btn rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 hover:text-amber-800">
                        <x-icon name="pencil" class="size-4" /> Edit
                    </a>
                    <form action="{{ route('portal.admin.classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Hapus kelas ini beserta data terkait?')">
                        @csrf @method('DELETE')
                        <button class="btn w-full rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700">
                            <x-icon name="trash" class="size-4" /> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-3">
                <x-empty-state title="Belum ada kelas" description="Buat kelas pertama lewat tombol Tambah kelas." icon="school" />
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $classes->links() }}</div>
</x-layouts.portal>