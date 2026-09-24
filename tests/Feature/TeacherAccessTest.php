<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherAccessTest extends TestCase
{
    use CreatesPortalFixture;
    use RefreshDatabase;

    /**
     * Guru tidak bisa melihat/mengubah materi milik guru lain.
     */
    public function test_teacher_cannot_view_material_owned_by_another_teacher(): void
    {
        ['teacherUser' => $owner, 'other' => $other, 'otherClassSubject' => $otherClassSubject] = $this->makeTeacher();

        $material = Material::query()->create([
            'class_subject_id' => $otherClassSubject->id,
            'teacher_id' => $other->id,
            'title' => 'Materi Rahasia Guru Lain',
            'status' => 'published',
        ]);

        $this->actingAs($owner)->get("/portal/teacher/materials/{$material->id}")->assertForbidden();
    }

    /**
     * Guru dapat mengedit materi miliknya.
     */
    public function test_teacher_can_edit_own_material(): void
    {
        ['teacherUser' => $owner, 'teacher' => $teacher, 'classSubject' => $classSubject] = $this->makeTeacher();

        $material = Material::query()->create([
            'class_subject_id' => $classSubject->id,
            'teacher_id' => $teacher->id,
            'title' => 'Materi Saya',
            'status' => 'published',
        ]);

        $this->actingAs($owner)->get("/portal/teacher/materials/{$material->id}/edit")->assertOk();
    }

    public function test_teacher_list_sees_only_own_materials(): void
    {
        ['teacherUser' => $owner, 'teacher' => $teacher, 'other' => $other, 'class' => $class, 'classSubject' => $classSubject] = $this->makeTeacher();

        Material::query()->create([
            'class_subject_id' => $classSubject->id,
            'teacher_id' => $teacher->id,
            'title' => 'Punya Saya',
            'status' => 'published',
        ]);
        Material::query()->create([
            'class_subject_id' => $classSubject->id,
            'teacher_id' => $other->id,
            'title' => 'Punya Guru Lain',
            'status' => 'published',
        ]);

        $response = $this->actingAs($owner)->get('/portal/teacher/materials');

        $response->assertOk()
            ->assertSee('Punya Saya')
            ->assertDontSee('Punya Guru Lain');
    }

    /**
     * Tugas milik guru lain tidak boleh diakses.
     */
    public function test_teacher_cannot_view_assignment_of_another_teacher(): void
    {
        ['teacherUser' => $owner, 'other' => $other, 'otherClassSubject' => $otherClassSubject] = $this->makeTeacher();

        $assignment = Assignment::query()->create([
            'class_subject_id' => $otherClassSubject->id,
            'teacher_id' => $other->id,
            'title' => 'Tugas Rahasia',
            'description' => 'rahasia',
            'due_at' => now()->addDays(3),
            'status' => 'published',
        ]);

        $this->actingAs($owner)->get("/portal/teacher/assignments/{$assignment->id}")->assertForbidden();
    }

    /**
     * Guru bisa mengakses halaman pengumpulan murid untuk tugasnya sendiri.
     */
    public function test_teacher_can_access_submissions_for_own_assignment(): void
    {
        ['teacherUser' => $owner, 'teacher' => $teacher, 'classSubject' => $classSubject] = $this->makeTeacher();

        $assignment = Assignment::query()->create([
            'class_subject_id' => $classSubject->id,
            'teacher_id' => $teacher->id,
            'title' => 'Tugas Saya',
            'description' => 'deskripsi',
            'due_at' => now()->addDays(3),
            'status' => 'published',
        ]);

        $this->actingAs($owner)->get("/portal/teacher/submissions/assignment/{$assignment->id}")->assertOk();
    }
}
