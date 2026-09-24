<x-layouts.portal :title="'Detail Pengumuman'" :role="'student'">
    <x-page-header :title="$announcement->title" :description="'Diterbitkan oleh '.($announcement->author?->name ?? 'Admin')"
        :back="route('portal.student.announcements.index')" />

    <article class="panel panel-pad">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line pb-5">
            <span class="badge badge-brand">{{ \App\Models\Announcement::AUDIENCES[$announcement->audience] ?? $announcement->audience }}</span>
            <time class="inline-flex items-center gap-1.5 text-sm text-ink-soft">
                <x-icon name="calendar" class="size-4 text-accent-600" />
                {{ $announcement->published_at?->translatedFormat('l, d F Y • H:i') }}
            </time>
        </div>

        <div class="article-prose prose-measure mt-6 whitespace-pre-line">
            {!! nl2br(e($announcement->content)) !!}
        </div>
    </article>

    <div class="mt-6 flex items-center gap-2 rounded-md border border-line bg-white px-4 py-3 text-sm text-ink-soft">
        <x-icon name="megaphone" class="size-4" />
        Butuh bantuan? Hubungi wali kelas atau guru Anda melalui pesan pada portal.
    </div>
</x-layouts.portal>