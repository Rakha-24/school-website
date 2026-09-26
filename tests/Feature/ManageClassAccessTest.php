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

class ManageClassAccessTest extends TestCase
{
    use RefreshDatabase;

    private function makeClass(string $name): SchoolClass
    {
        return SchoolClass::create([
            'name' => $name,
            'grade' => 10,
            'academic_year' => '2026/2027',
        ]);
    }

    private function makeTeacher(User $role = null): array
    {
        $user = $role ?: User::factory()->create(['role' => User::ROLE_TEACHER]);
        $teacher = Teacher::create(['user_id' => $user->id]);

        return [$user, $teacher];
    }

    private function teach(SchoolClass $class, Teacher $teacher): void
    {
        ClassSubject::create([
            'class_id' => $class->id,
            'subject_id' => Subject::create(['name' => 'Mapel '.$class->id])->id,
            'teacher_id' => $teacher->id,
        ]);
    }

    public function test_subject_teacher_may_manage_a_class_they_teach(): void
    {
        $class = $this->makeClass('X-A');
        [$user, $teacher] = $this->makeTeacher();
        $this->teach($class, $teacher);

        $this->assertTrue(
            Gate::forUser($user)->allows('manageClass', $class),
            'Guru mapel harus bisa mencatat presensi kelas yang diampunya, bukan hanya wali kelas.'
        );
    }

    public function test_homeroom_teacher_may_manage_their_class(): void
    {
        $class = $this->makeClass('X-B');
        [$user, $teacher] = $this->makeTeacher();
        $class->forceFill(['homeroom_teacher_id' => $teacher->id])->save();

        $this->assertTrue(Gate::forUser($user)->allows('manageClass', $class));
    }

    public function test_teacher_may_not_manage_an_unrelated_class(): void
    {
        $mine = $this->makeClass('X-C');
        $theirs = $this->makeClass('X-D');
        [$user, $teacher] = $this->makeTeacher();
        $this->teach($mine, $teacher);

        $this->assertFalse(Gate::forUser($user)->allows('manageClass', $theirs));
    }

    public function test_student_may_not_manage_any_class(): void
    {
        $class = $this->makeClass('X-E');
        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $this->assertFalse(Gate::forUser($student)->allows('manageClass', $class));
    }

    public function test_the_rule_never_drifts_from_the_canonical_helper(): void
    {
        $taught = $this->makeClass('X-F');
        $untouched = $this->makeClass('X-G');
        [$user, $teacher] = $this->makeTeacher();
        $this->teach($taught, $teacher);

        foreach ([$taught, $untouched] as $class) {
            $this->assertSame(
                Access::teacherManagesClass($user, $class),
                Gate::forUser($user)->allows('manageClass', $class),
                'Aturan manageClass harus identik dengan Access::teacherManagesClass().'
            );
        }
    }
}
