<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Ensures the authenticated user has one of the allowed roles.
     * Matches against the user's role slug/name regardless of
     * separator style (e.g. restaurant_owner == restaurant-owner).
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->role) {
            abort(403);
        }

        $normalize = fn (string $value): string => strtolower(preg_replace('/[\s_-]+/', '', $value));

        $allowed = array_map($normalize, $roles);
        $userRole = $normalize($user->role->slug);

        if (! in_array($userRole, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}