<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StudentProfileController extends Controller
{
    /**
     * Display the profile edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Hitung account age dalam hari
        $createdAt = $user->created_at;
        $now = now();
        $accountAgeDays = floor($createdAt->diffInDays($now));
        
        // Generate avatar URL jika tidak ada
        if (!$user->avatar || !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
            $seed = $user->email ?? $user->id ?? rand(1, 999999);
            $user->avatar = "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($seed) . "&backgroundColor=65c9ff,b6e3f4,c0aede,d1d4f9,ffd5dc,ffdfbf";
        }
        
        return view('user.event.profile.profile-edit', compact('user', 'accountAgeDays'));
    }

    /**
     * Display the profile page.
     */
    public function index()
    {
        return $this->edit();
    }

    /**
     * Determine if user is using temporary password
     * LOGIKA BARU: User menggunakan temp password jika:
     * 1. Ada temp_password di database
     * 2. DAN user BELUM PERNAH mengganti password (password_reset_count = 0 atau null)
     */
    private function isUsingTempPassword($user)
    {
        // 1. Cek jika ada temp_password
        if (empty($user->temp_password)) {
            return false;
        }
        
        // 2. Cek jika user sudah pernah ganti password
        // Jika password_reset_count > 0, berarti sudah ganti password
        if (($user->password_reset_count ?? 0) > 0) {
            return false;
        }
        
        // 3. Cek jika password_changed_at sudah ada (sudah pernah ganti password)
        if (!empty($user->password_changed_at)) {
            return false;
        }
        
        return true;
    }

    /**
     * Check if user has temporary password stored (even if not using it)
     */
    private function hasTempPassword($user)
    {
        return !empty($user->temp_password);
    }

    /**
     * Get the actual password type user is using for login
     */
    private function getCurrentPasswordType($user)
    {
        if ($this->isUsingTempPassword($user)) {
            return 'temp';
        }
        
        return 'main';
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 
                       Rule::unique('users')->ignore($user->id)],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered by another account.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors in the form.');
        }

        // Mulai transaction
        DB::beginTransaction();
        
        try {
            $changes = [];
            $passwordChanged = false;
            $tempPasswordCleared = false;
            
            // Check password status BEFORE update
            $isUsingTempPasswordBefore = $this->isUsingTempPassword($user);
            $hasTempPasswordBefore = $this->hasTempPassword($user);
            $currentPasswordType = $this->getCurrentPasswordType($user);
            
            // Update nama jika berubah
            if ($user->name !== $request->name) {
                $user->name = $request->name;
                $changes[] = 'name';
            }
            
            // Update email jika berubah
            if ($user->email !== $request->email) {
                $oldEmail = $user->email;
                $user->email = $request->email;
                
                // Reset email verification if email changed
                if ($oldEmail !== $request->email) {
                    $user->email_verified_at = null;
                }
                $changes[] = 'email';
            }
            
            // Handle password change jika ada password baru
            if ($request->filled('new_password')) {
                $newPassword = $request->new_password;
                
                // Validate password strength
                if (strlen($newPassword) < 8) {
                    throw new \Exception('Password must be at least 8 characters long.');
                }
                
                if (!preg_match('/[a-zA-Z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
                    throw new \Exception('Password must contain both letters and numbers.');
                }
                
                // Hash password baru dan set sebagai password utama
                $user->password = Hash::make($newPassword);
                $passwordChanged = true;
                
                // **PENTING: HAPUS TEMP PASSWORD saat user mengganti password**
                if ($hasTempPasswordBefore) {
                    $user->temp_password = null;
                    $user->temp_password_created_at = null;
                    $tempPasswordCleared = true;
                    
                    Log::info('Temp password cleared after password change', [
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);
                }
                
                // Update timestamps dan counter
                $user->password_changed_at = now();
                $user->password_reset_count = ($user->password_reset_count ?? 0) + 1;
                
                $changes[] = 'password';
                
                Log::info('User changed password', [
                    'user_id' => $user->id,
                    'was_using_temp' => $isUsingTempPasswordBefore,
                    'had_temp' => $hasTempPasswordBefore
                ]);
            } 
            // Jika user TIDAK mengganti password tapi MASIH menggunakan temp password
            elseif ($isUsingTempPasswordBefore) {
                // User memilih untuk tetap menggunakan temp password sebagai password utama
                // Kita tandai bahwa ini sudah menjadi password utama dengan meng-update counter
                $user->password_reset_count = 1;
                $user->password_changed_at = now();
                
                Log::info('User keeping temp password as main password', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }
            
            // Simpan perubahan
            $user->save();
            
            // Commit transaction
            DB::commit();
            
            // Check status AFTER update
            $isUsingTempPasswordAfter = $this->isUsingTempPassword($user);
            $hasTempPasswordAfter = $this->hasTempPassword($user);
            
            // Prepare success message
            $successMessage = 'Profile updated successfully.';
            
            if (in_array('name', $changes)) {
                $successMessage .= ' Name updated.';
            }
            
            if (in_array('email', $changes)) {
                $successMessage .= ' Email updated.';
                if (!$user->email_verified_at) {
                    $successMessage .= ' Please verify your new email address.';
                }
            }
            
            if ($passwordChanged) {
                if ($isUsingTempPasswordBefore) {
                    $successMessage .= ' Temporary password has been replaced with your new permanent password.';
                } else {
                    $successMessage .= ' Password updated successfully.';
                }
            } elseif ($isUsingTempPasswordBefore && !$isUsingTempPasswordAfter) {
                $successMessage .= ' Your temporary password is now set as your permanent password.';
            }

            // Jika request JSON (AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'has_temp_password' => $hasTempPasswordAfter,
                    'is_using_temp_password' => $isUsingTempPasswordAfter,
                    'was_using_temp_password' => $isUsingTempPasswordBefore,
                    'temp_password_cleared' => $tempPasswordCleared,
                    'password_changed' => $passwordChanged,
                    'changes' => $changes,
                    'new_password_type' => $this->getCurrentPasswordType($user)
                ]);
            }
            
            return redirect()->route('student.profile.edit')
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollBack();
            
            Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e
            ]);
            
            $errorMessage = $e->getMessage() ?: 'An error occurred while updating your profile. Please try again.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    /**
     * Get current password info for form (AJAX endpoint).
     */
    public function getCurrentPasswordInfo()
    {
        $user = Auth::user();
        $isUsingTempPassword = $this->isUsingTempPassword($user);
        $hasTempPassword = $this->hasTempPassword($user);
        $currentPasswordType = $this->getCurrentPasswordType($user);
        
        return response()->json([
            'success' => true,
            'has_temp_password' => $hasTempPassword,
            'is_using_temp_password' => $isUsingTempPassword,
            'current_password_type' => $currentPasswordType,
            'temp_password_created_at' => $user->temp_password_created_at 
                ? $user->temp_password_created_at->format('Y-m-d H:i:s')
                : null,
            'password_reset_count' => $user->password_reset_count ?? 0,
            'password_changed_at' => $user->password_changed_at 
                ? $user->password_changed_at->format('Y-m-d H:i:s')
                : null,
            'requires_password_change' => $isUsingTempPassword
        ]);
    }

    /**
     * Verify password (optional - for manual verification if needed)
     */
    public function verifyPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = Auth::user();
        $currentPasswordType = $this->getCurrentPasswordType($user);
        
        // Check against appropriate password
        if ($currentPasswordType === 'temp') {
            // Verify against temp password
            $passwordValid = Hash::check($request->password, $user->temp_password);
        } else {
            // Verify against main password
            $passwordValid = Hash::check($request->password, $user->password);
        }
        
        return response()->json([
            'success' => true,
            'valid' => $passwordValid,
            'password_type' => $currentPasswordType,
            'message' => $passwordValid 
                ? ($currentPasswordType === 'temp' 
                    ? 'Temporary password verified.' 
                    : 'Password verified.')
                : ($currentPasswordType === 'temp' 
                    ? 'Temporary password is incorrect.' 
                    : 'Password is incorrect.')
        ]);
    }

    /**
     * Update only password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $isUsingTempPassword = $this->isUsingTempPassword($user);
        $hasTempPassword = $this->hasTempPassword($user);

        DB::beginTransaction();
        
        try {
            // Update password baru
            $user->password = Hash::make($request->new_password);
            
            // **HAPUS TEMP PASSWORD jika ada (INI PENTING!)**
            $tempPasswordCleared = false;
            if ($hasTempPassword) {
                $user->temp_password = null;
                $user->temp_password_created_at = null;
                $tempPasswordCleared = true;
            }
            
            // Update timestamps dan counter
            $user->password_changed_at = now();
            $user->password_reset_count = ($user->password_reset_count ?? 0) + 1;
            
            $user->save();
            
            DB::commit();

            $message = 'Password successfully updated.';
            if ($tempPasswordCleared) {
                $message .= ' Temporary password has been permanently removed.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'has_temp_password' => false,
                'is_using_temp_password' => false,
                'temp_password_cleared' => $tempPasswordCleared,
                'password_reset_count' => $user->password_reset_count
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Password update error: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating your password.'
            ], 500);
        }
    }

    /**
     * Generate a verification token for auto password verification
     */
    public function generatePasswordToken()
    {
        $user = Auth::user();
        $currentPasswordType = $this->getCurrentPasswordType($user);
        
        // Generate simple token (timestamp + random string)
        $token = time() . '_' . Str::random(16);
        
        // Store in session (valid for 10 minutes)
        session([
            'password_verification_token' => $token,
            'password_verification_time' => time(),
            'password_type' => $currentPasswordType
        ]);
        
        return response()->json([
            'success' => true,
            'token' => $token,
            'password_type' => $currentPasswordType,
            'message' => 'Auto-verification enabled'
        ]);
    }

    /**
     * Verify auto token
     */
    public function verifyAutoToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = Auth::user();
        $storedToken = session('password_verification_token');
        $verificationTime = session('password_verification_time');
        
        if (!$storedToken || !$verificationTime) {
            return response()->json([
                'success' => false,
                'message' => 'No verification session found.'
            ], 401);
        }
        
        // Cek jika token sama
        if ($storedToken !== $request->token) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification token.'
            ], 401);
        }
        
        // Cek waktu (token valid selama 10 menit)
        $timeDiff = now()->timestamp - $verificationTime;
        if ($timeDiff > 600) { // 10 menit
            session()->forget(['password_verification_token', 'password_verification_time']);
            return response()->json([
                'success' => false,
                'message' => 'Verification token expired.'
            ], 401);
        }
        
        // Jika semua validasi lolos
        $currentPasswordType = $this->getCurrentPasswordType($user);
        
        return response()->json([
            'success' => true,
            'valid' => true,
            'password_type' => $currentPasswordType,
            'message' => 'Auto verification successful.'
        ]);
    }

    /**
     * Check if user has temporary password.
     */
    public function checkTempPassword()
    {
        $user = Auth::user();
        $isUsingTempPassword = $this->isUsingTempPassword($user);
        $hasTempPassword = $this->hasTempPassword($user);
        $currentPasswordType = $this->getCurrentPasswordType($user);
        
        return response()->json([
            'success' => true,
            'has_temp_password' => $hasTempPassword,
            'is_using_temp_password' => $isUsingTempPassword,
            'current_password_type' => $currentPasswordType,
            'temp_password_created_at' => $user->temp_password_created_at 
                ? $user->temp_password_created_at->format('Y-m-d H:i:s')
                : null,
            'password_reset_count' => $user->password_reset_count ?? 0,
            'password_changed_at' => $user->password_changed_at 
                ? $user->password_changed_at->format('Y-m-d H:i:s')
                : null,
            'requires_password_change' => $isUsingTempPassword
        ]);
    }

    /**
     * Handle email verification for changed email.
     */
    public function sendVerificationEmail(Request $request)
    {
        $user = Auth::user();
        
        // Kirim email verifikasi jika email belum terverifikasi
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            
            $message = 'Verification email has been sent. Please check your inbox.';
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return redirect()->back()->with('success', $message);
        }
        
        $message = 'Your email is already verified.';
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }
        
        return redirect()->back()->with('info', $message);
    }

    /**
     * Check if email already exists (AJAX endpoint).
     */
    public function checkEmailAvailability(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $email = $request->email;
        
        // Cek jika email sudah digunakan oleh user lain
        $exists = User::where('email', $email)
            ->where('id', '!=', $user->id)
            ->exists();
        
        return response()->json([
            'success' => true,
            'available' => !$exists,
            'message' => $exists ? 'This email is already registered.' : 'Email is available.'
        ]);
    }

    /**
     * Validate password strength (AJAX endpoint).
     */
    public function validatePasswordStrength(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $password = $request->password;
        $strength = 0;
        $messages = [];
        
        // Check length
        if (strlen($password) >= 8) {
            $strength += 25;
        } else {
            $messages[] = 'Minimum 8 characters';
        }
        
        // Check uppercase
        if (preg_match('/[A-Z]/', $password)) {
            $strength += 25;
        } else {
            $messages[] = 'At least one uppercase letter';
        }
        
        // Check lowercase
        if (preg_match('/[a-z]/', $password)) {
            $strength += 25;
        } else {
            $messages[] = 'At least one lowercase letter';
        }
        
        // Check number
        if (preg_match('/[0-9]/', $password)) {
            $strength += 25;
        } else {
            $messages[] = 'At least one number';
        }
        
        // Determine strength level
        $level = 'weak';
        if ($strength >= 100) {
            $level = 'strong';
        } elseif ($strength >= 75) {
            $level = 'good';
        } elseif ($strength >= 50) {
            $level = 'fair';
        }
        
        return response()->json([
            'success' => true,
            'strength' => $strength,
            'level' => $level,
            'messages' => $messages,
            'is_valid' => $strength >= 100
        ]);
    }

    /**
     * Get user profile data (AJAX endpoint).
     */
    public function getProfileData()
    {
        $user = Auth::user();
        $isUsingTempPassword = $this->isUsingTempPassword($user);
        $hasTempPassword = $this->hasTempPassword($user);
        $currentPasswordType = $this->getCurrentPasswordType($user);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'account_age_days' => floor($user->created_at->diffInDays(now())),
                'password_reset_count' => $user->password_reset_count ?? 0,
                'password_changed_at' => $user->password_changed_at,
                'has_temp_password' => $hasTempPassword,
                'is_using_temp_password' => $isUsingTempPassword,
                'current_password_type' => $currentPasswordType,
                'temp_password_created_at' => $user->temp_password_created_at,
            ]
        ]);
    }

    /**
     * Upload profile picture.
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ], [
            'avatar.required' => 'Please select an image.',
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'Only JPEG, PNG, JPG, and GIF images are allowed.',
            'avatar.max' => 'Image size must not exceed 2MB.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        
        DB::beginTransaction();
        
        try {
            // Hapus avatar lama jika ada dan bukan dari URL eksternal
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            
            // Upload avatar baru
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Update user avatar
            $user->avatar = $path;
            $user->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully.',
                'avatar_url' => asset('storage/' . $path)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Avatar upload error: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload profile picture. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove profile picture.
     */
    public function removeAvatar()
    {
        $user = Auth::user();
        
        DB::beginTransaction();
        
        try {
            // Hapus file dari storage jika ada
            if ($user->avatar && !filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }
            
            // Reset ke avatar default
            $seed = $user->email ?? $user->id ?? rand(1, 999999);
            $user->avatar = "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($seed) . "&backgroundColor=65c9ff,b6e3f4,c0aede,d1d4f9,ffd5dc,ffdfbf";
            $user->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Profile picture removed successfully.',
                'avatar_url' => $user->avatar
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Avatar remove error: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove profile picture. Please try again.'
            ], 500);
        }
    }
}