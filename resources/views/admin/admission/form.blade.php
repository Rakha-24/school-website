@php($isEdit = $item !== null)
<x-layouts.portal :title="$isEdit ? 'Edit Konten PPDB' : 'Tambah Konten PPDB'" :role="'admin'">
    <x-page-header :title="$isEdit ? 'Edit konten PPDB' : 'Tambah konten PPDB'"
        description="Tambahkan informasi penerimaan peserta didik baru, misalnya syarat atau jadwal."
        :back="route('portal.admin.admission.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('portal.admin.admission.update', $item) : route('portal.admin.admission.store') }}">
        @csrf
        @if ($isEdit) @method('PATCH') @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Konten</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="title" class="label">Judul</label>
                            <input id="title" type="text" name="title" class="field" value="{{ old('title', $item?->title) }}" placeholder="cth. Syarat pendaftaran jalur prestasi">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="type" class="label">Tipe</label>
                                <select id="type" name="type" class="field">
                                    @foreach ($types as $key => $label)
                                        <option value="{{ $key }}" @selected(old('type', $item?->type) === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="sort_order" class="label">Urutan tampil</label>
                                <input id="sort_order" type="number" name="sort_order" class="field" min="0" value="{{ old('sort_order', $item?->sort_order ?? 0) }}">
                                @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="body" class="label">Isi konten</label>
                            <textarea id="body" name="body" rows="10" class="field" placeholder="Tulis isi konten pendaftaran...">{{ old('body', $item?->body) }}</textarea>
                            @error('body') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Status</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="status" class="label">Status</label>
                            <select id="status" name="status" class="field">
                                <option value="published" @selected(old('status', $item?->status ?? 'published') === 'published')>Tampil</option>
                                <option value="hidden" @selected(old('status', $item?->status) === 'hidden')>Disembunyikan</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">{{ $isEdit ? 'Simpan perubahan' : 'Simpan konten' }}</button>
                    <a href="{{ route('portal.admin.admission.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>