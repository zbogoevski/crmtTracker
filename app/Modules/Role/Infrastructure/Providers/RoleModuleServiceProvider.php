<?php

declare(strict_types=1);

namespace App\Modules\Role\Infrastructure\Providers;

use App\Modules\Role\Infrastructure\Models\Role;
use App\Modules\Role\Infrastructure\Policies\RolePolicy;
use App\Modules\Role\Infrastructure\Repositories\RoleRepository;
use App\Modules\Role\Infrastructure\Repositories\RoleRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Override;

class RoleModuleServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        // Bind interfaces to implementations
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
    }

    public function boot(): void
    {
        // Check if module is enabled before loading
        if (! $this->isModuleEnabled()) {
            return;
        }

        $this->registerPolicies();
        $this->loadRoutes();
    }

    /**
     * Check if this module is enabled in config/modules.php
     */
    protected function isModuleEnabled(): bool
    {
        return (bool) config('modules.specific.Role.enabled', true);
    }

    protected function registerPolicies(): void
    {
        Gate::policy(Role::class, RolePolicy::class);
    }

    protected function loadRoutes(): void
    {
        // Load API routes
        $apiRouteFile = __DIR__.'/../Routes/roles.php';
        if (file_exists($apiRouteFile)) {
            require $apiRouteFile;
        }

        // Load web routes
        $webRouteFile = __DIR__.'/../Routes/web.php';
        if (file_exists($webRouteFile)) {
            Route::middleware('web')->group(function () use ($webRouteFile): void {
                require $webRouteFile;
            });
        }
    }
}
