<?php

namespace App\Support;

use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

class Access
{
    /**
     * Apakah user adalah admin.
     */
    public static function admin(?User $user): bool
    {
        return (bool) $user?->isAdmin();
    }

    public static function teacher(?User $user): bool
    {
        return (bool) $user?->isTeacher();
    }

    public static function student(?User $user): bool
    {
        return (bool) $user?->isStudent();
    }

    /**
     * Apakah guru mengajar kelas ini (relasi class_subjects) atau menjadi wali kelas.
     */
    public static function teacherManagesClass(?User $user, SchoolClass $class): bool
    {
        $teacher = $user?->teacher;
        if (! $teacher) {
            return false;
        }

        return $class->homeroom_teacher_id === $teacher->id
            || $teacher->classSubjects()->where('class_id', $class->id)->exists();
    }

    /**
     * Apakah guru memegang relasi kelas-mapel ini.
     */
    public static function teacherManagesClassSubject(?User $user, ClassSubject $classSubject): bool
    {
        return (bool) $user?->teacher?->id && $classSubject->teacher_id === $user->teacher->id;
    }

    /**
     * Apakah siswa terdaftar di kelas ini.
     */
    public static function studentInClass(?Student $student, SchoolClass $class): bool
    {
        return (bool) $student && $student->class_id === $class->id;
    }

    /**
     * Apakah user saat ini adalah pemilik student record ini.
     */
    public static function ownsStudent(?User $user, Student $student): bool
    {
        return (bool) $user && $user->id === $student->user_id;
    }

    public static function ownsTeacher(?User $user, Teacher $teacher): bool
    {
        return (bool) $user && $user->id === $teacher->user_id;
    }
}
