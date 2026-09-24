<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\ClassSubject;
use App\Models\Material;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentAccessTest extends TestCase
{
    use RefreshDatabase;

    private function fixture(): array
    {
        // Kelas A dengan murid & guru; kelas B hanya untuk guru lain.
        $t1 = User::factory()->create(['role' => 'teacher']);
        $t2 = User::factory()->create(['role' => 'teacher']);
        $teacher1 = Teacher::query()->create(['user_id' => $t1->id, 'teacher_number' => 'T-'.mt_rand(1000, 9999)]);
        $teacher2 = Teacher::query()->create(['user_id' => $t2->id, 'teacher_number' => 'T-'.mt_rand(1000, 9999)]);

        $classA = SchoolClass::query()->create(['name' => 'XI-IPA'.mt_rand(1, 9), 'grade' => 11, 'academic_year' => '2026/2027', 'status' => 'active']);
        $classB = SchoolClass::query()->create(['name' => 'XI-IPS'.mt_rand(1, 9), 'grade' => 11, 'academic_year' => '2026/2027', 'status' => 'active']);

        $subjectA = Subject::query()->create(['name' => 'Biologi']);
        $subjectB = Subject::query()->create(['name' => 'Ekonomi']);
        $csA = ClassSubject::query()->create(['class_id' => $classA->id, 'subject_id' => $subjectA->id, 'teacher_id' => $teacher1->id, 'hours_per_week' => 3]);
        $csB = ClassSubject::query()->create(['class_id' => $classB->id, 'subject_id' => $subjectB->id, 'teacher_id' => $teacher2->id, 'hours_per_week' => 2]);

        $studentUser = User::factory()->create(['role' => 'student']);
        $student = Student::query()->create([
            'user_id' => $studentUser->id,
            'student_number' => 'N-'.mt_rand(100000, 999999),
            'class_id' => $classA->id,
            'enrollment_year' => 2026,
            'status' => 'active',
        ]);

        $materialA = Material::query()->create([
            'class_subject_id' => $csA->id,
            'teacher_id' => $teacher1->id,
            'title' => 'Materi Kelas Saya',
            'status' => 'published',
            'published_at' => now(),
        ]);
        $materialB = Material::query()->create([
            'class_subject_id' => $csB->id,
            'teacher_id' => $teacher2->id,
            'title' => 'Materi Kelas Lain',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $draft = Material::query()->create([
            'class_subject_id' => $csA->id,
            'teacher_id' => $teacher1->id,
            'title' => 'Materi Draf',
            'status' => 'draft',
        ]);

        $assignmentA = Assignment::query()->create([
            'class_subject_id' => $csA->id,
            'teacher_id' => $teacher1->id,
            'title' => 'Tugas Kelas Saya',
            'description' => 'kerjakan',
            'due_at' => now()->addDays(5),
            'status' => 'published',
        ]);
        $assignmentB = Assignment::query()->create([
            'class_subject_id' => $csB->id,
            'teacher_id' => $teacher2->id,
            'title' => 'Tugas Kelas Lain',
            'description' => 'kerjakan',
            'due_at' => now()->addDays(5),
            'status' => 'published',
        ]);

        return compact('studentUser', 'classA', 'csA', 'materialA', 'materialB', 'draft', 'assignmentA', 'assignmentB');
    }

    public function test_student_sees_only_materials_of_own_class(): void
    {
        $f = $this->fixture();

        $this->actingAs($f['studentUser'])->get('/portal/student/materials')
            ->assertOk()
            ->assertSee('Materi Kelas Saya')
            ->assertDontSee('Materi Kelas Lain');
    }

    public function test_student_cannot_view_material_from_other_class(): void
    {
        $f = $this->fixture();

        $this->actingAs($f['studentUser'])->get('/portal/student/materials/'.$f['materialB']->id)->assertForbidden();
    }

    public function test_student_can_view_material_from_own_class(): void
    {
        $f = $this->fixture();

        $this->actingAs($f['studentUser'])->get('/portal/student/materials/'.$f['materialA']->id)->assertOk();
    }

    public function test_student_cannot_view_unpublished_material_even_from_own_class(): void
    {
        $f = $this->fixture();

        $this->actingAs($f['studentUser'])->get('/portal/student/materials/'.$f['draft']->id)->assertNotFound();
    }

    public function test_student_cannot_view_assignment_from_other_class(): void
    {
        $f = $this->fixture();

        $this->actingAs($f['studentUser'])->get('/portal/student/assignments/'.$f['assignmentB']->id)->assertForbidden();
    }

    public function test_student_can_view_assignment_from_own_class(): void
    {
        $f = $this->fixture();

        $this->actingAs($f['studentUser'])->get('/portal/student/assignments/'.$f['assignmentA']->id)->assertOk();
    }
}
