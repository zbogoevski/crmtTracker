<?php

declare(strict_types=1);

namespace App\Modules\Permission\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Permission\Application\Actions\CreatePermissionAction;
use App\Modules\Permission\Application\Actions\DeletePermissionAction;
use App\Modules\Permission\Application\Actions\GetAllPermissionsAction;
use App\Modules\Permission\Application\Actions\GetPermissionByIdAction;
use App\Modules\Permission\Application\Actions\UpdatePermissionAction;
use App\Modules\Permission\Application\DTO\CreatePermissionDTO;
use App\Modules\Permission\Application\DTO\UpdatePermissionDTO;
use App\Modules\Permission\Infrastructure\Http\Requests\CreatePermissionRequest;
use App\Modules\Permission\Infrastructure\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebPermissionController extends Controller
{
    public function __construct(
        protected GetAllPermissionsAction $getAllPermissionsAction,
        protected GetPermissionByIdAction $getPermissionByIdAction,
        protected CreatePermissionAction $createPermissionAction,
        protected UpdatePermissionAction $updatePermissionAction,
        protected DeletePermissionAction $deletePermissionAction,
    ) {}

    /**
     * List all permissions (admin only).
     */
    public function index(Request $request): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $perPage = (int) $request->get('per_page', 15);
        $permissions = $this->getAllPermissionsAction->execute($perPage);

        return view('permission.index', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show permission details (admin only).
     */
    public function show(int $id): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $permission = $this->getPermissionByIdAction->execute($id);

        return view('permission.show', [
            'permission' => $permission,
        ]);
    }

    /**
     * Show create permission form (admin only).
     */
    public function create(): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return view('permission.create');
    }

    /**
     * Store new permission (admin only).
     */
    public function store(CreatePermissionRequest $request): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $dto = CreatePermissionDTO::fromArray($request->validated());
        $this->createPermissionAction->execute($dto);

        return redirect()->route('web.permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Show edit permission form (admin only).
     */
    public function edit(int $id): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $permission = $this->getPermissionByIdAction->execute($id);

        return view('permission.edit', [
            'permission' => $permission,
        ]);
    }

    /**
     * Update permission (admin only).
     */
    public function update(int $id, UpdatePermissionRequest $request): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $dto = UpdatePermissionDTO::fromArray($request->validated());
        $this->updatePermissionAction->execute($id, $dto);

        return redirect()->route('web.permissions.show', $id)->with('success', 'Permission updated successfully.');
    }

    /**
     * Delete permission (admin only).
     */
    public function destroy(int $id): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $this->deletePermissionAction->execute($id);

        return redirect()->route('web.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
