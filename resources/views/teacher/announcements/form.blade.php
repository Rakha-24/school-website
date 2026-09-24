@php($isEdit = $announcement !== null)
<x-layouts.portal :title="'Buat Pengumuman'" :role="'teacher'">
    <x-page-header title="Buat pengumuman" description="Bagikan informasi kepada siswa maupun sesama guru."
        :back="route('portal.teacher.announcements.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ route('portal.teacher.announcements.store') }}">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Isi pengumuman</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="title" class="label">Judul</label>
                            <input id="title" type="text" name="title" class="field" value="{{ old('title', $announcement?->title) }}" placeholder="cth. Pengumuman Kegiatan Tengah Semester">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="content" class="label">Isi pengumuman</label>
                            <textarea id="content" name="content" rows="12" class="field" placeholder="Tulis informasi yang ingin disampaikan...">{{ old('content', $announcement?->content) }}</textarea>
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
                            <label for="audience" class="label">Ditujukan untuk</label>
                            <select id="audience" name="audience" class="field">
                                @foreach ($audiences as $key => $label)
                                    <option value="{{ $key }}" @selected(old('audience', $announcement?->audience ?? 'students') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('audience') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="status" class="label">Status</label>
                            <select id="status" name="status" class="field">
                                <option value="draft" @selected(old('status', $announcement?->status ?? 'draft') === 'draft')>Draf</option>
                                <option value="published" @selected(old('status', $announcement?->status) === 'published')>Terbitkan</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            <p class="mt-2 text-xs text-ink-soft">Pengumuman yang terbit akan langsung diberitahukan ke penerima.</p>
                        </div>
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">Simpan pengumuman</button>
                    <a href="{{ route('portal.teacher.announcements.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>