<x-guest-layout title="Pulihkan Kata Sandi" intro="Masukkan email terdaftar, dan kami akan mengirimkan tautan untuk membuat kata sandi baru.">

    @if (session('status'))
        <div class="mb-5 flex items-start gap-2.5 rounded-md border border-accent-200 bg-accent-50 px-4 py-3 text-sm text-accent-800">
            <x-icon name="check-circle" class="mt-0.5 size-4" />
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@smktarunasainskediri.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="btn btn-primary w-full justify-center">
            Kirim Tautan Pemulihan
        </button>

        <p class="text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-brand-700 underline underline-offset-2 hover:text-accent-700">← Kembali ke halaman masuk</a>
        </p>
    </form>
</x-guest-layout>