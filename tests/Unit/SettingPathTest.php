<?php

namespace Tests\Unit;

use App\Models\Setting;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionProperty;
use Tests\TestCase;

class SettingPathTest extends TestCase
{
    private ReflectionProperty $resolved;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolved = new ReflectionProperty(Setting::class, 'resolved');
    }

    protected function tearDown(): void
    {
        $this->resolved->setValue(null, null);

        parent::tearDown();
    }

    private function pretendStoredValue(?string $value): void
    {
        $this->resolved->setValue(null, $value === null ? [] : ['logo' => $value]);
    }

    public static function validPaths(): array
    {
        return [
            ['settings/logo-abc.webp'],
            ['seeds/hero.webp'],
            ['photos/students/2026-01-01-abc.webp'],
        ];
    }

    #[DataProvider('validPaths')]
    public function test_it_keeps_relative_paths(string $path): void
    {
        $this->pretendStoredValue($path);

        $this->assertSame($path, Setting::path('logo'));
    }

    public static function rejectedValues(): array
    {
        return [
            'php temp path' => ['/tmp/php7de7v8l8rl0cdch0ft7'],
            'absolute unix' => ['/etc/passwd'],
            'traversal' => ['../../../etc/passwd'],
            'https url' => ['https://evil.example/x.png'],
            'http url' => ['http://evil.example/x.png'],
            'windows drive' => ['C:/win.png'],
            'unc path' => ['\\\\srv\\share\\x.png'],
            'empty' => [''],
            'whitespace' => ['   '],
        ];
    }

    #[DataProvider('rejectedValues')]
    public function test_it_rejects_anything_that_is_not_a_relative_public_path(string $value): void
    {
        $this->pretendStoredValue($value);

        $this->assertNull(Setting::path('logo'));
    }

    public function test_it_returns_null_when_key_is_absent(): void
    {
        $this->pretendStoredValue(null);

        $this->assertNull(Setting::path('logo'));
    }
}
