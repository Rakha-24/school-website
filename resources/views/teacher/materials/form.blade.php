@php($isEdit = $material !== null)
<x-layouts.portal :title="$isEdit ? 'Edit Materi' : 'Buat Materi'" :role="'teacher'">
    <x-page-header :title="$isEdit ? 'Edit materi' : 'Buat materi'"
        description="Bagikan materi pembelajaran untuk kelas dan mata pelajaran tertentu."
        :back="route('portal.teacher.materials.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('portal.teacher.materials.update', $material) : route('portal.teacher.materials.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Konten materi</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="class_subject_id" class="label">Kelas & mata pelajaran</label>
                            <select id="class_subject_id" name="class_subject_id" class="field">
                                <option value="">— Pilih kelas & mapel —</option>
                                @foreach ($classSubjects as $classSubject)
                                    <option value="{{ $classSubject->id }}" @selected((int) old('class_subject_id', $material?->class_subject_id) === (int) $classSubject->id)>
                                        {{ $classSubject->schoolClass?->name }} — {{ $classSubject->subject?->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_subject_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="title" class="label">Judul materi</label>
                            <input id="title" type="text" name="title" class="field" value="{{ old('title', $material?->title) }}" placeholder="cth. Bab 1: Pengenalan Jaringan Komputer">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="description" class="label">Ringkasan <span class="font-normal text-ink-soft">(opsional)</span></label>
                            <textarea id="description" name="description" rows="3" class="field" placeholder="Ringkasan singkat materi">{{ old('description', $material?->description) }}</textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="content" class="label">Catatan / isi materi <span class="font-normal text-ink-soft">(opsional)</span></label>
                            <textarea id="content" name="content" rows="10" class="field" placeholder="Tulis catatan atau isi materi pembelajaran">{{ old('content', $material?->content) }}</textarea>
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
                                @foreach ($statuses as $key => $label)
                                    <option value="{{ $key }}" @selected(old('status', $material?->status ?? 'draft') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Berkas lampiran</h2>
                    <div class="mt-5">
                        @if ($material?->file_path)
                            <a href="{{ $material->fileUrl() }}" target="_blank" class="mb-3 flex items-center gap-2 rounded-md border border-line bg-paper px-3 py-2 text-sm font-medium text-brand-800 transition-colors hover:border-brand-500">
                                <x-icon name="document" class="size-4" /> Berkas terpasang
                                <x-icon name="arrow-top-right" class="ml-auto size-4" />
                            </a>
                        @endif
                        <label for="file" class="label">Ganti lampiran <span class="font-normal text-ink-soft">(pdf/doc/ppt/xls/txt/zip, maks. 20MB)</span></label>
                        <input id="file" type="file" name="file" class="field">
                        @error('file') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">{{ $isEdit ? 'Simpan perubahan' : 'Simpan materi' }}</button>
                    <a href="{{ route('portal.teacher.materials.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>