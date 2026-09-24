<x-guest-layout title="Konfirmasi Kata Sandi" intro="Ini adalah area aman aplikasi. Konfirmasikan kata sandi Anda sebelum melanjutkan.">

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1.5 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="btn btn-primary w-full justify-center">Konfirmasi</button>
    </form>
</x-guest-layout>