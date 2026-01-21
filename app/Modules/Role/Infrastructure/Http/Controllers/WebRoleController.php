<?php

declare(strict_types=1);

namespace App\Modules\Role\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Role\Application\Actions\CreateRoleAction;
use App\Modules\Role\Application\Actions\DeleteRoleAction;
use App\Modules\Role\Application\Actions\GetAllRolesAction;
use App\Modules\Role\Application\Actions\GetRoleByIdAction;
use App\Modules\Role\Application\Actions\UpdateRoleAction;
use App\Modules\Role\Application\DTO\CreateRoleDTO;
use App\Modules\Role\Application\DTO\UpdateRoleDTO;
use App\Modules\Role\Infrastructure\Http\Requests\CreateRoleRequest;
use App\Modules\Role\Infrastructure\Http\Requests\UpdateRoleRequest;
use App\Modules\Role\Infrastructure\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebRoleController extends Controller
{
    public function __construct(
        protected GetAllRolesAction $getAllRolesAction,
        protected GetRoleByIdAction $getRoleByIdAction,
        protected CreateRoleAction $createRoleAction,
        protected UpdateRoleAction $updateRoleAction,
        protected DeleteRoleAction $deleteRoleAction,
    ) {}

    /**
     * List all roles (admin only).
     */
    public function index(Request $request): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $perPage = (int) $request->get('per_page', 15);
        $roles = $this->getAllRolesAction->execute($perPage);

        return view('role.index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show role details (admin only).
     */
    public function show(int $id): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $role = $this->getRoleByIdAction->execute($id);

        return view('role.show', [
            'role' => $role,
        ]);
    }

    /**
     * Show create role form (admin only).
     */
    public function create(): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return view('role.create');
    }

    /**
     * Store new role (admin only).
     */
    public function store(CreateRoleRequest $request): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $dto = CreateRoleDTO::fromArray($request->validated());
        $this->createRoleAction->execute($dto);

        return redirect()->route('web.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show edit role form (admin only).
     */
    public function edit(int $id): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $role = $this->getRoleByIdAction->execute($id);

        return view('role.edit', [
            'role' => $role,
        ]);
    }

    /**
     * Update role (admin only).
     */
    public function update(int $id, UpdateRoleRequest $request): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $dto = UpdateRoleDTO::fromArray($request->validated());
        $this->updateRoleAction->execute($id, $dto);

        return redirect()->route('web.roles.show', $id)->with('success', 'Role updated successfully.');
    }

    /**
     * Delete role (admin only).
     */
    public function destroy(int $id): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $this->deleteRoleAction->execute($id);

        return redirect()->route('web.roles.index')->with('success', 'Role deleted successfully.');
    }
}
