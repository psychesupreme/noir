<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * Verify the authenticated user's account tier matches at least one
     * of the specified roles. Usage: middleware('role:admin,staff')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Authentication required.');
        }

        $allowedRoles = array_map(
            fn (string $role): UserRole => UserRole::from($role),
            $roles,
        );

        if (! in_array($user->account_tier, $allowedRoles, true)) {
            abort(403, 'You do not have the required permissions to access this resource.');
        }

        return $next($request);
    }
}
