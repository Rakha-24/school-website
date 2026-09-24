<x-layouts.portal :title="'Detail Pengumuman'" :role="'teacher'">
    <x-page-header :title="$announcement->title" :description="'Pengumuman untuk '.(\App\Models\Announcement::AUDIENCES[$announcement->audience] ?? $announcement->audience)"
        :back="route('portal.teacher.announcements.index')" />

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="panel panel-pad lg:col-span-2">
            <div class="flex flex-wrap items-center gap-2">
                @if ($announcement->isPublished())
                    <span class="badge badge-green">Terbit</span>
                @else
                    <span class="badge badge-slate">Draf</span>
                @endif
                <span class="badge badge-brand">{{ \App\Models\Announcement::AUDIENCES[$announcement->audience] ?? $announcement->audience }}</span>
                <span class="text-xs text-ink-soft">Oleh {{ $announcement->author?->name }}</span>
            </div>

            <div class="article-prose mt-6 border-t border-line pt-6">
                {!! $announcement->content !!}
            </div>

            <p class="mt-6 border-t border-line pt-4 text-xs text-ink-faint">
                Dipublikasikan {{ $announcement->published_at?->translatedFormat('d F Y, H:i') ?? 'Belum terbit' }}
            </p>
        </section>

        <aside class="space-y-6">
            <section class="panel panel-pad">
                <p class="eyebrow">Informasi dasar</p>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Penerima</dt>
                        <dd class="font-medium text-ink-900">{{ \App\Models\Announcement::AUDIENCES[$announcement->audience] ?? $announcement->audience }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Status</dt>
                        <dd class="font-medium text-ink-900">{{ $announcement->isPublished() ? 'Terbit' : 'Draf' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-ink-soft">Penulis</dt>
                        <dd class="text-right font-medium text-ink-900">{{ $announcement->author?->name }}</dd>
                    </div>
                </dl>
            </section>

            <a href="{{ route('portal.teacher.announcements.create') }}" class="btn btn-outline w-full">
                <x-icon name="plus" class="size-4" /> Buat pengumuman baru
            </a>
        </aside>
    </div>
</x-layouts.portal>