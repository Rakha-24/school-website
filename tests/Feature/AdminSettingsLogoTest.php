<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSettingsLogoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::set('school_name', 'SMK Taruna Sains Kediri');

        $this->admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_admin_can_upload_logo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('logo.png', 120, 120);

        $this->actingAs($this->admin)
            ->put(route('portal.admin.settings.update'), [
                'school_name' => 'SMK Taruna Sains Kediri',
                'tagline' => 'Tagline',
                'address' => 'Jl. Pendidikan No. 1',
                'email' => 'smk.tarunasains@gmail.com',
                'logo' => $file,
            ]);

        $path = Setting::get('logo');
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
        $this->assertStringStartsWith('settings/', $path);
    }

    public function test_admin_can_remove_logo(): void
    {
        Storage::fake('public');

        $path = UploadedFile::fake()->image('logo.png')->storePubliclyAs('settings', 'logo-hehe.png', 'public');
        Setting::set('logo', $path);

        $this->actingAs($this->admin)
            ->put(route('portal.admin.settings.update'), [
                'school_name' => 'SMK Taruna Sains Kediri',
                'address' => 'Jl. Pendidikan No. 1',
                'email' => 'smk.tarunasains@gmail.com',
                'logo_remove' => '1',
            ]);

        $this->assertNull(Setting::get('logo'));
        Storage::disk('public')->assertMissing($path);
    }

    public function test_upload_does_not_store_php_temp_path_in_settings(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->put(route('portal.admin.settings.update'), [
                'school_name' => 'SMK Taruna Sains Kediri',
                'address' => 'Jl. Pendidikan No. 1',
                'email' => 'smk.tarunasains@gmail.com',
                'logo' => UploadedFile::fake()->image('logo.png', 120, 120),
            ]);

        $path = Setting::get('logo');

        $this->assertNotSame('', (string) $path, 'path tempfile PHP tidak boleh tersimpan di settings');
        $this->assertStringNotContainsString('php', strtolower((string) $path));
        $this->assertFalse(
            str_starts_with((string) $path, '/'),
            'settings logo harus path relatif, bukan path absolut'
        );
        $this->assertSame($path, Setting::path('logo'), 'nilai tersimpan harus lolos validasi Setting::path()');
    }

    public function test_corrupt_stored_value_falls_back_to_default_logo(): void
    {
        // Nilai seperti ini pernah tersimpan karena bug casting UploadedFile ke
        // string. render harus memakainya sebagai null, bukan <img> rusak.
        Setting::set('logo', '/tmp/php7de7v8l8rl0cdch0ft7');

        $this->assertNull(Setting::path('logo'));

        $this->actingAs($this->admin)
            ->get(route('portal.admin.settings'))
            ->assertOk()
            ->assertDontSee('/storage//tmp/php', false);
    }

    public function test_invalid_logo_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->put(route('portal.admin.settings.update'), [
                'school_name' => 'SMK Taruna Sains Kediri',
                'address' => 'Jl. Pendidikan No. 1',
                'email' => 'smk.tarunasains@gmail.com',
                'logo' => UploadedFile::fake()->create('logo.txt', 10),
            ])
            ->assertSessionHasErrors('logo');

        $this->assertNull(Setting::get('logo'));
    }
}
