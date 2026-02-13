<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //  user must be logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user(); // ✅ now IDE understands better

        //  avoid "property does not exist" warnings
        if (!$user || !($user->is_admin ?? false)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
