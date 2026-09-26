<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;
use App\Support\Access;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Assignment $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $assignment->teacher_id === $user->teacher->id
                || Access::teacherManagesClassSubject($user, $assignment->classSubject);
        }

        if ($user->isStudent()) {
            return Access::studentInClass($user->student, $assignment->classSubject->schoolClass);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }


    public function update(User $user, Assignment $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isTeacher() && $assignment->teacher_id === $user->teacher->id;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $this->update($user, $assignment);
    }
}
