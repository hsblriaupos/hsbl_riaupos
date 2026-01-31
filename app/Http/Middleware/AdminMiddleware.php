<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->withErrors([
                'error' => 'Silakan login terlebih dahulu.'
            ]);
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('login.form')->withErrors([
                'error' => 'Akses hanya untuk administrator.'
            ]);
        }

        return $next($request);
    }
}
