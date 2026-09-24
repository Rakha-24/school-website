<x-layouts.portal :title="$schedule ? 'Edit Jadwal' : 'Tambah Jadwal'" :role="'admin'">
    <x-page-header :title="$schedule ? 'Edit jadwal' : 'Tambah jadwal'"
        :description="'Atur sesi mengajar: kelas, mapel, guru, hari, dan jam.'"
        :back="route('portal.admin.schedules.index')" />

    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold">Mohon periksa kembali isian formulir:</p>
            <ul class="mt-1.5 list-disc space-y-0.5 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $schedule ? route('portal.admin.schedules.update', $schedule) : route('portal.admin.schedules.store') }}" class="panel panel-pad max-w-3xl space-y-6">
        @csrf
        @if ($schedule) @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="class_id" class="label">Kelas</label>
                <select id="class_id" name="class_id" class="field" required>
                    <option value="" disabled @selected(!old('class_id', $schedule?->class_id))>— Pilih kelas —</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(old('class_id', $schedule?->class_id) == $class->id)>{{ $class->displayName() }}</option>
                    @endforeach
                </select>
                @error('class_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="subject_id" class="label">Mata pelajaran</label>
                <select id="subject_id" name="subject_id" class="field" required>
                    <option value="" disabled @selected(!old('subject_id', $schedule?->subject_id))>— Pilih mapel —</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('subject_id', $schedule?->subject_id) == $subject->id)>
                            {{ $subject->code ? $subject->code.' — ' : '' }}{{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="teacher_id" class="label">Guru pengajar</label>
                <select id="teacher_id" name="teacher_id" class="field" required>
                    <option value="" disabled @selected(!old('teacher_id', $schedule?->teacher_id))>— Pilih guru —</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(old('teacher_id', $schedule?->teacher_id) == $teacher->id)>{{ $teacher->user->name }}</option>
                    @endforeach
                </select>
                @error('teacher_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="day" class="label">Hari</label>
                <select id="day" name="day" class="field" required>
                    @foreach ($days as $value => $label)
                        <option value="{{ $value }}" @selected((int) old('day', $schedule?->day ?? 1) === (int) $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('day') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="start_time" class="label">Jam mulai</label>
                <input id="start_time" type="time" name="start_time" class="field"
                       value="{{ old('start_time', $schedule?->start_time ? \Illuminate\Support\Str::substr($schedule->start_time, 0, 5) : '') }}" required />
                @error('start_time') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="end_time" class="label">Jam selesai</label>
                <input id="end_time" type="time" name="end_time" class="field"
                       value="{{ old('end_time', $schedule?->end_time ? \Illuminate\Support\Str::substr($schedule->end_time, 0, 5) : '') }}" required />
                @error('end_time') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="room" class="label">Ruang</label>
                <input id="room" type="text" name="room" class="field" placeholder="Bengkel Alat Berat"
                       value="{{ old('room', $schedule?->room) }}" />
                @error('room') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-2 border-t border-line pt-5">
            <button type="submit" class="btn btn-primary">{{ $schedule ? 'Simpan perubahan' : 'Tambah jadwal' }}</button>
            <a href="{{ route('portal.admin.schedules.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</x-layouts.portal>