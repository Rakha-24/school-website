<x-guest-layout title="Verifikasi Email" intro="Sebelum melanjutkan, verifikasi alamat email Anda menggunakan tautan yang telah dikirimkan.">

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 flex items-start gap-2.5 rounded-md border border-accent-200 bg-accent-50 px-4 py-3 text-sm text-accent-800">
            <x-icon name="check-circle" class="mt-0.5 size-4" />
            Tautan verifikasi baru telah dikirim ke email Anda.
        </div>
    @endif

    <div class="mt-4 flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-outline w-full justify-center">Kirim Ulang Email Verifikasi</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn w-full justify-center text-ink-soft hover:text-brand-950">Keluar</button>
        </form>
    </div>
</x-guest-layout>