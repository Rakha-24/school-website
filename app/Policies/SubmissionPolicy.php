<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;
use App\Support\Access;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubmissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            return $assignment->teacher_id === $user->teacher->id
                || Access::teacherManagesClassSubject($user, $assignment->classSubject);
        }

        return false;
    }

    public function view(User $user, Submission $submission): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Siswa hanya melihat submission miliknya
        if ($user->isStudent()) {
            return Access::ownsStudent($user, $submission->student);
        }

        // Guru: hanya submission dari kelas yang dia ajar
        if ($user->isTeacher()) {
            $assignment = $submission->assignment;

            return $assignment->teacher_id === $user->teacher->id
                || Access::teacherManagesClassSubject($user, $assignment->classSubject);
        }

        return false;
    }

    public function create(User $user, $student): bool
    {
        if (! $user->isStudent()) {
            return false;
        }

        return Access::ownsStudent($user, $student);
    }

    public function update(User $user, Submission $submission): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Siswa dapat memperbarui jawabannya sebelum deadline selagi belum dinilai
        if ($user->isStudent() && Access::ownsStudent($user, $submission->student)) {
            return $submission->score === null && $submission->assignment->isOpen();
        }

        return false;
    }

    /**
     * Hanya guru pengampu/di kelas yang bersangkutan atau admin yang boleh menilai.
     */
    public function grade(User $user, Submission $submission): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTeacher()) {
            $assignment = $submission->assignment;

            return $assignment->teacher_id === $user->teacher->id
                || Access::teacherManagesClassSubject($user, $assignment->classSubject);
        }

        return false;
    }
}
