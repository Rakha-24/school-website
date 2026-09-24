<x-layouts.portal :title="'Nilai Pengumpulan'" :role="'teacher'">
    <x-page-header :title="'Penilaian: '.$assignment->title"
        :description="$assignment->classSubject?->schoolClass?->name.' · '.$assignment->classSubject?->subject?->name"
        :back="route('portal.teacher.submissions.index')" />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    @php($byStudent = $assignment->submissions->keyBy('student_id'))

    <section class="panel">
        <div class="panel-head">
            <div>
                <p class="eyebrow">Daftar siswa</p>
                <h2 class="font-display text-lg font-semibold text-brand-950">{{ $assignment->classSubject?->schoolClass?->name }}</h2>
            </div>
            <div class="flex gap-2">
                <span class="badge badge-brand">{{ $assignment->submissions->count() }} pengumpulan</span>
                <span class="badge badge-amber">{{ $assignment->submissions->whereNull('score')->count() }} belum dinilai</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-site">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Status pengumpulan</th>
                        <th class="min-w-80">Penilaian</th>
                        <th class="w-24"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assignment->classSubject?->schoolClass?->students ?? collect() as $student)
                        @php($submission = $byStudent->get($student->id))
                        <tr>
                            <td>
                                <p class="font-medium text-brand-950">{{ $student->user?->name }}</p>
                                <p class="text-xs text-ink-soft">{{ $student->student_number }}</p>
                            </td>
                            <td>
                                @if ($submission)
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        @if ($submission->isGraded())
                                            <span class="badge badge-green">Dinilai</span>
                                        @elseif ($submission->isLate())
                                            <span class="badge badge-red">Terlambat</span>
                                        @else
                                            <span class="badge badge-amber">Menunggu dinilai</span>
                                        @endif
                                        <a href="{{ $submission->fileUrl() }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-accent-700 hover:text-brand-900">
                                            <x-icon name="document" class="size-3.5" /> Berkas
                                        </a>
                                        <span class="text-xs text-ink-faint">{{ $submission->submitted_at?->translatedFormat('d M, H:i') }}</span>
                                    </div>
                                @else
                                    <span class="badge badge-slate">Belum mengumpulkan</span>
                                @endif
                            </td>
                            @if ($submission)
                                <td colspan="2">
                                    <form action="{{ route('portal.teacher.submissions.grade', $submission) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                        @csrf
                                        <div class="flex items-center gap-1.5">
                                            <input type="number" name="score" min="0" max="100" required
                                                value="{{ old('score', $submission?->score) }}" placeholder="0–100"
                                                class="field !w-20 !py-1.5 text-center">
                                            <span class="text-xs text-ink-soft">/100</span>
                                        </div>
                                        <input type="text" name="feedback" value="{{ old('feedback', $submission?->feedback) }}" placeholder="Catatan singkat"
                                            class="field min-w-44 flex-1 !py-1.5">
                                        <button class="btn btn-primary btn-xs">{{ $submission->isGraded() ? 'Perbarui' : 'Simpan' }}</button>
                                    </form>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <x-empty-state title="Belum ada siswa" description="Kelas ini belum memiliki data siswa." icon="users" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan saat menyimpan nilai. Pastikan nilai antara 0–100.
        </div>
    @endif
</x-layouts.portal>