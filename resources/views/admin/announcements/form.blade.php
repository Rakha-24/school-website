@php($isEdit = $announcement !== null)
<x-layouts.portal :title="$isEdit ? 'Edit Pengumuman' : 'Buat Pengumuman'" :role="'admin'">
    <x-page-header :title="$isEdit ? 'Edit pengumuman' : 'Buat pengumuman'"
        description="Tulis informasi yang akan diterima oleh siswa dan guru sesuai audiensi."
        :back="route('portal.admin.announcements.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('portal.admin.announcements.update', $announcement) : route('portal.admin.announcements.store') }}">
        @csrf
        @if ($isEdit) @method('PATCH') @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Informasi pengumuman</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="title" class="label">Judul</label>
                            <input id="title" type="text" name="title" class="field" value="{{ old('title', $announcement?->title) }}" placeholder="cth. Pembagian rapor semester ganjil">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="content" class="label">Isi pengumuman</label>
                            <textarea id="content" name="content" rows="8" class="field" placeholder="Tulis detail pengumuman...">{{ old('content', $announcement?->content) }}</textarea>
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
                            <label for="audience" class="label">Audiensi</label>
                            <select id="audience" name="audience" class="field">
                                @foreach ($audiences as $key => $label)
                                    <option value="{{ $key }}" @selected(old('audience', $announcement?->audience) === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('audience') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="status" class="label">Status</label>
                            <select id="status" name="status" class="field">
                                <option value="draft" @selected(old('status', $announcement?->status ?? 'draft') === 'draft')>Draf</option>
                                <option value="published" @selected(old('status', $announcement?->status) === 'published')>Terbit</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="published_at" class="label">Tanggal publikasi <span class="font-normal text-ink-soft">(opsional)</span></label>
                            <input id="published_at" type="datetime-local" name="published_at" class="field" value="{{ old('published_at', $announcement?->published_at?->format('Y-m-d\TH:i')) }}">
                            @error('published_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">{{ $isEdit ? 'Simpan perubahan' : 'Simpan pengumuman' }}</button>
                    <a href="{{ route('portal.admin.announcements.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>