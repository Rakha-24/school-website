<x-layouts.portal :title="$class ? 'Edit Kelas' : 'Tambah Kelas'" :role="'admin'">
    <x-page-header :title="$class ? 'Edit data kelas' : 'Tambah kelas'"
        :description="$class ? 'Perbarui rombongan belajar, wali kelas, dan status.' : 'Buat rombongan belajar atau wali kelas baru.'"
        :back="route('portal.admin.classes.index')" />

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

    <form method="POST" action="{{ $class ? route('portal.admin.classes.update', $class) : route('portal.admin.classes.store') }}" class="panel panel-pad max-w-3xl space-y-6">
        @csrf
        @if ($class) @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="label">Nama rombel</label>
                <input id="name" type="text" name="name" class="field" placeholder="X-TAB 1"
                       value="{{ old('name', $class?->name) }}" required />
                @error('name') <p class="field-error mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="grade" class="label">Tingkat</label>
                <input id="grade" type="number" name="grade" class="field" min="1" max="13"
                       value="{{ old('grade', $class?->grade) }}" required />
                @error('grade') <p class="field-error mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="academic_year" class="label">Tahun ajaran</label>
                <input id="academic_year" type="text" name="academic_year" class="field" placeholder="2026/2027"
                       value="{{ old('academic_year', $class?->academic_year) }}" required />
                <p class="mt-1.5 text-xs text-ink-soft">Format: tahun/tahun, contoh 2026/2027.</p>
                @error('academic_year') <p class="field-error mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="homeroom_teacher_id" class="label">Wali kelas</label>
                <select id="homeroom_teacher_id" name="homeroom_teacher_id" class="field">
                    <option value="" disabled @selected(!old('homeroom_teacher_id', $class?->homeroom_teacher_id))>— Pilih wali kelas —</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id', $class?->homeroom_teacher_id) == $teacher->id)>{{ $teacher->user->name }}</option>
                    @endforeach
                </select>
                @error('homeroom_teacher_id') <p class="field-error mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="label">Status</label>
                <select id="status" name="status" class="field" required>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $class?->status ?? 'active') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="mt-1.5 text-xs text-ink-soft">Arsipkan kelas yang tidak lagi dipakai.</p>
                @error('status') <p class="field-error mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-2 border-t border-line pt-5">
            <button type="submit" class="btn btn-primary">{{ $class ? 'Simpan perubahan' : 'Buat kelas' }}</button>
            <a href="{{ route('portal.admin.classes.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</x-layouts.portal>