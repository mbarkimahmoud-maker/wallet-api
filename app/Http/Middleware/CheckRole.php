<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        //check utilisateur connecte
        if (!auth('api')->check()) {
            return response()->json([
                'message' => 'Unauthorized - please login first'
            ], 401);
        }

        // user role from JWT
        $userRole = auth('api')->user()->role;

        // role permit 
        if (!in_array($userRole, $roles)) {
            return response()->json([
                'message' => 'Forbidden - you do not have permission'
            ], 403);
        }

        // continue to the controller or the next middleware
        return $next($request);
    }
}
