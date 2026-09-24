<x-layouts.portal :title="$user ? 'Edit Pengguna' : 'Tambah Pengguna'" :role="'admin'">
    <x-page-header :title="$user ? 'Edit pengguna' : 'Tambah pengguna'"
        :description="$user ? 'Perbarui data akun dan hak akses pengguna.' : 'Buat akun baru untuk administrator, guru, atau siswa.'"
        :back="route('portal.admin.users.index')" />

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

    <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ $user ? route('portal.admin.users.update', $user) : route('portal.admin.users.store') }}" class="panel panel-pad lg:col-span-2">
            @csrf
            @if ($user) @method('PUT') @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="label">Nama lengkap</label>
                    <input id="name" type="text" name="name" class="field" placeholder="Nama pengguna"
                           value="{{ old('name', $user?->name) }}" required />
                    @error('name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" type="email" name="email" class="field" placeholder="nama@contoh.sch.id"
                           value="{{ old('email', $user?->email) }}" required />
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="label">Peran</label>
                    <select id="role" name="role" class="field" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected(old('role', $user?->role ?? 'student') === $role)>
                                {{ match ($role) { 'admin' => 'Administrator', 'teacher' => 'Guru', default => 'Siswa' } }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-ink-soft">Mengubah peran memengaruhi akses ke portal.</p>
                    @error('role') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="label">Status</label>
                    <select id="status" name="status" class="field">
                        <option value="active" @selected(old('status', 'active') === 'active')>Aktif</option>
                        <option value="inactive" @selected(old('status', 'active') === 'inactive')>Nonaktif</option>
                    </select>
                    @if ($user)
                        <p class="mt-1.5 text-xs text-ink-soft">Menandai Nonaktif akan memutus sesi pengguna.</p>
                    @endif
                    @error('status') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="label">Kata sandi</label>
                    <input id="password" type="password" name="password" class="field" autocomplete="new-password"
                           placeholder="{{ $user ? 'Biarkan kosong jika tidak diganti' : 'Minimal 8 karakter' }}" />
                    <p class="mt-1.5 text-xs text-ink-soft">
                        {{ $user ? 'Kosongkan untuk mempertahankan kata sandi saat ini.' : 'Kosongkan untuk memakai kata sandi bawaan: password123' }}
                    </p>
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">Ulangi kata sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="field" autocomplete="new-password" />
                    @error('password_confirmation') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center gap-2 border-t border-line pt-5">
                <button type="submit" class="btn btn-primary">{{ $user ? 'Simpan perubahan' : 'Buat pengguna' }}</button>
                <a href="{{ route('portal.admin.users.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>

        <aside class="panel panel-pad h-fit">
            <p class="eyebrow">Catatan</p>
            <ul class="mt-3 space-y-3 text-sm text-ink-soft">
                <li>Siswa tanpa data siswa akan otomatis dibuatkan profil dengan NIS sementara.</li>
                <li>Guru juga otomatis mendapat profil guru aktif.</li>
            </ul>
        </aside>
    </div>
</x-layouts.portal>