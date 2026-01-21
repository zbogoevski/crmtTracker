<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Override;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->ensureViteManifestExistsForTests();

        // Disable rate limiting for tests
        $this->withoutMiddleware(\Illuminate\Routing\Middleware\ThrottleRequests::class);
    }

    protected function ensureViteManifestExistsForTests(): void
    {
        $manifestPath = public_path('build/manifest.json');

        if (is_file($manifestPath)) {
            return;
        }

        $manifestDir = dirname($manifestPath);
        if (! is_dir($manifestDir)) {
            mkdir($manifestDir, 0755, true);
        }

        $stubManifest = [
            'resources/js/app.js' => [
                'file' => 'assets/app.js',
                'src' => 'resources/js/app.js',
                'isEntry' => true,
            ],
            'resources/css/app.css' => [
                'file' => 'assets/app.css',
                'src' => 'resources/css/app.css',
                'isEntry' => true,
            ],
        ];

        file_put_contents(
            $manifestPath,
            (string) json_encode($stubManifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
