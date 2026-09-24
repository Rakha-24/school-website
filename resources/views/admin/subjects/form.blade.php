<x-layouts.portal :title="$subject ? 'Edit Mapel' : 'Tambah Mapel'" :role="'admin'">
    <x-page-header :title="$subject ? 'Edit mata pelajaran' : 'Tambah mata pelajaran'"
        :description="$subject ? 'Perbarui nama, kode, dan deskripsi mapel.' : 'Tambahkan katalog mata pelajaran baru.'"
        :back="route('portal.admin.subjects.index')" />

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

    <form method="POST" action="{{ $subject ? route('portal.admin.subjects.update', $subject) : route('portal.admin.subjects.store') }}" class="panel panel-pad max-w-2xl space-y-6">
        @csrf
        @if ($subject) @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="label">Nama mata pelajaran</label>
                <input id="name" type="text" name="name" class="field" placeholder="Pemrograman Web"
                       value="{{ old('name', $subject?->name) }}" required />
                @error('name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="code" class="label">Kode</label>
                <input id="code" type="text" name="code" class="field" placeholder="PW"
                       value="{{ old('code', $subject?->code) }}" />
                @error('code') <p class="field-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="description" class="label">Deskripsi</label>
            <textarea id="description" name="description" rows="5" class="field" placeholder="Penjelasan singkat mata pelajaran...">{{ old('description', $subject?->description) }}</textarea>
            @error('description') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-2 border-t border-line pt-5">
            <button type="submit" class="btn btn-primary">{{ $subject ? 'Simpan perubahan' : 'Buat mapel' }}</button>
            <a href="{{ route('portal.admin.subjects.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</x-layouts.portal>