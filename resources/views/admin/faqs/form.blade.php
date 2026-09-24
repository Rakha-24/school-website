@php($isEdit = $faq !== null)
<x-layouts.portal :title="$isEdit ? 'Edit FAQ' : 'Tambah FAQ'" :role="'admin'">
    <x-page-header :title="$isEdit ? 'Edit FAQ' : 'Tambah FAQ'"
        description="Tulis pertanyaan dan jawaban untuk pengunjung halaman publik."
        :back="route('portal.admin.faqs.index')" />

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            Ada kesalahan pada isian formulir. Periksa kembali kolom yang ditandai.
        </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route('portal.admin.faqs.update', $faq) : route('portal.admin.faqs.store') }}">
        @csrf
        @if ($isEdit) @method('PATCH') @endif

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Pertanyaan & jawaban</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="question" class="label">Pertanyaan</label>
                            <input id="question" type="text" name="question" class="field" value="{{ old('question', $faq?->question) }}" placeholder="cth. Bagaimana cara mendaftar PPDB?">
                            @error('question') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="answer" class="label">Jawaban</label>
                            <textarea id="answer" name="answer" rows="8" class="field" placeholder="Tulis jawaban yang informatif dan jelas">{{ old('answer', $faq?->answer) }}</textarea>
                            @error('answer') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="panel panel-pad">
                    <h2 class="font-display text-lg font-semibold text-brand-950">Pengaturan</h2>
                    <div class="mt-5 space-y-5">
                        <div>
                            <label for="sort_order" class="label">Urutan tampil</label>
                            <input id="sort_order" type="number" name="sort_order" class="field" min="0" value="{{ old('sort_order', $faq?->sort_order ?? 0) }}">
                            @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="status" class="label">Status</label>
                            <select id="status" name="status" class="field">
                                <option value="published" @selected(old('status', $faq?->status ?? 'published') === 'published')>Tampil</option>
                                <option value="hidden" @selected(old('status', $faq?->status) === 'hidden')>Disembunyikan</option>
                            </select>
                            @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary flex-1">{{ $isEdit ? 'Simpan perubahan' : 'Simpan FAQ' }}</button>
                    <a href="{{ route('portal.admin.faqs.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </aside>
        </div>
    </form>
</x-layouts.portal>