<x-layouts.portal :title="$teacher ? 'Edit Guru' : 'Tambah Guru'" :role="'admin'">
    <x-page-header :title="$teacher ? 'Edit data guru' : 'Tambah guru'"
        :description="$teacher ? 'Perbarui profil dan status keaktifan guru.' : 'Buat akun dan data guru sekaligus.'"
        :back="route('portal.admin.teachers.index')" />

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

    <form method="POST" action="{{ $teacher ? route('portal.admin.teachers.update', $teacher) : route('portal.admin.teachers.store') }}" enctype="multipart/form-data" class="panel panel-pad max-w-3xl space-y-6">
        @csrf
        @if ($teacher) @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="label">Nama lengkap</label>
                <input id="name" type="text" name="name" class="field" placeholder="Nama guru"
                       value="{{ old('name', $teacher?->user->name) }}" required />
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="label">Email</label>
                <input id="email" type="email" name="email" class="field" placeholder="nama@contoh.sch.id"
                       value="{{ old('email', $teacher?->user->email) }}" required />
                @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="teacher_number" class="label">NIP</label>
                <input id="teacher_number" type="text" name="teacher_number" class="field"
                       value="{{ old('teacher_number', $teacher?->teacher_number) }}" />
                @error('teacher_number') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="label">No. HP</label>
                <input id="phone" type="text" name="phone" class="field"
                       value="{{ old('phone', $teacher?->phone) }}" />
                @error('phone') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="label">Status</label>
                <select id="status" name="status" class="field" required>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $teacher?->status ?? 'active') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('status') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="photo" class="label">Foto</label>
                <input id="photo" type="file" name="photo" class="field" accept="image/jpeg,image/png,image/webp" />
                @if ($teacher?->photo)
                    <p class="mt-1.5 text-xs text-ink-soft">Foto saat ini: {{ basename($teacher->photo) }}. Unggah untuk menggantinya.</p>
                @endif
                @error('photo') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-4 border-t border-line pt-5 sm:grid-cols-2">
            <div>
                <label for="password" class="label">Kata sandi</label>
                <input id="password" type="password" name="password" class="field" autocomplete="new-password"
                       placeholder="{{ $teacher ? 'Biarkan kosong jika tidak diganti' : 'Minimal 8 karakter' }}" />
                <p class="mt-1.5 text-xs text-ink-soft">
                    {{ $teacher ? 'Kosongkan untuk mempertahankan kata sandi saat ini.' : 'Kosongkan untuk memakai kata sandi bawaan: password123' }}
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
            <button type="submit" class="btn btn-primary">{{ $teacher ? 'Simpan perubahan' : 'Tambah guru' }}</button>
            <a href="{{ route('portal.admin.teachers.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</x-layouts.portal>