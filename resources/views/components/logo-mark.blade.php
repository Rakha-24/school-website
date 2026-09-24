@props(['class' => 'size-10'])

@php($logo = App\Models\Setting::get('logo'))

@if ($logo)
    <img src="{{ asset('storage/'.$logo) }}" alt="Logo sekolah" class="{{ $class }} shrink-0 rounded-md object-contain" />
@else
    <svg viewBox="0 0 48 48" class="{{ $class }} shrink-0" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <rect x="1" y="1" width="46" height="46" rx="11" class="fill-brand-900"/>
        <rect x="1.75" y="1.75" width="44.5" height="44.5" rx="10.25" class="stroke-brand-700" stroke-width="1"/>
        <path d="M24 8.5 9 14.5v-2.25h30v2.25L24 8.5Z" class="fill-accent-500"/>
        <path d="M9 14.5v5c0 8.4 6.6 13.8 15 16 8.4-2.2 15-7.6 15-16v-5H9Zm4.5 5.6h21V23h-21v-2.9Zm0 6.3h21v3H13.5v-3Zm7 6.2h7v3h-7v-3Z" class="fill-white"/>
    </svg>
@endif