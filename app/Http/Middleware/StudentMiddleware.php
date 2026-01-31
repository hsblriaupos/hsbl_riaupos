<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentMiddleware
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

        if ($user->role !== 'student') {
            return redirect()->route('admin.dashboard')->withErrors([
                'error' => 'Akses hanya untuk siswa.'
            ]);
        }

        return $next($request);
    }
}