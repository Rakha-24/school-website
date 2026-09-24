<x-layouts.portal :title="'Pesan Masuk'" :role="'admin'">
    <x-page-header title="Pesan masuk" description="Tanggapan calon siswa, orang tua, dan pengunjung dari formulir kontak situs."
        :actions="array_filter([
            ($unreadCount ?? 0) > 0 ? ['label' => 'Tandai semua dibaca', 'route' => 'portal.admin.messages.index', 'icon' => 'check'] : null,
        ])"
    />
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="panel lg:col-span-3">
            <div class="overflow-x-auto">
                <table class="table-site">
                    <thead>
                        <tr>
                            <th>Pengirim</th>
                            <th>Subjek</th>
                            <th>Status</th>
                            <th>Diterima</th>
                            <th class="w-24"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($messages as $message)
                            <tr>
                                <td>
                                    <p class="font-medium text-brand-950">{{ $message->name }}</p>
                                    <p class="text-xs text-ink-soft">{{ $message->email }}</p>
                                </td>
                                <td class="max-w-xs">
                                    <p class="truncate font-medium text-ink-900">{{ $message->subject }}</p>
                                    <p class="truncate text-xs text-ink-soft">{{ $message->message }}</p>
                                </td>
                                <td>
                                    @if ($message->is_read)
                                        <span class="badge badge-slate">Dibaca</span>
                                    @else
                                        <span class="badge badge-accent">Baru</span>
                                    @endif
                                </td>
                                <td class="text-sm text-ink-soft">{{ $message->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('portal.admin.messages.show', $message) }}" class="btn btn-ghost btn-xs">
                                            <x-icon name="eye" class="size-4" /> Baca
                                        </a>
                                        <form action="{{ route('portal.admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50">
                                                <x-icon name="trash" class="size-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <x-empty-state title="Belum ada pesan" description="Pesan dari formulir kontak akan tampil di sini." icon="inbox" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4">
                {{ $messages->links() }}
            </div>
        </section>
    </div>
</x-layouts.portal>