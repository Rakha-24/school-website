<x-layouts.portal :title="'Pengumuman'" :role="'admin'">
    <x-page-header title="Pengumuman" description="Informasi untuk siswa dan guru yang tampil di portal masing-masing."
        :actions="[['label' => 'Buat pengumuman', 'route' => 'portal.admin.announcements.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <section class="panel">
        <div class="overflow-x-auto">
            <table class="table-site">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Audiensi</th>
                        <th>Status</th>
                        <th>Penulis</th>
                        <th>Diterbitkan</th>
                        <th class="w-32"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        @php($audienceBadge = ['public' => 'badge-green', 'students' => 'badge-amber', 'teachers' => 'badge-brand', 'all' => 'badge-accent'][$announcement->audience] ?? 'badge-slate')
                        <tr>
                            <td class="max-w-xs">
                                <p class="truncate font-medium text-brand-950">{{ $announcement->title }}</p>
                                <p class="truncate text-xs text-ink-soft">{{ $announcement->content }}</p>
                            </td>
                            <td>
                                <span class="badge {{ $audienceBadge }}">{{ \App\Models\Announcement::AUDIENCES[$announcement->audience] ?? $announcement->audience }}</span>
                            </td>
                            <td>
                                @if ($announcement->isPublished())
                                    <span class="badge badge-green">Terbit</span>
                                @else
                                    <span class="badge badge-slate">Draf</span>
                                @endif
                            </td>
                            <td class="text-sm text-ink-soft">{{ $announcement->author?->name }}</td>
                            <td class="text-sm text-ink-soft">{{ $announcement->published_at?->translatedFormat('d M Y') ?? '—' }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('portal.admin.announcements.edit', $announcement) }}" class="btn btn-ghost btn-xs">
                                        <x-icon name="pencil" class="size-4" /> Edit
                                    </a>
                                    <form action="{{ route('portal.admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state title="Belum ada pengumuman" description="Pengumuman untuk siswa dan guru akan tampil di sini." icon="megaphone" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4">
            {{ $announcements->links() }}
        </div>
    </section>
</x-layouts.portal>