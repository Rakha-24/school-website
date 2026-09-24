<?php

namespace Tests\Feature;

use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;

trait CreatesPortalFixture
{
    protected function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    protected function makeTeacher(): array
    {
        $teacherUser = User::factory()->create(['role' => 'teacher']);
        $otherUser = User::factory()->create(['role' => 'teacher']);
        $teacher = Teacher::query()->create(['user_id' => $teacherUser->id, 'teacher_number' => 'T-'.mt_rand(1000, 9999)]);
        $other = Teacher::query()->create(['user_id' => $otherUser->id, 'teacher_number' => 'T-'.mt_rand(1000, 9999)]);

        $class = SchoolClass::query()->create([
            'name' => 'X-TAB '.mt_rand(10, 99),
            'grade' => 10,
            'academic_year' => '2026/2027',
            'status' => 'active',
        ]);
        $otherClass = SchoolClass::query()->create([
            'name' => 'XI-TAB '.mt_rand(10, 99),
            'grade' => 10,
            'academic_year' => '2026/2027',
            'status' => 'active',
        ]);

        $subject = Subject::query()->create(['name' => 'Matematika']);
        $classSubject = ClassSubject::query()->create([
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'hours_per_week' => 4,
        ]);
        $foreignSubject = Subject::query()->create(['name' => 'Fisika']);
        $otherClassSubject = ClassSubject::query()->create([
            'class_id' => $otherClass->id,
            'subject_id' => $foreignSubject->id,
            'teacher_id' => $other->id,
            'hours_per_week' => 2,
        ]);

        return compact('teacherUser', 'otherUser', 'teacher', 'other', 'class', 'classSubject', 'otherClassSubject');
    }

    protected function makeStudent(SchoolClass $class, bool $ownClass = true): array
    {
        $studentUser = User::factory()->create(['role' => 'student']);
        $student = Student::query()->create([
            'user_id' => $studentUser->id,
            'student_number' => 'NIS-'.mt_rand(100000, 999999),
            'class_id' => $ownClass ? $class->id : null,
            'enrollment_year' => (int) date('Y'),
            'status' => 'active',
        ]);

        return compact('studentUser', 'student');
    }
}
