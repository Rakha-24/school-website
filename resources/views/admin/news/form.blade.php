@php($isEdit = $news !== null)
<x-layouts.portal :title="$isEdit ? 'Edit Berita' : 'Tulis Berita'" :role="'admin'">
    <x-page-header :title="$isEdit ? 'Edit berita' : 'Tulis berita'"
        description="Tulis berita, kegiatan, atau pengumuman untuk halaman publik sekolah."
        :back="route('portal.admin.news.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('portal.admin.news.update', $news) : route('portal.admin.news.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($isEdit) @method('PATCH') @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Konten berita</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="title" class="label">Judul</label>
                            <input id="title" type="text" name="title" class="field" value="{{ old('title', $news?->title) }}" placeholder="cth. Siswa SMK Taruna Sains Kediri juara lomba robotik nasional">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="slug" class="label">Slug <span class="font-normal text-ink-soft">(opsional)</span></label>
                                <input id="slug" type="text" name="slug" class="field" value="{{ old('slug', $news?->slug) }}" placeholder="otomatis dari judul">
                                @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="category" class="label">Kategori</label>
                                <select id="category" name="category" class="field">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}" @selected(old('category', $news?->category) === $category)>{{ $category }}</option>
                                    @endforeach
                                </select>
                                @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="excerpt" class="label">Ringkasan <span class="font-normal text-ink-soft">(opsional)</span></label>
                            <textarea id="excerpt" name="excerpt" rows="2" class="field" placeholder="Ringkasan singkat yang tampil di kartu berita">{{ old('excerpt', $news?->excerpt) }}</textarea>
                            @error('excerpt') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="content" class="label">Isi berita</label>
                            <textarea id="content" name="content" rows="14" class="field" placeholder="Tulis isi berita lengkap...">{{ old('content', $news?->content) }}</textarea>
                            @error('content') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Publikasi</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="status" class="label">Status</label>
                            <select id="status" name="status" class="field">
                                <option value="draft" @selected(old('status', $news?->status ?? 'draft') === 'draft')>Draf</option>
                                <option value="published" @selected(old('status', $news?->status) === 'published')>Terbit</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="published_at" class="label">Tanggal publikasi <span class="font-normal text-ink-soft">(opsional)</span></label>
                            <input id="published_at" type="datetime-local" name="published_at" class="field" value="{{ old('published_at', $news?->published_at?->format('Y-m-d\TH:i')) }}">
                            @error('published_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Gambar sampul</h2>
                    <div class="mt-5">
                        @if ($news?->cover_image)
                            <img src="{{ asset('storage/'.$news->cover_image) }}" alt="Pratinjau sampul" class="mb-3 aspect-video w-full rounded-lg border border-line object-cover">
                        @endif
                        <label for="cover_image" class="label">Ganti file <span class="font-normal text-ink-soft">(jpeg/png/webp, maks. 6MB)</span></label>
                        <input id="cover_image" type="file" name="cover_image" class="field" accept="image/jpeg,image/png,image/webp">
                        @error('cover_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">{{ $isEdit ? 'Simpan perubahan' : 'Terbitkan berita' }}</button>
                    <a href="{{ route('portal.admin.news.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>