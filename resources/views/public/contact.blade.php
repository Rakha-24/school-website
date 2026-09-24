@php
    $settings = config('school.settings', []);
    $address = $settings['address'] ?? 'Dusun Pule, Purwoasri, Kab. Kediri';
    $phone = $settings['phone'] ?? '(0354) 512345';
    $email = $settings['email'] ?? 'smk.tarunasains@gmail.com';
@endphp

<x-layouts.public title="Hubungi Kami">
    <x-page-hero
        eyebrow="Kontak"
        title="Hubungi kami"
        lede="Punya pertanyaan seputar pendaftaran, program keahlian, atau kegiatan sekolah? Kirim pesan atau datang langsung ke sekolah."
    />

    <section class="section bg-paper">
        <div class="container-site grid gap-10 lg:grid-cols-[1fr_1.4fr]">
            {{-- Informasi kontak --}}
            <div>
                <x-section-heading
                    eyebrow="Informasi Sekolah"
                    title="Kami siap membantu"
                    lede="Pilih kanal yang paling nyaman untuk menghubungi pihak sekolah."
                />

                <div class="space-y-4">
                    <div class="panel flex items-start gap-3.5 p-5">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700 ring-1 ring-brand-100 ring-inset">
                            <x-icon name="map-pin" class="size-5" />
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-brand-950">Alamat</p>
                            <p class="mt-0.5 text-sm leading-relaxed text-ink-soft">{{ $address }}</p>
                        </div>
                    </div>

                    <div class="panel flex items-start gap-3.5 p-5">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700 ring-1 ring-brand-100 ring-inset">
                            <x-icon name="phone" class="size-5" />
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-brand-950">Telepon</p>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="mt-0.5 block text-sm text-ink-soft transition-colors hover:text-brand-800">{{ $phone }}</a>
                        </div>
                    </div>

                    <div class="panel flex items-start gap-3.5 p-5">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700 ring-1 ring-brand-100 ring-inset">
                            <x-icon name="mail" class="size-5" />
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-brand-950">Email</p>
                            <a href="mailto:{{ $email }}" class="mt-0.5 block text-sm text-ink-soft transition-colors hover:text-brand-800">{{ $email }}</a>
                        </div>
                    </div>

                    <div class="panel flex items-start gap-3.5 p-5">
                        <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700 ring-1 ring-brand-100 ring-inset">
                            <x-icon name="clock" class="size-5" />
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-brand-950">Jam Layanan</p>
                            <p class="mt-0.5 text-sm leading-relaxed text-ink-soft">Senin – Jumat, 07.00 – 16.00 WIB</p>
                            <p class="mt-0.5 text-sm leading-relaxed text-ink-soft">Sabtu, 08.00 – 12.00 WIB</p>
                        </div>
                    </div>
                </div>

                {{-- Placeholder peta --}}
                <div class="panel mt-6 flex flex-col items-center justify-center border-dashed px-6 py-10 text-center">
                    <span class="mb-3 inline-flex size-11 items-center justify-center rounded-full bg-brand-50 text-brand-700 ring-1 ring-brand-100 ring-inset">
                        <x-icon name="globe" class="size-5" />
                    </span>
                    <p class="text-sm font-semibold text-brand-950">Lokasi sekolah</p>
                    <p class="mt-1 max-w-xs text-xs leading-relaxed text-ink-soft">
                        {{ $address }} — gunakan aplikasi peta favoritmu dan cari “SMK Taruna Sains Kediri”.
                    </p>
                </div>
            </div>

            {{-- Formulir pesan --}}
            <div>
                <div class="panel p-6 sm:p-8">
                    <x-section-heading
                        eyebrow="Kirim Pesan"
                        title="Tulis pesanmu"
                        lede="Isi formulir berikut dan kami akan membalas melalui email secepatnya."
                    />

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

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
                        @csrf

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="label" for="name">Nama <span class="text-rose-600">*</span></label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="120"
                                       autocomplete="name" placeholder="Nama lengkap kamu" class="field">
                                @error('name')
                                    <p class="field-error mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="label" for="email">Email <span class="text-rose-600">*</span></label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="160"
                                       autocomplete="email" placeholder="nama@email.com" class="field">
                                @error('email')
                                    <p class="field-error mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="label" for="phone">Nomor telepon <span class="font-normal text-ink-400">(opsional)</span></label>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" maxlength="25"
                                       autocomplete="tel" placeholder="08xx xxxx xxxx" class="field">
                                @error('phone')
                                    <p class="field-error mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="label" for="subject">Subjek <span class="text-rose-600">*</span></label>
                                <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required maxlength="160"
                                       placeholder="Contoh: Info PPDB 2026/2027" class="field">
                                @error('subject')
                                    <p class="field-error mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="label" for="message">Pesan <span class="text-rose-600">*</span></label>
                            <textarea id="message" name="message" rows="6" required maxlength="5000"
                                      placeholder="Tulis pesanmu di sini…" class="field resize-y">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="field-error mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <p class="text-xs text-ink-faint">Maksimal 5 pesan per menit.</p>
                            <button type="submit" class="btn btn-primary btn-lg">
                                Kirim Pesan <x-icon name="arrow-right" class="size-4" />
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
