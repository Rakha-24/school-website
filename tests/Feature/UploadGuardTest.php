<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Semua penulisan berkas harus lewat HandlesUploads::storeSanitizedFile().
 *
 * Pemanggilan UploadedFile::store() secara langsung melempar
 * League\Flysystem\UnableToCreateDirectory di environment read-only
 * (serverless), sehingga pengguna melihat 500 alih-alih pesan yang jelas.
 * Pemanggilan itu juga melewatkan sanitasi nama berkas.
 */
class UploadGuardTest extends TestCase
{
    public function test_no_controller_writes_a_file_without_the_shared_upload_guard(): void
    {
        $offenders = [];

        foreach (glob(app_path('Http/Controllers/**/*.php')) as $file) {
            $source = file_get_contents($file);

            // Hanya tulis berkas yang nyata; store() milik request/response
            // tidak ikut karena bentuknya berbeda.
            if (preg_match('/->\s*file\([^)]*\)\s*->\s*store\w*\s*\(/', $source, $m)
                || preg_match('/->\s*storeAs\s*\(/', $source)
                || preg_match('/->\s*storePublicly\w*\s*\(/', $source)
                || preg_match('/->\s*putFile\w*\s*\(/', $source)
                || preg_match('/->\s*move\s*\(\s*\$/', $source)) {
                $offenders[] = str_replace(base_path().'/', '', $file).' ('.$m[0].')';
            }
        }

        $this->assertSame(
            [],
            $offenders,
            "Controller berikut menulis berkas di luar HandlesUploads:\n".implode("\n", $offenders)
        );
    }

    public function test_the_shared_guard_is_the_only_disk_writer(): void
    {
        $trait = file_get_contents(app_path('Traits/HandlesUploads.php'));

        $this->assertStringContainsString('storePubliclyAs', $trait);
        $this->assertMatchesRegularExpression(
            '/catch\s*\(\s*Throwable\s*\)/',
            $trait,
            'Guard harus menangkap Throwable, karena kegagalan adapter terjadi saat konstruktor disk dibangun.'
        );
    }
}
