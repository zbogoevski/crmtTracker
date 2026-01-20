<?php

declare(strict_types=1);

namespace App\Modules\Core\Support\Generators;

use Illuminate\Filesystem\Filesystem;

/**
 * @deprecated Repository bindings are now registered in individual module service providers.
 * This class is kept for backward compatibility but does nothing.
 */
class RepositoryBinder
{
    public function __construct(protected Filesystem $files) {}

    /**
     * @deprecated Repository bindings are now registered in module service providers.
     * This method does nothing and can be removed.
     */
    public function bind(string $moduleName): void
    {
        // Repository bindings are now handled by individual module service providers
        // No need to modify RepositoryServiceProvider anymore
        // Legacy code has been removed as it was unreachable
    }
}
