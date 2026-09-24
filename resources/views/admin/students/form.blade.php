<x-layouts.portal :title="$student ? 'Edit Siswa' : 'Tambah Siswa'" :role="'admin'">
    <x-page-header :title="$student ? 'Edit data siswa' : 'Tambah siswa'"
        :description="$student ? 'Perbarui profil, rombel, dan status siswa.' : 'Buat akun dan data siswa sekaligus.'"
        :back="route('portal.admin.students.index')" />

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

    <form method="POST" action="{{ $student ? route('portal.admin.students.update', $student) : route('portal.admin.students.store') }}" enctype="multipart/form-data" class="panel panel-pad max-w-3xl space-y-6">
        @csrf
        @if ($student) @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="label">Nama lengkap</label>
                <input id="name" type="text" name="name" class="field" placeholder="Nama siswa"
                       value="{{ old('name', $student?->user->name) }}" required />
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="label">Email</label>
                <input id="email" type="email" name="email" class="field" placeholder="nama@contoh.sch.id"
                       value="{{ old('email', $student?->user->email) }}" required />
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="student_number" class="label">NIS</label>
                <input id="student_number" type="text" name="student_number" class="field"
                       value="{{ old('student_number', $student?->student_number) }}" required />
                @error('student_number') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="national_student_number" class="label">NISN</label>
                <input id="national_student_number" type="text" name="national_student_number" class="field"
                       value="{{ old('national_student_number', $student?->national_student_number) }}" />
                @error('national_student_number') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="class_id" class="label">Kelas</label>
                <select id="class_id" name="class_id" class="field" required>
                    <option value="" disabled @selected(!old('class_id', $student?->class_id))>— Pilih kelas —</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(old('class_id', $student?->class_id) == $class->id)>{{ $class->displayName() }}</option>
                    @endforeach
                </select>
                @error('class_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="enrollment_year" class="label">Tahun masuk</label>
                <input id="enrollment_year" type="number" name="enrollment_year" class="field" min="1990" max="2100"
                       value="{{ old('enrollment_year', $student?->enrollment_year ?? now()->year) }}" required />
                @error('enrollment_year') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="label">Status</label>
                <select id="status" name="status" class="field" required>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $student?->status ?? 'active') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="photo" class="label">Foto</label>
                <input id="photo" type="file" name="photo" class="field" accept="image/jpeg,image/png,image/webp" />
                @if ($student?->photo)
                    <p class="mt-1.5 text-xs text-ink-soft">Foto saat ini: {{ basename($student->photo) }}. Unggah untuk menggantinya.</p>
                @endif
                @error('photo') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-4 border-t border-line pt-5 sm:grid-cols-2">
            <div>
                <label for="password" class="label">Kata sandi</label>
                <input id="password" type="password" name="password" class="field" autocomplete="new-password"
                       placeholder="{{ $student ? 'Biarkan kosong jika tidak diganti' : 'Minimal 8 karakter' }}" />
                <p class="mt-1.5 text-xs text-ink-soft">
                    {{ $student ? 'Kosongkan untuk mempertahankan kata sandi saat ini.' : 'Kosongkan untuk memakai kata sandi bawaan: password123' }}
                </p>
                @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="label">Ulangi kata sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="field" autocomplete="new-password" />
                @error('password_confirmation') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-2 border-t border-line pt-5">
            <button type="submit" class="btn btn-primary">{{ $student ? 'Simpan perubahan' : 'Tambah siswa' }}</button>
            <a href="{{ route('portal.admin.students.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</x-layouts.portal>