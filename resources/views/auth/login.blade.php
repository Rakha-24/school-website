<x-guest-layout title="Masuk ke Portal" intro="Gunakan akun yang diterbitkan oleh sekolah. Email dan kata sandi diperoleh dari admin sekolah.">

    @if (session('status'))
        <div class="mb-5 flex items-start gap-2.5 rounded-md border border-accent-200 bg-accent-50 px-4 py-3 text-sm text-accent-800">
            <x-icon name="check-circle" class="mt-0.5 size-4" />
            {{ session('status') }}
        </div>
    @endif

    @php
        $demo = [
            ['role' => 'Admin', 'mail' => 'admin@smktarunasainskediri.com', 'pass' => 'password123'],
            ['role' => 'Guru',  'mail' => 'siti.rahayu@smktarunasainskediri.com', 'pass' => 'password123'],
            ['role' => 'Siswa', 'mail' => 'arya.pratama@smktarunasainskediri.com', 'pass' => 'password123'],
        ];
    @endphp

    <div class="mb-6 rounded-panel border border-line bg-paper-50/70 p-4">
        <div class="mb-3 flex items-center gap-2">
            <x-icon name="sparkles" class="size-4 text-accent-600" />
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-ink-500">Akun Demo</p>
        </div>
        <p class="mb-3 text-xs leading-relaxed text-ink-faint">
            Pilih salah satu akun untuk masuk langsung ke portal sekolah. Semua akun memakai kata sandi <strong class="font-semibold text-ink-700">password123</strong>.
        </p>
        <div class="space-y-2.5">
            @foreach ($demo as $account)
                <form method="POST" action="{{ route('login') }}"
                      class="flex items-center justify-between gap-3 rounded-md border border-line bg-white/60 px-3 py-2 transition-colors hover:border-line-strong">
                    @csrf
                    <input type="hidden" name="email" value="{{ $account['mail'] }}">
                    <input type="hidden" name="password" value="{{ $account['pass'] }}">
                    <input type="hidden" name="remember" value="on">
                    <div class="min-w-0">
                        <p class="flex items-center gap-2 text-sm font-semibold text-ink-900">
                            {{ $account['role'] }}
                            <span class="badge {{ match($account['role']) {
                                'Admin' => 'badge-accent',
                                'Guru'  => 'badge-green',
                                'Siswa' => 'badge-brand',
                            } }}">{{ $account['role'] }}</span>
                        </p>
                        <p class="truncate text-xs text-ink-soft">{{ $account['mail'] }}</p>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm shrink-0">Masuk</button>
                </form>
            @endforeach
        </div>
    </div>

    <div class="relative my-5 flex items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-ink-faint">
        <span class="h-px flex-1 bg-line"></span>
        atau masuk dengan akun Anda
        <span class="h-px flex-1 bg-line"></span>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@smktarunasainskediri.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" value="Kata Sandi" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-brand-700 underline underline-offset-2 hover:text-accent-700">Lupa kata sandi?</a>
                @endif
            </div>
            <x-text-input id="password" class="mt-1.5 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="flex items-center gap-2 text-sm text-ink-soft">
            <input id="remember_me" type="checkbox" name="remember" class="size-4 rounded border-line bg-white text-accent-600 shadow-sm focus:ring-accent-500">
            Ingat saya di perangkat ini
        </label>

        <button type="submit" class="btn btn-primary w-full justify-center">
            Masuk <x-icon name="arrow-right" class="size-4" />
        </button>

        <p class="text-center text-xs leading-relaxed text-ink-faint">
            Akun dibuat oleh pihak sekolah. Peserta didik yang belum memiliki akun dapat menghubungi wali kelas.
        </p>
    </form>
</x-guest-layout>