<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Example: ->middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Ambil slug role langsung tanpa relasi model (lebih cepat & tidak trigger accessor)
        $userRoles = DB::table('roles')
            ->join('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('user_roles.user_id', $user->id)
            ->pluck('roles.slug')
            ->toArray();

        if (empty($userRoles)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (! array_intersect($userRoles, $roles)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
