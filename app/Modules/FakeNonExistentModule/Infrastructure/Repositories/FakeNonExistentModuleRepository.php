<?php

declare(strict_types=1);

namespace App\Modules\FakeNonExistentModule\Infrastructure\Repositories;

use App\Modules\Core\Repositories\EloquentRepository;
use App\Modules\FakeNonExistentModule\Infrastructure\Repositories\FakeNonExistentModuleRepositoryInterface;
use App\Modules\FakeNonExistentModule\Infrastructure\Models\FakeNonExistentModule;

/**
 * Repository implementation for FakeNonExistentModule operations.
 *
 * Provides database access methods for fakenonexistentmodule models.
 *
 * @extends EloquentRepository<FakeNonExistentModule>
 */
final class FakeNonExistentModuleRepository extends EloquentRepository implements FakeNonExistentModuleRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @param FakeNonExistentModule $model The fakenonexistentmodule model instance
     */
    public function __construct(FakeNonExistentModule $model)
    {
        parent::__construct($model);
    }
}
