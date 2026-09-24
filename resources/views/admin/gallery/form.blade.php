@php($isEdit = $item !== null)
<x-layouts.portal :title="$isEdit ? 'Edit Foto Galeri' : 'Tambah Foto Galeri'" :role="'admin'">
    <x-page-header :title="$isEdit ? 'Edit foto galeri' : 'Tambah foto galeri'"
        description="Unggah dokumentasi kegiatan sekolah beserta keterangannya."
        :back="route('portal.admin.gallery.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('portal.admin.gallery.update', $item) : route('portal.admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($isEdit) @method('PATCH') @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Detail foto</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="title" class="label">Judul foto</label>
                            <input id="title" type="text" name="title" class="field" value="{{ old('title', $item?->title) }}" placeholder="cth. Peringatan Hari Kemerdekaan 17 Agustus">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="category" class="label">Kategori</label>
                            <select id="category" name="category" class="field">
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" @selected(old('category', $item?->category) === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="description" class="label">Deskripsi <span class="font-normal text-ink-soft">(opsional)</span></label>
                            <textarea id="description" name="description" rows="4" class="field" placeholder="Keterangan singkat foto">{{ old('description', $item?->description) }}</textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
                                <option value="draft" @selected(old('status', $item?->status ?? 'draft') === 'draft')>Draf</option>
                                <option value="published" @selected(old('status', $item?->status) === 'published')>Terbit</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Gambar</h2>
                    <div class="mt-5">
                        @if ($item?->image)
                            <img src="{{ asset('storage/'.$item->image) }}" alt="Pratinjau foto galeri" class="mb-3 aspect-[4/3] w-full rounded-lg border border-line object-cover">
                        @endif
                        <label for="image" class="label">{{ $isEdit ? 'Ganti file' : 'File foto' }} <span class="font-normal text-ink-soft">(wajib, jpeg/png/webp, maks. 8MB)</span></label>
                        <input id="image" type="file" name="image" class="field" accept="image/jpeg,image/png,image/webp">
                        @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">{{ $isEdit ? 'Simpan perubahan' : 'Simpan foto' }}</button>
                    <a href="{{ route('portal.admin.gallery.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>