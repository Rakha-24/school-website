<x-layouts.portal :title="'Tugas'" :role="'student'">
    <x-page-header title="Tugas" description="Kumpulan tugas dari seluruh mata pelajaran kelas Anda." />

    @if (session('status'))
        <div class="mb-6 flex items-center gap-2 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="size-4" /> {{ session('status') }}
        </div>
    @endif

    {{-- Filter status --}}
    <nav class="no-scrollbar mb-6 flex flex-wrap gap-2 overflow-x-auto" aria-label="Filter status tugas">
        <a href="{{ route('portal.student.assignments.index') }}"
           class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ $activeFilter === null ? 'border-brand-800 bg-brand-800 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' }}">
            Semua
        </a>
        <a href="{{ route('portal.student.assignments.index', ['filter' => 'open']) }}"
           class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ $activeFilter === 'open' ? 'border-brand-800 bg-brand-800 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' }}">
            Terbuka
        </a>
        <a href="{{ route('portal.student.assignments.index', ['filter' => 'done']) }}"
           class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors {{ $activeFilter === 'done' ? 'border-brand-800 bg-brand-800 text-white' : 'border-line bg-white text-ink-700 hover:border-brand-500 hover:text-brand-800' }}">
            Sudah dikerjakan
        </a>
    </nav>

    <section class="panel">
        <div class="overflow-x-auto">
            <table class="table-site">
                <thead>
                    <tr>
                        <th>Tugas</th>
                        <th>Mata pelajaran</th>
                        <th>Tenggat</th>
                        <th>Status</th>
                        <th class="w-28"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignments as $assignment)
                        @php
                            $submitted = $ownSubmissions->has($assignment->id);
                            $score = $ownSubmissions->get($assignment->id);
                        @endphp
                        <tr>
                            <td class="max-w-xs">
                                <p class="truncate font-medium text-brand-950">{{ $assignment->title }}</p>
                                <p class="truncate text-xs text-ink-soft">{{ $assignment->teacher?->user?->name ?? '—' }}</p>
                            </td>
                            <td class="text-ink-soft">{{ $assignment->classSubject?->subject?->name }}</td>
                            <td class="whitespace-nowrap text-sm text-ink-soft">{{ $assignment->due_at->translatedFormat('d M Y, H:i') }}</td>
                            <td>
                                @if ($submitted && $score !== null)
                                    <span class="badge badge-green">Dinilai · {{ $score }}</span>
                                @elseif ($submitted)
                                    <span class="badge badge-brand">Dikirim</span>
                                @elseif ($assignment->isOpen())
                                    <span class="badge badge-accent">Terbuka</span>
                                @else
                                    <span class="badge badge-red">Tenggat lewat</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('portal.student.assignments.show', $assignment) }}" class="btn btn-ghost btn-sm">
                                    Buka <x-icon name="arrow-right" class="size-3.5" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state icon="clipboard" title="Tidak ada tugas"
                                    :description="$activeFilter === 'open' ? 'Tidak ada tugas yang masih terbuka.' : ($activeFilter === 'done' ? 'Belum ada tugas yang Anda kerjakan.' : 'Belum ada tugas dari guru.')" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.portal>