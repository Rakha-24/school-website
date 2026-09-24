@php($isEdit = $extracurricular !== null)
<x-layouts.portal :title="$isEdit ? 'Edit Ekstrakurikuler' : 'Tambah Ekstrakurikuler'" :role="'admin'">
    <x-page-header :title="$isEdit ? 'Edit ekstrakurikuler' : 'Tambah ekstrakurikuler'"
        description="Lengkapi detail kegiatan ekstrakurikuler untuk halaman publik."
        :back="route('portal.admin.extracurriculars.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('portal.admin.extracurriculars.update', $extracurricular) : route('portal.admin.extracurriculars.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($isEdit) @method('PATCH') @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Detail kegiatan</h2>
                    <div class="mt-5 space-y-5">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="label">Nama kegiatan</label>
                                <input id="name" type="text" name="name" class="field" value="{{ old('name', $extracurricular?->name) }}" placeholder="cth. Pramuka">
                                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="slug" class="label">Slug <span class="font-normal text-ink-soft">(opsional)</span></label>
                                <input id="slug" type="text" name="slug" class="field" value="{{ old('slug', $extracurricular?->slug) }}" placeholder="otomatis dari nama">
                                @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div>
                            <label for="description" class="label">Deskripsi</label>
                            <textarea id="description" name="description" rows="6" class="field" placeholder="Tulis deskripsi dan kegiatan yang dilakukan">{{ old('description', $extracurricular?->description) }}</textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="schedule" class="label">Jadwal latihan <span class="font-normal text-ink-soft">(opsional)</span></label>
                                <input id="schedule" type="text" name="schedule" class="field" value="{{ old('schedule', $extracurricular?->schedule) }}" placeholder="cth. Jumat, 15.00–17.00">
                                @error('schedule') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="leader" class="label">Pembina / ketua <span class="font-normal text-ink-soft">(opsional)</span></label>
                                <input id="leader" type="text" name="leader" class="field" value="{{ old('leader', $extracurricular?->leader) }}" placeholder="cth. Bapak/Ibu ...">
                                @error('leader') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
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
                                <option value="active" @selected(old('status', $extracurricular?->status ?? 'active') === 'active')>Aktif</option>
                                <option value="inactive" @selected(old('status', $extracurricular?->status) === 'inactive')>Nonaktif</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Foto</h2>
                    <div class="mt-5">
                        @if ($extracurricular?->photo)
                            <img src="{{ asset('storage/'.$extracurricular->photo) }}" alt="Pratinjau foto ekstrakurikuler" class="mb-3 aspect-[4/3] w-full rounded-lg border border-line object-cover">
                        @endif
                        <label for="photo" class="label">Ganti file <span class="font-normal text-ink-soft">(jpeg/png/webp, maks. 6MB)</span></label>
                        <input id="photo" type="file" name="photo" class="field" accept="image/jpeg,image/png,image/webp">
                        @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">{{ $isEdit ? 'Simpan perubahan' : 'Simpan kegiatan' }}</button>
                    <a href="{{ route('portal.admin.extracurriculars.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>