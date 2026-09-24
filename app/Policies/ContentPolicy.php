<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContentPolicy
{
    use HandlesAuthorization;

    public function manage(User $user): bool
    {
        return $user->isAdmin();
    }

    public function manageNews(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, News $news): bool
    {
        return $news->isPublished() || $user->isAdmin();
    }
}
