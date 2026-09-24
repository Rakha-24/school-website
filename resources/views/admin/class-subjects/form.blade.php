<x-layouts.portal :title="$row ? 'Edit Relasi Mapel' : 'Tambah Relasi Mapel'" :role="'admin'">
    <x-page-header :title="$row ? 'Edit relasi kelas–mapel' : 'Tambah relasi kelas–mapel'"
        :description="'Hubungkan rombongan belajar dengan mata pelajaran dan guru pengajar.'"
        :back="route('portal.admin.class-subjects.index')" />

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

    <form method="POST" action="{{ $row ? route('portal.admin.class-subjects.update', $row) : route('portal.admin.class-subjects.store') }}" class="panel panel-pad max-w-3xl space-y-6">
        @csrf
        @if ($row) @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="class_id" class="label">Kelas</label>
                <select id="class_id" name="class_id" class="field" required>
                    <option value="" disabled @selected(!old('class_id', $row?->class_id))>— Pilih kelas —</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(old('class_id', $row?->class_id) == $class->id)>{{ $class->displayName() }}</option>
                    @endforeach
                </select>
                @error('class_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="subject_id" class="label">Mata pelajaran</label>
                <select id="subject_id" name="subject_id" class="field" required>
                    <option value="" disabled @selected(!old('subject_id', $row?->subject_id))>— Pilih mapel —</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(old('subject_id', $row?->subject_id) == $subject->id)>
                            {{ $subject->code ? $subject->code.' — ' : '' }}{{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="teacher_id" class="label">Guru pengajar</label>
                <select id="teacher_id" name="teacher_id" class="field" required>
                    <option value="" disabled @selected(!old('teacher_id', $row?->teacher_id))>— Pilih guru —</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(old('teacher_id', $row?->teacher_id) == $teacher->id)>{{ $teacher->user->name }}</option>
                    @endforeach
                </select>
                @error('teacher_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="hours_per_week" class="label">Jam per minggu</label>
                <input id="hours_per_week" type="number" name="hours_per_week" class="field" min="1" max="40"
                       value="{{ old('hours_per_week', $row?->hours_per_week ?? 2) }}" />
                @error('hours_per_week') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-2 border-t border-line pt-5">
            <button type="submit" class="btn btn-primary">{{ $row ? 'Simpan perubahan' : 'Tambah relasi' }}</button>
            <a href="{{ route('portal.admin.class-subjects.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</x-layouts.portal>