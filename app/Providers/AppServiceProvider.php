<?php

namespace App\Providers;

use App\Support\Access;
use App\View\Components\Layouts\PublicLayout;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.custom');
        Blade::component(PublicLayout::class, 'layouts.public');

        Gate::define('access-admin', fn ($user) => Access::admin($user));

        Gate::define('manage-announcements', fn ($user) => $user->isAdmin() || $user->isTeacher());
        Gate::define('manage-schedule', fn ($user) => Access::admin($user));
        Gate::define('manage-content', fn ($user) => Access::admin($user));
        Gate::define('view-report', fn ($user) => Access::admin($user));

        Gate::define('manage-class-subjects', fn ($user) => Access::admin($user));

        // Delegasi ke Access::teacherManagesClass(), bukan aturan sendiri. Versi
        // sebelumnya hanya mengizinkan wali kelas, sementara dropdown presensi
        // menampilkan kelas dari mapel yang diampu — guru mapel pun melihat
        // kelas miliknya di dropdown lalu mendapat 403.
        Gate::define('manageClass', fn ($user, $class) => Access::admin($user)
            || Access::teacherManagesClass($user, $class));

        Gate::define('create-materials', fn ($user) => $user->isTeacher() || Access::admin($user));
        Gate::define('create-assignments', fn ($user) => $user->isTeacher() || Access::admin($user));
    }
}
