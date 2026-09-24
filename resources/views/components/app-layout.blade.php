@php $role = auth()->user()?->role ?? ''; @endphp

<x-layouts.portal :title="$title ?? 'Profil'" :role="$role">
    {{ $slot }}
</x-layouts.portal>