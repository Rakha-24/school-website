<x-layouts.portal :title="'Pengaturan'" :role="'admin'">
    <x-page-header title="Pengaturan sekolah" description="Informasi profil sekolah, PPDB, dan konten utama situs." />

    @if (session('status'))
        <div role="status" class="mb-6 flex items-start gap-2.5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <x-icon name="check-circle" class="mt-0.5 size-4 shrink-0" />
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" class="mb-6 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold">Mohon periksa kembali isian formulir:</p>
            <ul class="mt-1.5 list-disc space-y-0.5 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('portal.admin.settings.update') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-2">
        @csrf
        @method('PUT')

        <section class="panel panel-pad space-y-4">
            <div class="pb-2">
                <p class="eyebrow">Identitas</p>
                <h2 class="mt-1 font-display text-lg font-semibold text-brand-950">Profil sekolah</h2>
            </div>

            <div>
                <label for="logo" class="label">Logo sekolah</label>
                <div class="flex items-center gap-4 rounded-lg border border-line bg-paper-50 p-3.5">
                    <span class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-line bg-white p-1.5">
                        @if ($logo)
                            <img src="{{ asset('storage/'.$logo) }}" alt="Logo sekolah saat ini" class="h-full w-full object-contain">
                        @else
                            <x-logo-mark class="size-10" />
                        @endif
                    </span>
                    <div class="min-w-0 flex-1">
                        <input id="logo" type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml"
                               class="w-full text-sm text-ink-700 file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-brand-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-brand-800 file:transition-colors hover:file:bg-brand-100" />
                        <div class="mt-1.5 flex items-center justify-between gap-2">
                            @error('logo')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                            @if ($logo)
                                <label class="inline-flex cursor-pointer items-center gap-1.5 text-xs text-red-600 hover:text-red-700">
                                    <input type="checkbox" name="logo_remove" value="1" class="size-3.5 rounded border-line-strong text-red-600 focus:ring-red-500/30">
                                    Gunakan logo bawaan (hapus)
                                </label>
                            @endif
                        </div>
                        <p class="mt-1.5 text-xs text-ink-soft">PNG, JPG, WebP, atau SVG — maksimal 2 MB. Kosongkan untuk memakai logo bawaan.</p>
                    </div>
                </div>
            </div>

            <div>
                <label for="school_name" class="label">Nama sekolah</label>
                <input id="school_name" type="text" name="school_name" class="field"
                       value="{{ old('school_name', $settings['school_name'] ?? '') }}" required />
                @error('school_name') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="tagline" class="label">Tagline</label>
                <input id="tagline" type="text" name="tagline" class="field"
                       value="{{ old('tagline', $settings['tagline'] ?? '') }}" />
                @error('tagline') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="address" class="label">Alamat</label>
                <input id="address" type="text" name="address" class="field"
                       value="{{ old('address', $settings['address'] ?? '') }}" required />
                @error('address') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="phone" class="label">Telepon</label>
                    <input id="phone" type="text" name="phone" class="field"
                           value="{{ old('phone', $settings['phone'] ?? '') }}" />
                    @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" type="email" name="email" class="field"
                           value="{{ old('email', $settings['email'] ?? '') }}" />
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <section class="space-y-6">
            <section class="panel panel-pad space-y-4">
                <div class="pb-2">
                    <p class="eyebrow">PPDB &amp; hero</p>
                    <h2 class="mt-1 font-display text-lg font-semibold text-brand-950">Pendaftaran siswa baru</h2>
                </div>

                <div>
                    <label class="flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="ppdb_open" value="1"
                               class="size-4 rounded border-line-strong text-brand-700 focus:ring-brand-600/30"
                               {{ old('ppdb_open', $settings['ppdb_open'] ?? '') === '1' ? 'checked' : '' }} />
                        <span class="text-sm font-semibold text-ink-700">Buka pendaftaran PPDB</span>
                    </label>
                    <p class="mt-1.5 text-xs text-ink-soft">Jika dicentang, tombol "Daftar" akan tampil di situs publik.</p>
                </div>

                <div>
                    <label for="ppdb_year" class="label">Tahun ajaran PPDB</label>
                    <input id="ppdb_year" type="text" name="ppdb_year" class="field" placeholder="2026/2027"
                           value="{{ old('ppdb_year', $settings['ppdb_year'] ?? '') }}" />
                    @error('ppdb_year') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="hero_headline" class="label">Judul utama (hero)</label>
                    <input id="hero_headline" type="text" name="hero_headline" class="field"
                           value="{{ old('hero_headline', $settings['hero_headline'] ?? '') }}" />
                    @error('hero_headline') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </section>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary">
                    <x-icon name="check" class="size-4" /> Simpan pengaturan
                </button>
            </div>
        </section>
    </form>
</x-layouts.portal>