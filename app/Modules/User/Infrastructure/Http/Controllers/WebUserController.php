<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\Actions\TwoFactor\DisableAction;
use App\Modules\Auth\Application\Actions\TwoFactor\SetupAction;
use App\Modules\Auth\Application\Services\TwoFactor\ServiceInterface;
use App\Modules\User\Application\Actions\CreateUserAction;
use App\Modules\User\Application\Actions\DeleteUserAction;
use App\Modules\User\Application\Actions\GetAllUsersAction;
use App\Modules\User\Application\Actions\GetUserByIdAction;
use App\Modules\User\Application\Actions\UpdateUserAction;
use App\Modules\User\Application\DTO\CreateUserDTO;
use App\Modules\User\Application\DTO\UpdateUserDTO;
use App\Modules\User\Infrastructure\Http\Requests\CreateUserRequest;
use App\Modules\User\Infrastructure\Http\Requests\UpdatePasswordRequest;
use App\Modules\User\Infrastructure\Http\Requests\UpdateUserRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class WebUserController extends Controller
{
    public function __construct(
        protected GetAllUsersAction $getAllUsersAction,
        protected GetUserByIdAction $getUserByIdAction,
        protected CreateUserAction $createUserAction,
        protected UpdateUserAction $updateUserAction,
        protected DeleteUserAction $deleteUserAction,
        protected SetupAction $setupTwoFactorAction,
        protected DisableAction $disableTwoFactorAction,
        protected ServiceInterface $twoFactorService,
    ) {}

    /**
     * Show user profile page.
     */
    public function showProfile(): View
    {
        $user = Auth::user();
        if ($user === null) {
            abort(401);
        }

        $isTwoFactorEnabled = $this->twoFactorService->isTwoFactorEnabled($user);

        return view('user.profile', [
            'user' => $user,
            'isTwoFactorEnabled' => $isTwoFactorEnabled,
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(UpdateUserRequest $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user === null) {
            abort(401);
        }

        $dto = UpdateUserDTO::fromArray($request->validated());
        $this->updateUserAction->execute($user->id, $dto);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update user password.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user === null) {
            abort(401);
        }

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $this->updateUserAction->execute($user->id, UpdateUserDTO::fromArray([
            'password' => $request->password,
        ]));

        return redirect()->route('profile.show')->with('success', 'Password updated successfully.');
    }

    /**
     * Setup 2FA for current user.
     */
    public function setupTwoFactor(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user === null) {
            abort(401);
        }

        try {
            $setup = $this->setupTwoFactorAction->execute($user);

            return redirect()->route('profile.show')
                ->with('twoFactorSetup', $setup)
                ->with('success', '2FA setup initiated. Please scan the QR code and verify.');
        } catch (Exception $e) {
            return back()->withErrors(['twoFactor' => $e->getMessage()]);
        }
    }

    /**
     * Disable 2FA for current user.
     */
    public function disableTwoFactor(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user === null) {
            abort(401);
        }

        try {
            $this->disableTwoFactorAction->execute($user);

            return redirect()->route('profile.show')->with('success', '2FA disabled successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['twoFactor' => $e->getMessage()]);
        }
    }

    /**
     * List all users (admin only).
     */
    public function index(Request $request): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $perPage = (int) $request->get('per_page', 15);
        $users = $this->getAllUsersAction->execute($perPage);

        return view('user.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show user details (admin only).
     */
    public function show(int $id): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $user = $this->getUserByIdAction->execute($id);
        $isTwoFactorEnabled = $this->twoFactorService->isTwoFactorEnabled($user);

        return view('user.show', [
            'user' => $user,
            'isTwoFactorEnabled' => $isTwoFactorEnabled,
        ]);
    }

    /**
     * Show create user form (admin only).
     */
    public function create(): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.create');
    }

    /**
     * Store new user (admin only).
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $dto = CreateUserDTO::fromArray($request->validated());
        $this->createUserAction->execute($dto);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show edit user form (admin only).
     */
    public function edit(int $id): View
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $user = $this->getUserByIdAction->execute($id);
        $isTwoFactorEnabled = $this->twoFactorService->isTwoFactorEnabled($user);

        return view('user.edit', [
            'user' => $user,
            'isTwoFactorEnabled' => $isTwoFactorEnabled,
        ]);
    }

    /**
     * Update user (admin only).
     */
    public function update(int $id, UpdateUserRequest $request): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $dto = UpdateUserDTO::fromArray($request->validated());
        $this->updateUserAction->execute($id, $dto);

        return redirect()->route('users.show', $id)->with('success', 'User updated successfully.');
    }

    /**
     * Delete user (admin only).
     */
    public function destroy(int $id): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $this->deleteUserAction->execute($id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Enable/Disable 2FA for user (admin only).
     */
    public function toggleTwoFactor(int $id, Request $request): RedirectResponse
    {
        if (! auth()->user()?->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $user = $this->getUserByIdAction->execute($id);
        $action = $request->input('action');

        try {
            if ($action === 'enable') {
                $this->setupTwoFactorAction->execute($user);
                $message = '2FA enabled for user.';
            } else {
                $this->disableTwoFactorAction->execute($user);
                $message = '2FA disabled for user.';
            }

            return redirect()->route('users.show', $id)->with('success', $message);
        } catch (Exception $e) {
            return back()->withErrors(['twoFactor' => $e->getMessage()]);
        }
    }
}
