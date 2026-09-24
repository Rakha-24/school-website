@php($isEdit = $assignment !== null)
<x-layouts.portal :title="$isEdit ? 'Edit Tugas' : 'Buat Tugas'" :role="'teacher'">
    <x-page-header :title="$isEdit ? 'Edit tugas' : 'Buat tugas'"
        description="Beri tugas kepada siswa lengkap dengan tenggat pengumpulan."
        :back="route('portal.teacher.assignments.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('portal.teacher.assignments.update', $assignment) : route('portal.teacher.assignments.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Detail tugas</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="class_subject_id" class="label">Kelas & mata pelajaran</label>
                            <select id="class_subject_id" name="class_subject_id" class="field">
                                <option value="">— Pilih kelas & mapel —</option>
                                @foreach ($classSubjects as $classSubject)
                                    <option value="{{ $classSubject->id }}" @selected((int) old('class_subject_id', $assignment?->class_subject_id) === (int) $classSubject->id)>
                                        {{ $classSubject->schoolClass?->name }} — {{ $classSubject->subject?->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_subject_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="title" class="label">Judul tugas</label>
                            <input id="title" type="text" name="title" class="field" value="{{ old('title', $assignment?->title) }}" placeholder="cth. Tugas 2: Kerjakan latihan hal. 45">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="due_at" class="label">Tenggat pengumpulan</label>
                            <input id="due_at" type="datetime-local" name="due_at" class="field" value="{{ old('due_at', $assignment?->due_at ? $assignment->due_at->format('Y-m-d\TH:i') : '') }}">
                            @error('due_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="description" class="label">Petunjuk tugas</label>
                            <textarea id="description" name="description" rows="10" class="field" placeholder="Jelaskan petunjuk pengerjaan tugas...">{{ old('description', $assignment?->description) }}</textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Berkas lampiran</h2>
                    <div class="mt-5">
                        @if ($assignment?->attachment)
                            <a href="{{ $assignment->attachmentUrl() }}" target="_blank" class="mb-3 flex items-center gap-2 rounded-md border border-line bg-paper px-3 py-2 text-sm font-medium text-brand-800 transition-colors hover:border-brand-500">
                                <x-icon name="document" class="size-4" /> Berkas terpasang
                                <x-icon name="arrow-top-right" class="ml-auto size-4" />
                            </a>
                        @endif
                        <label for="attachment" class="label">Ganti lampiran <span class="font-normal text-ink-soft">(opsional, maks. 20MB)</span></label>
                        <input id="attachment" type="file" name="attachment" class="field" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip">
                        @error('attachment') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">{{ $isEdit ? 'Simpan perubahan' : 'Simpan tugas' }}</button>
                    <a href="{{ route('portal.teacher.assignments.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>