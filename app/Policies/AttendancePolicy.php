<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\User;
use App\Support\Access;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, SchoolClass $class): bool
    {
        return $this->manageClass($user, $class);
    }

    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return Access::teacherManagesClass($user, $attendance->schoolClass);
        }

        if ($user->isStudent()) {
            return Access::ownsStudent($user, $attendance->student);
        }

        return false;
    }

    public function manageClass(User $user, SchoolClass $class): bool
    {
        return $user->isAdmin() || Access::teacherManagesClass($user, $class);
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $this->manageClass($user, $attendance->schoolClass);
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $this->manageClass($user, $attendance->schoolClass);
    }
}
