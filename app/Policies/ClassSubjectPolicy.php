<?php

namespace App\Policies;

use App\Models\ClassSubject;
use App\Models\User;
use App\Support\Access;

class ClassSubjectPolicy
{
    /**
     * Otorisasi di sini dipanggil dengan instance ClassSubject, jadi policy
     * yang searched Laravel adalah ClassSubjectPolicy — bukan AssignmentPolicy
     * atau MaterialPolicy. Tanpa file ini, authorize('createFor', $classSubject)
     * tidak punya policy dan menolak semua orang, sehingga guru tidak bisa
     * membuat tugas maupun materi sama sekali.
     */
    public function createFor(User $user, ClassSubject $classSubject): bool
    {
        return $user->isAdmin() || Access::teacherManagesClassSubject($user, $classSubject);
    }

    public function view(User $user, ClassSubject $classSubject): bool
    {
        return $user->isAdmin() || Access::teacherManagesClassSubject($user, $classSubject);
    }

    public function update(User $user, ClassSubject $classSubject): bool
    {
        return $user->isAdmin() || Access::teacherManagesClassSubject($user, $classSubject);
    }
}
