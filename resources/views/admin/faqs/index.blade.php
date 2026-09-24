<x-layouts.portal :title="'FAQ'" :role="'admin'">
    <x-page-header title="FAQ" description="Pertanyaan yang sering diajukan beserta jawabannya untuk halaman publik."
        :actions="[['label' => 'Tambah FAQ', 'route' => 'portal.admin.faqs.create', 'icon' => 'plus']]" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    <section class="panel divide-y divide-line">
        @forelse ($faqs as $faq)
            <div class="flex flex-wrap items-start justify-between gap-4 px-5 py-4">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="font-display text-base font-semibold text-brand-950">{{ $faq->question }}</p>
                        <span class="badge badge-slate">Urutan {{ $faq->sort_order }}</span>
                        @if ($faq->status === 'published')
                            <span class="badge badge-green">Tampil</span>
                        @else
                            <span class="badge badge-slate">Disembunyikan</span>
                        @endif
                    </div>
                    <p class="mt-1 max-w-2xl text-sm text-ink-soft">{{ $faq->answer }}</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('portal.admin.faqs.edit', $faq) }}" class="btn btn-ghost btn-xs">
                        <x-icon name="pencil" class="size-4" /> Edit
                    </a>
                    <form action="{{ route('portal.admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Hapus FAQ ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost btn-xs !text-red-600 hover:!bg-red-50"><x-icon name="trash" class="size-4" /></button>
                    </form>
                </div>
            </div>
        @empty
            <x-empty-state title="Belum ada FAQ" description="Tambahkan pertanyaan dan jawaban yang sering dicari pengunjung." icon="chat" />
        @endforelse
    </section>

    <div class="mt-6">{{ $faqs->links() }}</div>
</x-layouts.portal>