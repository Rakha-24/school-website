<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders(): void
    {
        $this->get('/')->assertOk()->assertSee('SMK');
    }

    public function test_public_pages_are_reachable(): void
    {
        foreach (['/about', '/academic', '/achievements', '/admission', '/contact', '/extracurriculars', '/gallery', '/news'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_contact_form_stores_message(): void
    {
        $this->from('/contact')->post('/contact', [
            'name' => 'Orang Tua',
            'email' => 'orangtua@example.com',
            'phone' => '0812-3456-7890',
            'subject' => 'Pertanyaan PPDB',
            'message' => 'Pertanyaan tentang PPDB.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseCount('contact_messages', 1);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $this->post('/contact', [])->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
