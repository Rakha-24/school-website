<section class="panel panel-pad">
    <header>
        <h2 class="font-display text-lg font-semibold text-brand-950">Informasi Profil</h2>
        <p class="mt-1 text-sm text-ink-soft">Perbarui informasi nama dan alamat email akun Anda.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('portal.profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="label">Nama</label>
            <input id="name" name="name" type="text" class="field" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="label">Email</label>
            <input id="email" name="email" type="email" class="field" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-lg border border-accent-200 bg-accent-50 px-4 py-3 text-sm text-accent-800">
                    Alamat email belum terverifikasi.

                    <button form="send-verification" class="ml-2 font-semibold underline underline-offset-2 hover:text-accent-700">
                        Kirim ulang email verifikasi
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-emerald-700">Tautan verifikasi baru telah dikirim ke email Anda.</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-700">
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>