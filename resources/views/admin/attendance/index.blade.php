<x-layouts.portal :title="'Presensi Siswa'" :role="'admin'">
    <x-page-header title="Presensi siswa" description="Catat kehadiran siswa per kelas dan tanggal." />

    @if (session('status'))
        <div role="status" class="mb-6 flex items-start gap-2.5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="mt-0.5 size-4 shrink-0" />
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <ul class="list-disc space-y-0.5 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" action="{{ route('portal.admin.attendance.index') }}" class="panel panel-pad mb-6">
        <div class="flex flex-wrap items-end gap-4">
            <div class="w-full sm:w-72">
                <label for="class" class="label">Kelas</label>
                <select id="class" name="class" class="field" @required($classes->isNotEmpty())>
                    <option value="" disabled @selected(! $selectedClass)>— Pilih kelas —</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected($selectedClass?->id === $class->id)>{{ $class->displayName() }} ({{ $class->students_count }} siswa)</option>
                    @endforeach
                </select>
                @error('class') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="date" class="label">Tanggal</label>
                <input id="date" type="date" name="date" class="field" value="{{ $date }}" max="{{ now()->toDateString() }}" required />
                @error('date') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <x-icon name="magnifying-glass" class="size-4" /> Tampilkan
            </button>
        </div>
    </form>

    @if (! $selectedClass)
        <section class="panel">
            <x-empty-state title="Pilih kelas terlebih dahulu" description="Pilih kelas dan tanggal untuk mencatat atau melihat presensi siswa." icon="check-circle" />
        </section>
    @elseif ($roster->isEmpty())
        <section class="panel">
            <x-empty-state title="Belum ada siswa di kelas ini" description="Tambahkan siswa ke {{ $selectedClass->name }} agar dapat dicatat presensinya." icon="users" />
        </section>
    @else
        <form method="POST" action="{{ route('portal.admin.attendance.store') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}" />
            <input type="hidden" name="date" value="{{ $date }}" />

            <section class="panel">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">Presensi</p>
                        <h2 class="font-display text-lg font-semibold text-brand-950">{{ $selectedClass->name }} · {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</h2>
                    </div>
                    <span class="badge badge-soft">{{ $roster->count() }} siswa</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-site">
                        <thead>
                            <tr>
                                <th class="w-12">No</th>
                                <th>Siswa</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roster as $student)
                                @php
                                    $record = $existing->get($student->id);
                                @endphp
                                <tr>
                                    <td class="text-ink-300">{{ $loop->iteration }}</td>
                                    <td>
                                        <p class="font-medium text-brand-950">{{ $student->user->name }}</p>
                                        <p class="text-xs text-ink-soft">{{ $student->student_number }}</p>
                                        <input type="hidden" name="records[{{ $student->id }}][student_id]" value="{{ $student->id }}" />
                                    </td>
                                    <td>
                                        <select name="records[{{ $student->id }}][status]" class="field" required>
                                            @foreach ($statuses as $value => $label)
                                                <option value="{{ $value }}" @selected(old("records.{$student->id}.status", $record?->status ?? 'present') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error("records.{$student->id}.status") <p class="field-error">{{ $message }}</p> @enderror
                                    </td>
                                    <td>
                                        <input type="text" name="records[{{ $student->id }}][note]" class="field" placeholder="Keterangan (opsional)"
                                               value="{{ old("records.{$student->id}.note", $record?->note) }}" />
                                        @error("records.{$student->id}.note") <p class="field-error">{{ $message }}</p> @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-line px-5 py-4">
                    <p class="text-xs text-ink-soft">Data presensi disimpan per siswa, kelas, dan tanggal.</p>
                    <button type="submit" class="btn btn-primary">
                        <x-icon name="check" class="size-4" /> Simpan presensi
                    </button>
                </div>
            </section>
        </form>
    @endif
</x-layouts.portal>