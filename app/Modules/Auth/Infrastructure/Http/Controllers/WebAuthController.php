<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Auth\Application\Actions\LoginAction;
use App\Modules\Auth\Infrastructure\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WebAuthController extends Controller
{
    public function __construct(
        protected LoginAction $loginAction,
    ) {}

    /**
     * Show the login form.
     */
    public function show(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        // Use Laravel's built-in authentication for web sessions
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => __('auth.failed')])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        // Store user data in session for easy access
        $user = Auth::user();
        if ($user !== null) {
            $request->session()->put('user', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }

        return redirect()->intended('/');
    }

    /**
     * Handle logout request.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.login');
    }
}
