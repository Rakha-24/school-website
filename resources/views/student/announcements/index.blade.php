<x-layouts.portal :title="'Pengumuman'" :role="'student'">
    @php
        $excerpt = fn ($content) => \Illuminate\Support\Str::limit(strip_tags((string) $content), 160);
    @endphp

    <x-page-header title="Pengumuman" description="Informasi terbaru dari sekolah, wali kelas, dan guru untuk siswa." />

    <div class="space-y-4">
        @forelse ($announcements as $announcement)
            <article class="panel">
                <a href="{{ route('portal.student.announcements.show', $announcement) }}" class="block p-5 transition-colors hover:bg-paper-50 sm:p-6">
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                        <h2 class="font-display text-lg font-semibold text-brand-950 hover:text-brand-700">{{ $announcement->title }}</h2>
                        <span class="badge badge-brand">{{ \App\Models\Announcement::AUDIENCES[$announcement->audience] ?? $announcement->audience }}</span>
                    </div>
                    <p class="mt-1 line-clamp-2 text-sm text-ink-soft">{{ $excerpt($announcement->content) }}</p>
                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-ink-faint">
                        <span class="inline-flex items-center gap-1.5">
                            <x-icon name="user" class="size-3.5" /> {{ $announcement->author?->name ?? 'Admin' }}
                        </span>
                        <time class="inline-flex items-center gap-1.5">
                            <x-icon name="calendar" class="size-3.5" /> {{ $announcement->published_at?->translatedFormat('d F Y') }}
                        </time>
                    </div>
                </a>
            </article>
        @empty
            <x-empty-state icon="megaphone" title="Belum ada pengumuman"
                description="Pengumuman yang ditujukan untuk siswa akan tampil di sini." />
        @endforelse
    </div>
</x-layouts.portal>