<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_faq_with_dotted_component_rendering(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get('/portal/admin/faqs/create')->assertOk();

        $this->actingAs($admin)->post('/portal/admin/faqs', [
            'question' => 'Kapan PPDB dibuka?',
            'answer' => 'Setiap tahun bulan Januari.',
            'status' => 'published',
            'sort_order' => 1,
        ])->assertRedirect();

        $this->assertDatabaseHas('faqs', ['question' => 'Kapan PPDB dibuka?', 'status' => 'published']);
    }

    public function test_admin_can_edit_and_update_faq(): void
    {
        $admin = $this->admin();
        $faq = Faq::query()->create([
            'question' => 'Q lama',
            'answer' => 'A lama',
            'status' => 'published',
            'sort_order' => 2,
        ]);

        $this->actingAs($admin)->get("/portal/admin/faqs/{$faq->id}/edit")->assertOk();

        $this->actingAs($admin)->put("/portal/admin/faqs/{$faq->id}", [
            'question' => 'Q baru',
            'answer' => 'A baru',
            'status' => 'hidden',
            'sort_order' => 3,
        ])->assertRedirect();

        $this->assertDatabaseHas('faqs', ['id' => $faq->id, 'question' => 'Q baru', 'status' => 'hidden']);
    }

    public function test_admin_can_delete_faq(): void
    {
        $admin = $this->admin();
        $faq = Faq::query()->create([
            'question' => 'Q hapus',
            'answer' => 'A hapus',
            'status' => 'published',
            'sort_order' => 4,
        ]);

        $this->actingAs($admin)->delete("/portal/admin/faqs/{$faq->id}")->assertRedirect();

        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    public function test_non_admin_cannot_create_faq(): void
    {
        $teacher = User::factory()->create(['role' => 'teacher']);

        $this->actingAs($teacher)->post('/portal/admin/faqs', [
            'question' => 'Bukan urusan saya',
            'answer' => 'tidak boleh',
            'status' => 'published',
        ])->assertForbidden();
    }
}
