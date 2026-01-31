<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // ✅ TAMBAHKAN INI

class StudentAuthController extends Controller
{
    /**
     * Process student login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ])->with('login_type', 'student')->withInput();
        }

        if ($user->role !== 'student') {
            return back()->withErrors([
                'email' => 'Email ini tidak terdaftar sebagai siswa.',
            ])->with('login_type', 'student')->withInput();
        }

        if (Auth::attempt($credentials, $request->remember ?? false)) {
            $request->session()->regenerate();
            
            // ✅ UBAH INI: Redirect ke halaman pilihan team setelah login sukses
            return redirect()->route('form.team.choice');
        }

        return back()->withErrors([
            'password' => 'Password salah.',
        ])->with('login_type', 'student')->withInput();
    }

    /**
     * Show student registration form
     */
    public function showRegisterForm()
    {
        return view('login.student_register');
    }

    /**
     * Process student registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // ✅ TAMBAHKAN webp
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('login_type', 'student');
        }

        // Upload avatar - ✅ PERBAIKI LOGIKA INI
        $avatarPath = null;
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            $avatar = $request->file('avatar');
            
            // ✅ Pastikan nama file aman dan unik
            $originalName = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $avatar->getClientOriginalExtension();
            $avatarName = Str::slug($originalName) . '_' . time() . '.' . $extension;
            
            // ✅ Simpan dengan path yang benar
            $avatarPath = $avatar->storeAs('avatars/students', $avatarName, 'public');
            
            // ✅ DEBUG: Log untuk memastikan file tersimpan
            \Log::info('Avatar uploaded: ' . $avatarPath);
            \Log::info('File exists: ' . (Storage::disk('public')->exists($avatarPath) ? 'YES' : 'NO'));
        }

        // ✅ Create user student dan SIMPAN user object untuk debugging
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
            'avatar'   => $avatarPath, // ✅ Pastikan ini tersimpan
        ]);

        // ✅ DEBUG: Log user yang dibuat
        \Log::info('User created:', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null
        ]);

        // Redirect ke halaman login dengan tab student aktif
        return redirect()
            ->route('login.form')
            ->with('success', '🎉 Registrasi berhasil! Silakan login dengan email dan password Anda.')
            ->with('login_type', 'student')
            ->with('registered_email', $request->email)
            ->with('debug_avatar_path', $avatarPath); // ✅ Tambahkan untuk debugging
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('login.student_forgot_password');
    }

    /**
     * Process forgot password request
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        return back()->with('status', 'Jika email terdaftar, Anda akan menerima link reset password.');
    }

    /**
     * Student logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}