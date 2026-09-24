<?php

namespace App\Policies;

use App\Models\ClassSubject;
use App\Models\Material;
use App\Models\User;
use App\Support\Access;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Material $material): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Guru: hanya materi milik / yang dia ajar
        if ($user->isTeacher()) {
            return $material->teacher_id === $user->teacher->id
                || Access::teacherManagesClassSubject($user, $material->classSubject);
        }

        // Siswa: hanya materi dari kelas-mapel kelasnya sendiri
        if ($user->isStudent()) {
            return Access::studentInClass($user->student, $material->classSubject->schoolClass);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function createFor(User $user, ClassSubject $classSubject): bool
    {
        return $user->isAdmin() || Access::teacherManagesClassSubject($user, $classSubject);
    }

    public function update(User $user, Material $material): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isTeacher() && $material->teacher_id === $user->teacher->id;
    }

    public function delete(User $user, Material $material): bool
    {
        return $this->update($user, $material);
    }
}
