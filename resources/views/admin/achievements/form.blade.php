@php($isEdit = $achievement !== null)
<x-layouts.portal :title="$isEdit ? 'Edit Prestasi' : 'Tambah Prestasi'" :role="'admin'">
    <x-page-header :title="$isEdit ? 'Edit prestasi' : 'Tambah prestasi'"
        description="Catat pencapaian siswa atau sekolah beserta tingkat kompetisinya."
        :back="route('portal.admin.achievements.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('portal.admin.achievements.update', $achievement) : route('portal.admin.achievements.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($isEdit) @method('PATCH') @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Informasi prestasi</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="title" class="label">Judul prestasi</label>
                            <input id="title" type="text" name="title" class="field" value="{{ old('title', $achievement?->title) }}" placeholder="cth. Juara 1 Lomba Robotik Nasional">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="slug" class="label">Slug <span class="font-normal text-ink-soft">(opsional)</span></label>
                                <input id="slug" type="text" name="slug" class="field" value="{{ old('slug', $achievement?->slug) }}" placeholder="otomatis dari judul">
                                @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="year" class="label">Tahun</label>
                                <input id="year" type="number" name="year" class="field" min="2000" max="2100" value="{{ old('year', $achievement?->year ?? now()->year) }}">
                                @error('year') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="level" class="label">Tingkat</label>
                                <select id="level" name="level" class="field">
                                    @foreach ($levels as $key => $label)
                                        <option value="{{ $key }}" @selected(old('level', $achievement?->level) === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('level') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="category" class="label">Kategori</label>
                                <input id="category" type="text" name="category" class="field" value="{{ old('category', $achievement?->category) }}" placeholder="cth. Sains, Seni, Olahraga">
                                @error('category') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="participant" class="label">Nama peserta <span class="font-normal text-ink-soft">(opsional)</span></label>
                            <input id="participant" type="text" name="participant" class="field" value="{{ old('participant', $achievement?->participant) }}" placeholder="cth. Ahmad Fauzi (XII RPL 1)">
                            @error('participant') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="description" class="label">Deskripsi <span class="font-normal text-ink-soft">(opsional)</span></label>
                            <textarea id="description" name="description" rows="5" class="field" placeholder="Cerita singkat di balik prestasi ini">{{ old('description', $achievement?->description) }}</textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
                                <option value="draft" @selected(old('status', $achievement?->status ?? 'draft') === 'draft')>Draf</option>
                                <option value="published" @selected(old('status', $achievement?->status) === 'published')>Terbit</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="published_at" class="label">Tanggal publikasi <span class="font-normal text-ink-soft">(opsional)</span></label>
                            <input id="published_at" type="datetime-local" name="published_at" class="field" value="{{ old('published_at', $achievement?->published_at?->format('Y-m-d\TH:i')) }}">
                            @error('published_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Foto pendukung</h2>
                    <div class="mt-5">
                        @if ($achievement?->image)
                            <img src="{{ asset('storage/'.$achievement->image) }}" alt="Pratinjau foto prestasi" class="mb-3 aspect-[4/3] w-full rounded-lg border border-line object-cover">
                        @endif
                        <label for="image" class="label">Ganti file <span class="font-normal text-ink-soft">(jpeg/png/webp, maks. 6MB)</span></label>
                        <input id="image" type="file" name="image" class="field" accept="image/jpeg,image/png,image/webp">
                        @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">{{ $isEdit ? 'Simpan perubahan' : 'Simpan prestasi' }}</button>
                    <a href="{{ route('portal.admin.achievements.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>