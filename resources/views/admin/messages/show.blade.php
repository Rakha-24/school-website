<x-layouts.portal :title="'Detail Pesan'" :role="'admin'">
    @php
        $isNew = ! $message->is_read;
    @endphp
    <x-page-header title="Detail pesan" description="Pesan dari formulir kontak situs publik."
        :back="route('portal.admin.messages.index')" />

    @if (session('status'))
        <div role="status" class="mb-6 flex items-start gap-2.5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="mt-0.5 size-4 shrink-0" />
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="panel panel-pad lg:col-span-2">
            <div class="border-b border-line pb-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="font-display text-xl font-semibold text-brand-950">{{ $message->subject }}</h2>
                        <p class="mt-1 text-sm text-ink-soft">{{ $message->name }} · <a class="font-medium text-brand-700 hover:text-brand-900" href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
                    </div>
                    @if ($isNew)
                        <span class="badge badge-accent">Baru</span>
                    @else
                        <span class="badge badge-slate">Dibaca</span>
                    @endif
                </div>
                <time class="mt-3 block text-xs text-ink-faint">Diterima {{ $message->created_at->translatedFormat('d F Y, H:i') }} ({{ $message->created_at->diffForHumans() }})</time>
            </div>

            <div class="article-prose mt-5">
                {!! nl2br(e($message->message)) !!}
            </div>
        </section>

        <aside class="space-y-6">
            <section class="panel panel-pad">
                <p class="eyebrow">Tindakan</p>
                <div class="mt-4 flex flex-col gap-2">
                    <a href="mailto:{{ $message->email }}?subject=Re: {{ rawurlencode($message->subject) }}" class="btn btn-primary w-full">
                        <x-icon name="mail" class="size-4" /> Balas lewat email
                    </a>
                    <form action="{{ route('portal.admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost w-full !text-red-600 hover:!bg-red-50">
                            <x-icon name="trash" class="size-4" /> Hapus pesan
                        </button>
                    </form>
                </div>
            </section>

            <section class="panel panel-pad">
                <p class="eyebrow">Pengirim</p>
                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-ink-soft">Nama</dt>
                        <dd class="font-medium text-ink-900">{{ $message->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-ink-soft">Email</dt>
                        <dd class="break-all font-medium text-brand-700">{{ $message->email }}</dd>
                    </div>
                </dl>
            </section>
        </aside>
    </div>
</x-layouts.portal>