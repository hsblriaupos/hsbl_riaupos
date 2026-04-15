<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        }
        
        $isAuthRoute = $request->is('admin/*') || 
                       $request->is('student/*') || 
                       $request->is('user/*') ||
                       $request->routeIs('admin.*') ||
                       $request->routeIs('student.*');
        
        if ($isAuthRoute) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Session expired',
                    'redirect' => route('login.form')
                ], 401);
            }
            
            return redirect()->route('login.form')
                ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }
        
        return $next($request);
    }
}