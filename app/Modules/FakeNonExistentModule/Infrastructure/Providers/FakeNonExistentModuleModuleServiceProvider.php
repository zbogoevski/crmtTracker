<?php

declare(strict_types=1);

namespace App\Modules\FakeNonExistentModule\Infrastructure\Providers;

use App\Modules\FakeNonExistentModule\Infrastructure\Models\FakeNonExistentModule;
use App\Modules\FakeNonExistentModule\Infrastructure\Repositories\FakeNonExistentModuleRepository;
use App\Modules\FakeNonExistentModule\Infrastructure\Repositories\FakeNonExistentModuleRepositoryInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FakeNonExistentModuleModuleServiceProvider extends ServiceProvider
{
    /**
     * Register module-specific bindings (repositories, services, etc.)
     * This is module-specific and should be in individual service providers.
     */
    public function register(): void
    {
        $this->app->bind(FakeNonExistentModuleRepositoryInterface::class, FakeNonExistentModuleRepository::class);
    }

    /**
     * Bootstrap module-specific resources (routes, policies, observers, events)
     * This is module-specific and should be in individual service providers.
     * ModularServiceProvider handles only global resources (migrations, factories, helpers).
     */
    public function boot(): void
    {
        // Check if module is enabled before loading
        if (! $this->isModuleEnabled()) {
            return;
        }

        $this->registerPolicies();
        $this->registerObservers();
        $this->registerEvents();
        $this->loadRoutes();
    }

    /**
     * Check if this module is enabled in config/modules.php
     */
    protected function isModuleEnabled(): bool
    {
        return (bool) config('modules.specific.FakeNonExistentModule.enabled', true);
    }

    /**
     * Load module routes.
     * Each module is responsible for loading its own routes.
     * Routes already have prefix and middleware defined in route files.
     */
    protected function loadRoutes(): void
    {
        $routeFile = __DIR__.'/../Routes/fakenonexistentmodule.php';

        if (! file_exists($routeFile)) {
            return;
        }

        // Routes already have prefix and middleware in route files, just require them
        require $routeFile;
    }

    /**
     * Register module policies.
     * Each module is responsible for registering its own policies.
     */
    protected function registerPolicies(): void
    {
        $policyClass = 'App\\Modules\\FakeNonExistentModule\\Infrastructure\\Policies\\FakeNonExistentModulePolicy';

        if (class_exists($policyClass) && class_exists(FakeNonExistentModule::class)) {
            Gate::policy(FakeNonExistentModule::class, $policyClass);
        }
    }

    /**
     * Register module observers.
     * Each module is responsible for registering its own observers.
     */
    protected function registerObservers(): void
    {
        $observerClass = 'App\\Modules\\FakeNonExistentModule\\Infrastructure\\Observers\\FakeNonExistentModuleObserver';

        if (class_exists($observerClass) && class_exists(FakeNonExistentModule::class)) {
            FakeNonExistentModule::observe($observerClass);
        }
    }

    /**
     * Register module events and listeners.
     * Each module is responsible for registering its own events.
     */
    protected function registerEvents(): void
    {
        $basePath = app_path('Modules/FakeNonExistentModule');
        $eventsPath = "{$basePath}/Application/Events";
        $listenersPath = "{$basePath}/Application/Listeners";

        if (! is_dir($eventsPath) || ! is_dir($listenersPath)) {
            return;
        }

        $fs = new Filesystem();

        foreach ($fs->files($eventsPath) as $eventFile) {
            $eventName = $eventFile->getFilenameWithoutExtension();
            $eventClass = "App\\Modules\\FakeNonExistentModule\\Application\\Events\\{$eventName}";

            if (! class_exists($eventClass)) {
                continue;
            }

            $listenerName = $eventName.'Listener';
            $listenerClass = "App\\Modules\\FakeNonExistentModule\\Application\\Listeners\\{$listenerName}";

            if (class_exists($listenerClass)) {
                Event::listen($eventClass, $listenerClass);
            }
        }
    }
}
