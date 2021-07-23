<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;

class AdminRole
{
    /*
        Allow access for students only
    */
    public function handle($request, Closure $next)
    {
        if ($request->getMethod() === "OPTIONS") {
            return $next($request);
        }
        $user = Auth::user();
        if ($user->is_admin) {
            return $next($request);
        }
        return response()->json(['message' => ["Unauthorized"]], 401);
    }
}
