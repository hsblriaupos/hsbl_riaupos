<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login.login_admin'); // View dengan 2 tab
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_type' => 'required|in:admin,student',
        ]);

        // Login Admin
        if ($request->login_type === 'admin') {
            $request->validate([
                'name' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('name', $request->name)->first();

            if ($user && Hash::check($request->password, $user->password) && $user->role === 'admin') {
                Auth::login($user, $request->remember ?? false);
                return redirect()->route('admin.dashboard');
            }

            return back()->withErrors([
                'name' => 'Username atau password salah!',
            ])->with('login_type', 'admin')->withInput();
        }
        
        // Login Student - redirect ke student login
        return redirect()->route('student.login')->with('login_type', 'student');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}