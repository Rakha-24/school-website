<x-layouts.portal :title="'Pengumuman'" :role="'teacher'">
    <x-page-header title="Pengumuman" description="Informasi akademik dan organisasi yang ditujukan untuk siswa maupun guru."
        :actions="[['label' => 'Buat pengumuman', 'route' => 'portal.teacher.announcements.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <section class="panel divide-y divide-line">
        @forelse ($announcements as $announcement)
            <a href="{{ route('portal.teacher.announcements.show', $announcement) }}" class="block px-5 py-4 transition-colors hover:bg-paper">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="truncate text-sm font-semibold text-brand-950">{{ $announcement->title }}</p>
                            @if ($announcement->isPublished())
                                <span class="badge badge-green">Terbit</span>
                            @else
                                <span class="badge badge-slate">Draf</span>
                            @endif
                            <span class="badge badge-brand">{{ \App\Models\Announcement::AUDIENCES[$announcement->audience] ?? $announcement->audience }}</span>
                        </div>
                        <p class="mt-1 truncate text-sm text-ink-soft">{{ $announcement->content }}</p>
                        <p class="mt-1 text-xs text-ink-faint">
                            Oleh {{ $announcement->author?->name }} · {{ $announcement->published_at?->translatedFormat('d M Y') ?? 'Belum terbit' }}
                        </p>
                    </div>
                    <span class="mt-1 flex size-8 shrink-0 items-center justify-center rounded-md border border-line text-brand-700">
                        <x-icon name="chevron-right" class="size-4" />
                    </span>
                </div>
            </a>
        @empty
            <x-empty-state title="Belum ada pengumuman" description="Buat pengumuman pertama untuk siswa atau guru." icon="megaphone" />
        @endforelse
    </section>
</x-layouts.portal>