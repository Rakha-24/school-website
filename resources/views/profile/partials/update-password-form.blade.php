<section class="panel panel-pad">
    <header>
        <h2 class="font-display text-lg font-semibold text-brand-950">Perbarui Kata Sandi</h2>
        <p class="mt-1 text-sm text-ink-soft">Pastikan akun Anda menggunakan kata sandi panjang dan acak agar tetap aman.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="label">Kata sandi saat ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="field" autocomplete="current-password">
            @error('current_password', 'updatePassword') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password" class="label">Kata sandi baru</label>
            <input id="update_password_password" name="password" type="password" class="field" autocomplete="new-password">
            @error('password', 'updatePassword') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="label">Konfirmasi kata sandi</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="field" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-700">
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>