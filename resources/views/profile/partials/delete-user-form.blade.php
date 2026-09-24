<section class="panel panel-pad">
    <header>
        <h2 class="font-display text-lg font-semibold text-rose-800">Hapus Akun</h2>
        <p class="mt-1 text-sm text-ink-soft">Setelah akun dihapus, seluruh data dan sumber dayanya akan terhapus permanen. Unduh informasi yang ingin Anda simpan sebelum menghapus akun.</p>
    </header>

    <button type="button" class="btn btn-danger mt-5"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        Hapus Akun
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('portal.profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Anda yakin ingin menghapus akun ini?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Setelah akun dihapus, seluruh data akan terhapus permanen. Masukkan kata sandi Anda untuk mengonfirmasi.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Kata sandi</label>
                <input id="password" name="password" type="password" class="field mt-1 block w-3/4" placeholder="Kata sandi">

                @error('password', 'userDeletion') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" x-on:click="$dispatch('close')" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-danger ms-3 ml-3">Hapus Akun</button>
            </div>
        </form>
    </x-modal>
</section>