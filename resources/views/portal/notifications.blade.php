<x-layouts.portal :title="'Notifikasi'" :role="auth()->user()->role">
    <x-page-header title="Notifikasi" description="Pemberitahuan terbaru yang ditujukan untuk Anda." />

    @if (session('status'))
        <div class="mb-6 flex items-center gap-2 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="size-4" /> {{ session('status') }}
        </div>
    @endif

    <section class="panel">
        <div class="divide-y divide-line">
            @forelse ($notifications as $notification)
                @php $data = $notification->data ?? []; @endphp
                <form action="{{ route('portal.notifications.read', $notification->id) }}" method="POST" class="{{ $notification->unread() ? 'bg-brand-50/70' : '' }}">
                    @csrf
                    <button type="submit" class="flex w-full items-start gap-3 px-5 py-4 text-left transition-colors hover:bg-paper">
                        <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-lg {{ $notification->unread() ? 'bg-accent-100 text-accent-700' : 'bg-brand-50 text-brand-700' }}">
                            <x-icon :name="$data['icon'] ?? 'bell'" class="size-4" />
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex flex-wrap items-center justify-between gap-x-3 gap-y-0.5">
                                <span class="text-sm font-semibold {{ $notification->unread() ? 'text-brand-950' : 'text-ink-700' }}">{{ $data['title'] ?? 'Notifikasi' }}</span>
                                <time class="text-xs text-ink-faint">{{ $notification->created_at->diffForHumans() }}</time>
                            </span>
                            <span class="mt-0.5 block text-sm text-ink-soft">{{ $data['message'] ?? '' }}</span>
                        </span>
                        @if ($notification->unread())
                            <span class="mt-2 size-2 shrink-0 rounded-full bg-accent-600" title="Belum dibaca"></span>
                        @endif
                    </button>
                </form>
            @empty
                <x-empty-state title="Tidak ada notifikasi" description="Semua pemberitahuan akan tampil di sini." icon="bell" />
            @endforelse
        </div>
        @if ($notifications->hasPages())
            <div class="border-t border-line px-5 py-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </section>
</x-layouts.portal>