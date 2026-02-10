<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetLog; // Pastikan ini di-import
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')->orderBy('name');
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by temp password status
        if ($request->filled('has_temp')) {
            if ($request->has_temp === 'has_temp') {
                $query->whereNotNull('temp_password');
            } elseif ($request->has_temp === 'no_temp') {
                $query->whereNull('temp_password');
            }
        }
        
        $users = $query->paginate(20)->withQueryString();
        
        return view('admin.resetpassword', compact('users'));
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'temp_password' => 'required|string|min:8',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $user = User::findOrFail($request->user_id);
        
        // Cek apakah user memiliki role student
        if ($user->role !== 'student') {
            return back()->with('error', 'Hanya user dengan role student yang bisa direset passwordnya.');
        }
        
        $tempPassword = $request->temp_password;
        
        // Mulai database transaction
        DB::beginTransaction();
        
        try {
            // Update user password
            $user->temp_password = $tempPassword;
            $user->temp_password_created_at = now();
            $user->password = Hash::make($tempPassword);
            $user->password_reset_count = $user->password_reset_count + 1;
            $user->save();
            
            // Simpan log (TANPA email)
            PasswordResetLog::create([
                'admin_id' => auth()->id(),
                'user_id' => $user->id,
                'email' => $user->email,
                'new_password' => $tempPassword,
                'notes' => $request->notes,
                'ip_address' => $request->ip(),
                'email_sent' => false // Selalu false karena tidak dikirim email
            ]);
            
            DB::commit();
            
            // Return dengan data password untuk copy
            return back()->with([
                'success' => 'Password berhasil direset untuk ' . $user->name,
                'temp_password_show' => $tempPassword,
                'user_name' => $user->name,
                'user_email' => $user->email
            ]);
                         
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Password reset failed: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }
    
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|string',
            'password_length' => 'required|integer|min:8|max:16',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $userIds = explode(',', $request->user_ids);
        $passwordLength = $request->password_length;
        
        $resetCount = 0;
        $failedUsers = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($userIds as $userId) {
                try {
                    $user = User::findOrFail($userId);
                    
                    if ($user->role !== 'student') {
                        $failedUsers[] = $user->name . ' (bukan student)';
                        continue;
                    }
                    
                    // Generate unique password untuk setiap user
                    $tempPassword = Str::random($passwordLength);
                    
                    // Update user
                    $user->temp_password = $tempPassword;
                    $user->temp_password_created_at = now();
                    $user->password = Hash::make($tempPassword);
                    $user->password_reset_count = $user->password_reset_count + 1;
                    $user->save();
                    
                    // Simpan log (TANPA email)
                    PasswordResetLog::create([
                        'admin_id' => auth()->id(),
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'new_password' => $tempPassword,
                        'notes' => $request->notes . ' [BULK RESET]',
                        'ip_address' => $request->ip(),
                        'email_sent' => false
                    ]);
                    
                    $resetCount++;
                    
                } catch (\Exception $e) {
                    $failedUsers[] = "User ID {$userId}: " . $e->getMessage();
                }
            }
            
            DB::commit();
            
            $message = "Berhasil mereset password untuk {$resetCount} user.";
            
            if (!empty($failedUsers)) {
                $message .= " Gagal untuk " . count($failedUsers) . " user.";
                session()->flash('warning', implode('<br>', $failedUsers));
            }
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Bulk password reset failed: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal melakukan bulk reset: ' . $e->getMessage());
        }
    }
    
    public function logs()
    {
        $logs = PasswordResetLog::with(['admin', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.resetpassword-logs', compact('logs'));
    }
    
    public function exportLogs()
    {
        $logs = PasswordResetLog::with(['admin', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="password-reset-logs-' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['No', 'Date', 'Admin', 'User', 'Email', 'New Password', 'Notes', 'IP Address', 'Email Sent']);
            
            $counter = 1;
            foreach ($logs as $log) {
                fputcsv($file, [
                    $counter++,
                    $log->created_at->format('d/m/Y H:i'),
                    $log->admin->name ?? 'N/A',
                    $log->user->name ?? 'N/A',
                    $log->email,
                    $log->new_password,
                    $log->notes,
                    $log->ip_address,
                    $log->email_sent ? 'Yes' : 'No'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}