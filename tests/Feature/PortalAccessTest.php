<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalAccessTest extends TestCase
{
    use CreatesPortalFixture;
    use RefreshDatabase;

    public function test_portal_redirects_admin_to_admin_dashboard(): void
    {
        $response = $this->actingAs($this->makeAdmin())->get('/portal');

        $response->assertRedirect('/portal/admin/dashboard');
    }

    public function test_portal_redirects_teacher_to_teacher_dashboard(): void
    {
        ['teacherUser' => $user] = $this->makeTeacher();

        $this->actingAs($user)->get('/portal')->assertRedirect('/portal/teacher/dashboard');
    }

    public function test_guest_cannot_access_admin_pages(): void
    {
        $this->get('/portal/admin/dashboard')->assertRedirect('/login');
    }

    public function test_student_cannot_access_admin_pages(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)->get('/portal/admin/dashboard')->assertForbidden();
    }

    public function test_student_cannot_access_teacher_pages(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)->get('/portal/teacher/materials')->assertForbidden();
    }

    public function test_teacher_can_access_teacher_pages(): void
    {
        ['teacherUser' => $user] = $this->makeTeacher();

        $this->actingAs($user)->get('/portal/teacher/dashboard')->assertOk();
    }

    public function test_admin_can_access_admin_pages(): void
    {
        $this->actingAs($this->makeAdmin())->get('/portal/admin/dashboard')->assertOk();
    }
}
