<?php

namespace Tests\Feature;

use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Support\Access;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

/**
 * OtorisasiAssignment dan Materi dijalankan sebagai
 * authorize('createFor', $classSubject). Karena argumennya ClassSubject,
 * Laravel mencari ClassSubjectPolicy. Kalau policy itu tidak ada, semua guru
 * mendapat 403 dan fitur buat tugas/materi mati total.
 */
class ClassSubjectCreateForTest extends TestCase
{
    use RefreshDatabase;

    private function makeClassSubject(int $teacherId, string $suffix): ClassSubject
    {
        $class = SchoolClass::create([
            'name' => 'X-'.$suffix,
            'grade' => 10,
            'academic_year' => '2026/2027',
        ]);

        $subject = Subject::create(['name' => 'Mapel '.$suffix]);

        return ClassSubject::create([
            'class_id' => $class->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacherId,
        ]);
    }

    private function makeTeacher(): array
    {
        $user = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $teacher = Teacher::create(['user_id' => $user->id]);

        return [$user, $teacher];
    }

    public function test_the_policy_for_class_subject_exists(): void
    {
        $this->assertTrue(
            class_exists(\App\Policies\ClassSubjectPolicy::class),
            'ClassSubjectPolicy wajib ada; authorize() mencari policy berdasarkan model yang dioper.'
        );
    }

    public function test_teacher_may_create_for_their_own_class_subject(): void
    {
        [$user, $teacher] = $this->makeTeacher();
        $classSubject = $this->makeClassSubject($teacher->id, 'A');

        $this->assertTrue(Gate::forUser($user)->allows('createFor', $classSubject));
    }

    public function test_teacher_may_not_create_for_another_teachers_class_subject(): void
    {
        [, $otherTeacher] = $this->makeTeacher();
        [$user] = $this->makeTeacher();
        $classSubject = $this->makeClassSubject($otherTeacher->id, 'B');

        $this->assertFalse(Gate::forUser($user)->allows('createFor', $classSubject));
    }

    public function test_admin_may_create_for_any_class_subject(): void
    {
        [, $teacher] = $this->makeTeacher();
        $classSubject = $this->makeClassSubject($teacher->id, 'C');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->assertTrue(Gate::forUser($admin)->allows('createFor', $classSubject));
    }

    public function test_student_may_not_create_for_any_class_subject(): void
    {
        [, $teacher] = $this->makeTeacher();
        $classSubject = $this->makeClassSubject($teacher->id, 'D');
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $this->assertFalse(Gate::forUser($student)->allows('createFor', $classSubject));
    }

    public function test_it_matches_the_canonical_helper(): void
    {
        [, $teacher] = $this->makeTeacher();
        [$user] = $this->makeTeacher();
        $mine = $this->makeClassSubject($teacher->id, 'E');
        $theirs = $this->makeClassSubject($user->id, 'F');

        foreach ([$mine, $theirs] as $classSubject) {
            $this->assertSame(
                Access::teacherManagesClassSubject($user, $classSubject),
                Gate::forUser($user)->allows('createFor', $classSubject)
            );
        }
    }
}
