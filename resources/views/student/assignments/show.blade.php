<x-layouts.portal :title="'Detail Tugas'" :role="'student'">
    @php
        $open = $assignment->isOpen();
        $graded = $submission?->isGraded();
    @endphp

    <x-page-header :title="$assignment->title" :description="'Tugas '.$assignment->classSubject?->subject?->name.' — kelas '.$assignment->classSubject?->schoolClass?->name"
        :back="route('portal.student.assignments.index')" />

    @if (session('status'))
        <div class="mb-6 flex items-center gap-2 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="size-4" /> {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Detail tugas --}}
        <section class="panel panel-pad lg:col-span-2">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line pb-5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="badge badge-brand">{{ $assignment->classSubject?->subject?->name }}</span>
                    @if ($graded)
                        <span class="badge badge-green">Dinilai</span>
                    @elseif ($submission && !$open)
                        <span class="badge badge-brand">Terkumpul</span>
                    @elseif ($open)
                        <span class="badge badge-accent">Terbuka</span>
                    @else
                        <span class="badge badge-red">Tenggat lewat</span>
                    @endif
                </div>
                <time class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700">
                    <x-icon name="calendar" class="size-4 text-accent-600" />
                    Tenggat {{ $assignment->due_at->translatedFormat('d F Y, H:i') }}
                    @if (!$open)<span class="text-xs font-normal text-ink-soft">({{ $assignment->due_at->diffForHumans() }})</span>@endif
                </time>
            </div>

            <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-ink-soft">Guru pengampu</dt>
                    <dd class="mt-1 inline-flex items-center gap-1.5 text-sm font-medium text-brand-950">
                        <x-icon name="user" class="size-4 text-ink-soft" /> {{ $assignment->teacher?->user?->name ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-ink-soft">Kelas</dt>
                    <dd class="mt-1 inline-flex items-center gap-1.5 text-sm font-medium text-brand-950">
                        <x-icon name="building-library" class="size-4 text-ink-soft" /> {{ $assignment->classSubject?->schoolClass?->name }}
                    </dd>
                </div>
            </dl>

            @if ($assignment->description)
                <div class="article-prose prose-measure mt-6 whitespace-pre-line">
                    {!! nl2br(e($assignment->description)) !!}
                </div>
            @endif

            @if ($assignment->attachmentUrl())
                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-line bg-paper p-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-700">
                            <x-icon name="document" class="size-5" />
                        </span>
                        <p class="text-sm font-semibold text-brand-950">Berkas lampiran tugas</p>
                    </div>
                    <a href="{{ $assignment->attachmentUrl() }}" class="btn btn-outline btn-sm" target="_blank" rel="noopener">
                        <x-icon name="arrow-down-tray" class="size-4" /> Unduh
                    </a>
                </div>
            @endif
        </section>

        {{-- Status pengumpulan --}}
        <section class="panel">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Pengumpulan</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Status jawaban Anda</h2>
                </div>
            </div>
            <div class="space-y-4 px-5 py-4">
                @if ($submission)
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="badge {{ $graded ? 'badge-green' : 'badge-brand' }}">
                                {{ $graded ? 'Telah dinilai' : 'Menunggu penilaian' }}
                            </span>
                            @if ($submission->isLate())
                                <span class="badge badge-amber">Dikumpulkan terlambat</span>
                            @endif
                        </div>
                        @if ($graded)
                            <div class="flex items-baseline gap-2">
                                <span class="font-display text-4xl font-semibold text-brand-950">{{ $submission->score }}</span>
                                <span class="text-sm text-ink-soft">dari 100</span>
                            </div>
                            @if ($submission->feedback)
                                <p class="rounded-md border border-line bg-paper px-4 py-3 text-sm text-ink-700">{{ $submission->feedback }}</p>
                            @endif
                        @endif
                        <dl class="space-y-1.5 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-ink-soft">Dikumpulkan</dt>
                                <dd class="font-medium text-ink-900">{{ $submission->submitted_at?->translatedFormat('d M Y, H:i') }}</dd>
                            </div>
                            @if ($submission->graded_at)
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-soft">Dinilai</dt>
                                    <dd class="font-medium text-ink-900">{{ $submission->graded_at->translatedFormat('d M Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                        @if ($submission->fileUrl())
                            <a href="{{ $submission->fileUrl() }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 text-sm font-medium text-brand-700 hover:text-brand-900">
                                <x-icon name="document" class="size-4" /> Berkas jawaban
                            </a>
                        @endif
                        @if ($submission->answer_text)
                            <div>
                                <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-ink-soft">Jawaban Anda</p>
                                <p class="whitespace-pre-line rounded-md border border-line bg-paper px-4 py-3 text-sm text-ink-700">{{ $submission->answer_text }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <x-empty-state icon="clipboard" title="Belum dikumpulkan"
                        :description="$open ? 'Anda belum mengumpulkan jawaban untuk tugas ini.' : 'Tugas sudah melewati tenggat waktu dan tidak dapat dikumpulkan lagi.'" />
                @endif
            </div>
        </section>
    </div>

    {{-- Form pengumpulan --}}
    @if ($open && !$graded)
        <section class="panel mt-6">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Kumpulkan</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">{{ $submission ? 'Perbarui jawaban Anda' : 'Kumpulkan jawaban' }}</h2>
                </div>
            </div>
            <form method="POST" action="{{ route('portal.student.assignments.submit', $assignment) }}" enctype="multipart/form-data" class="panel-pad space-y-5">
                @csrf
                <div>
                    <label class="label" for="answer_text">Jawaban tertulis</label>
                    <textarea id="answer_text" name="answer_text" rows="8" class="field" placeholder="Tulis jawaban Anda di sini…">{{ old('answer_text', $submission?->answer_text ?? '') }}</textarea>
                    @error('answer_text')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label" for="file">Atau unggah berkas <span class="font-normal text-ink-soft">(.pdf, .doc, .docx, .txt, .jpg, .png — maks. 20 MB)</span></label>
                    <input type="file" id="file" name="file" class="field" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                    @error('file')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        <x-icon name="arrow-up-tray" class="size-4" /> {{ $submission ? 'Perbarui jawaban' : 'Kumpulkan jawaban' }}
                    </button>
                    <p class="text-xs text-ink-soft">Jawaban boleh disimpan sekali lagi sebelum tenggat untuk diperbarui.</p>
                </div>
            </form>
        </section>
    @elseif (!$open && !$graded && !$submission)
        <div class="mt-6 flex items-center gap-2 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">
            <x-icon name="exclamation-triangle" class="size-4" /> Tugas sudah melewati tenggat waktu dan tidak dapat dikumpulkan.
        </div>
    @endif
</x-layouts.portal>