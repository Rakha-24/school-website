<x-layouts.portal :title="'Presensi Siswa'" :role="'teacher'">
    <x-page-header title="Presensi siswa" description="Catat kehadiran siswa untuk kelas yang Anda ampu pada tanggal tertentu." />

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
            <x-icon name="check-circle" class="mt-0.5 size-5 shrink-0 text-emerald-600" />
            <p class="text-sm font-medium text-emerald-800">{{ session('status') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian presensi. Periksa kembali data yang dimasukkan.
        </div>
    @endif

    {{-- Pemilih kelas & tanggal --}}
    <section class="panel panel-pad">
        <h2 class="font-display text-lg font-semibold text-brand-950">Pilih kelas dan tanggal</h2>
        <form method="GET" action="{{ route('portal.teacher.attendance.roster') }}" class="mt-5 grid gap-4 sm:grid-cols-[1fr_auto_auto] sm:items-end">
            <div>
                <label for="class-select" class="label">Kelas</label>
                <select id="class-select" name="class" class="field">
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected((int) ($selectedClass?->id ?? request('class')) === (int) $class->id)>
                            {{ $class->name }} ({{ $class->students_count }} siswa)
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date-input" class="label">Tanggal</label>
                <input id="date-input" type="date" name="date" class="field" value="{{ $date }}" max="{{ now()->toDateString() }}">
            </div>
            <button type="submit" class="btn btn-primary">
                <x-icon name="clipboard" class="size-4" /> Tampilkan
            </button>
        </form>
        <p class="mt-4 text-xs text-ink-soft">Presensi untuk tanggal di masa depan tidak dapat dicatat.</p>
    </section>

    {{-- Daftar presensi --}}
    @if ($selectedClass && $roster->isNotEmpty())
        <section class="panel mt-6">
            <div class="panel-head">
                <div>
                    <p class="eyebrow">Kelas {{ $selectedClass->name }}</p>
                    <h2 class="font-display text-lg font-semibold text-brand-950">Presensi {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</h2>
                </div>
                <span class="badge badge-brand">{{ $roster->count() }} siswa</span>
            </div>

            <form method="POST" action="{{ route('portal.teacher.attendance.store') }}">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
                <input type="hidden" name="date" value="{{ $date }}">

                <div class="divide-y divide-line">
                    @foreach ($roster as $student)
                        @php($record = $existing->get($student->id))
                        @php($name = 'records.'.$student->id)
                        <div class="grid gap-4 px-5 py-4 lg:grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] lg:items-center">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-brand-950">{{ $student->user?->name }}</p>
                                <p class="text-xs text-ink-soft">{{ $student->student_number }}</p>
                            </div>

                            <div class="flex flex-wrap gap-4 lg:justify-self-end">
                                <input type="hidden" name="{{ $name }}[student_id]" value="{{ $student->id }}">
                                @foreach ($statuses as $key => $label)
                                    <label class="flex cursor-pointer items-center gap-1.5 text-sm text-ink-700">
                                        <input type="radio" name="{{ $name }}[status]" value="{{ $key }}"
                                            class="size-4 text-brand-700 focus:ring-brand-600"
                                            @checked(old($name.'.status', $record?->status ?? 'present') === $key)>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>

                            <div class="lg:pl-8">
                                <input type="text" name="{{ $name }}[note]" value="{{ old($name.'.note', $record?->note) }}"
                                    placeholder="Catatan (opsional)" class="field !py-1.5">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between gap-4 border-t border-line px-5 py-4">
                    <p class="text-xs text-ink-soft">Penyimpanan baru akan memutakhirkan data presensi yang sudah ada.</p>
                    <button type="submit" class="btn btn-primary">
                        <x-icon name="check-circle" class="size-4" /> Simpan presensi
                    </button>
                </div>
            </form>
        </section>
    @elseif ($selectedClass)
        <section class="panel mt-6">
            <x-empty-state title="Tidak ada siswa" description="Kelas ini belum memiliki data siswa." icon="users" />
        </section>
    @else
        <section class="panel mt-6">
            <x-empty-state title="Pilih kelas terlebih dahulu" description="Pilih kelas dan tanggal di atas untuk mulai mencatat presensi." icon="clipboard" />
        </section>
    @endif
</x-layouts.portal>