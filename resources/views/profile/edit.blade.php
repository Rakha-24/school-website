<x-layouts.portal :title="'Profil Saya'" :role="auth()->user()->role">
    <x-page-header title="Profil Saya" description="Kelola informasi akun dan kata sandi Anda." />

    <div class="space-y-6">
        @include('profile.partials.update-profile-information-form')

        @include('profile.partials.update-password-form')

        @include('profile.partials.delete-user-form')
    </div>
</x-layouts.portal>