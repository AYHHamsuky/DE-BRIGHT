<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Usage: ->middleware('role:super_admin,admin')
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // A deactivated account is logged out on its next request.
        if ($user && ! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route($request->is('portal*') ? 'portal.login' : 'admin.login')
                ->withErrors(['login' => 'This account has been deactivated. Please contact the school.']);
        }

        $allowed = array_map(fn (string $role) => Role::from($role), $roles);

        if (! $user || ! $user->hasRole(...$allowed)) {
            abort(403, 'You do not have access to this page.');
        }

        return $next($request);
    }
}
