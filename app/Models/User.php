<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'temp_password',
        'temp_password_created_at',
        'password_changed_at',
        'password_reset_count',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'temp_password', // Sembunyikan dari API/response
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'temp_password_created_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'password_reset_count' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'avatar_url',
        'has_temp_password',
        'temp_password_age',
        'is_temp_password_expired',
        'password_status_badge',
        'formatted_password_changed_at',
        'initials',
        'display_name',
        'role_name',
    ];

    // ========== RELATIONSHIPS ==========

    /**
     * Relasi ke PasswordResetLog (sebagai user yang di-reset)
     */
    public function passwordResetLogs(): HasMany
    {
        return $this->hasMany(PasswordResetLog::class, 'user_id');
    }

    /**
     * Relasi ke PasswordResetLog (sebagai admin yang mereset)
     */
    public function adminPasswordResets(): HasMany
    {
        return $this->hasMany(PasswordResetLog::class, 'admin_id');
    }

    // ========== ACCESSORS ==========

    /**
     * Get the avatar URL
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(storage_path('app/public/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1565c0&color=fff';
    }

    /**
     * Check if user has temporary password
     */
    public function getHasTempPasswordAttribute(): bool
    {
        return !empty($this->temp_password);
    }

    /**
     * Get temporary password age in hours
     */
    public function getTempPasswordAgeAttribute(): ?int
    {
        if (!$this->temp_password_created_at) {
            return null;
        }
        
        return now()->diffInHours($this->temp_password_created_at);
    }

    /**
     * Check if temporary password is expired (older than 24 hours)
     */
    public function getIsTempPasswordExpiredAttribute(): bool
    {
        if (!$this->temp_password_created_at) {
            return false;
        }
        
        return now()->diffInHours($this->temp_password_created_at) > 24;
    }

    /**
     * Get password status badge (UPDATED untuk match dengan blade)
     */
    public function getPasswordStatusBadgeAttribute(): string
    {
        if ($this->has_temp_password) {
            if ($this->is_temp_password_expired) {
                return '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Expired</span>';
            }
            return '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Temporary</span>';
        }
        
        return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Permanent</span>';
    }

    /**
     * Get formatted last password change
     */
    public function getFormattedPasswordChangedAtAttribute(): string
    {
        if (!$this->password_changed_at) {
            return 'Never changed';
        }
        
        return $this->password_changed_at->format('d/m/Y H:i');
    }

    /**
     * Get user role name
     */
    public function getRoleNameAttribute(): string
    {
        return ucfirst($this->role);
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: $this->email;
    }

    /**
     * Get initial for avatar
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } elseif (!empty($this->name)) {
            $initials = strtoupper(substr($this->name, 0, 2));
        } else {
            $initials = 'US';
        }
        
        return $initials;
    }

    /**
     * Check if user has avatar
     */
    public function hasAvatar(): bool
    {
        return !empty($this->avatar) && file_exists(storage_path('app/public/' . $this->avatar));
    }

    /**
     * Get last password reset log
     */
    public function getLastPasswordResetAttribute()
    {
        return $this->passwordResetLogs()->latest()->first();
    }

    /**
     * Get password reset count badge
     */
    public function getPasswordResetCountBadgeAttribute(): string
    {
        return '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">' . $this->password_reset_count . '</span>';
    }

    /**
     * Get user status badge based on temp password
     */
    public function getUserStatusBadgeAttribute(): string
    {
        if ($this->temp_password) {
            $age = $this->temp_password_age;
            if ($age === null) {
                return '<span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">Active</span>';
            } elseif ($age <= 1) {
                return '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">New Reset</span>';
            } elseif ($age <= 24) {
                return '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Recent Reset</span>';
            } else {
                return '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Needs Change</span>';
            }
        }
        
        return '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Active</span>';
    }

    // ========== MUTATORS ==========

    /**
     * Set the user's name.
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = ucwords(strtolower(trim($value)));
    }

    /**
     * Set the user's email.
     */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    // ========== SCOPES ==========

    /**
     * Scope untuk mencari student
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    /**
     * Scope untuk mencari admin
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope untuk user dengan temp password
     */
    public function scopeWithTempPassword($query)
    {
        return $query->whereNotNull('temp_password');
    }

    /**
     * Scope untuk user tanpa temp password
     */
    public function scopeWithoutTempPassword($query)
    {
        return $query->whereNull('temp_password');
    }

    /**
     * Scope untuk user dengan temp password expired
     */
    public function scopeWithExpiredTempPassword($query)
    {
        return $query->whereNotNull('temp_password_created_at')
                    ->where('temp_password_created_at', '<=', now()->subHours(24));
    }

    /**
     * Scope untuk user yang perlu reset password
     */
    public function scopeNeedsPasswordReset($query)
    {
        return $query->where(function($q) {
            $q->whereNotNull('temp_password')
              ->where('temp_password_created_at', '<=', now()->subHours(24));
        });
    }

    /**
     * Scope untuk mencari berdasarkan keyword
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('email', 'like', "%{$keyword}%");
        });
    }

    // ========== METHODS ==========

    /**
     * Check if user is student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Clear temporary password
     */
    public function clearTempPassword(): bool
    {
        $this->temp_password = null;
        $this->temp_password_created_at = null;
        $this->password_changed_at = now();
        
        return $this->save();
    }

    /**
     * Set temporary password
     */
    public function setTempPassword(string $password): bool
    {
        $this->temp_password = $password;
        $this->temp_password_created_at = now();
        $this->password = \Illuminate\Support\Facades\Hash::make($password);
        $this->password_reset_count = ($this->password_reset_count ?: 0) + 1;
        
        return $this->save();
    }

    /**
     * Check if user needs to change password
     */
    public function needsPasswordChange(): bool
    {
        // Jika ada temp password dan belum pernah ganti password
        if ($this->has_temp_password && !$this->password_changed_at) {
            return true;
        }
        
        // Jika temp password sudah expired
        if ($this->has_temp_password && $this->is_temp_password_expired) {
            return true;
        }
        
        return false;
    }

    /**
     * Get user info for display
     */
    public function getUserInfoArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'role_name' => $this->role_name,
            'has_temp_password' => $this->has_temp_password,
            'temp_password_age' => $this->temp_password_age,
            'is_temp_password_expired' => $this->is_temp_password_expired,
            'password_reset_count' => $this->password_reset_count,
            'last_password_change' => $this->formatted_password_changed_at,
            'avatar_url' => $this->avatar_url,
            'initials' => $this->initials,
            'display_name' => $this->display_name,
        ];
    }

    /**
     * Get user info for reset password modal
     */
    public function getResetPasswordInfo(): array
    {
        return [
            'user_id' => $this->id,
            'user_name' => $this->name,
            'user_email' => $this->email,
            'has_temp_password' => $this->has_temp_password,
            'temp_password_created_at' => $this->temp_password_created_at 
                ? $this->temp_password_created_at->format('d/m/Y H:i') 
                : null,
            'password_reset_count' => $this->password_reset_count,
        ];
    }

    /**
     * Increment password reset count
     */
    public function incrementResetCount(): bool
    {
        $this->password_reset_count = ($this->password_reset_count ?: 0) + 1;
        return $this->save();
    }

    /**
     * Mark password as changed
     */
    public function markPasswordAsChanged(): bool
    {
        $this->temp_password = null;
        $this->temp_password_created_at = null;
        $this->password_changed_at = now();
        return $this->save();
    }

    /**
     * Get statistics for user
     */
    public function getPasswordStatistics(): array
    {
        $totalResets = $this->password_reset_count ?: 0;
        $lastReset = $this->last_password_reset;
        
        return [
            'total_resets' => $totalResets,
            'last_reset_date' => $lastReset ? $lastReset->created_at->format('d/m/Y H:i') : 'Never',
            'last_reset_by' => $lastReset && $lastReset->admin ? $lastReset->admin->name : 'N/A',
            'has_temp_password' => $this->has_temp_password,
            'temp_password_age_hours' => $this->temp_password_age,
            'temp_password_expired' => $this->is_temp_password_expired,
            'needs_password_change' => $this->needsPasswordChange(),
        ];
    }

    /**
     * Validate if user can have password reset
     */
    public function canHavePasswordReset(): array
    {
        $canReset = true;
        $messages = [];

        if (!$this->isStudent()) {
            $canReset = false;
            $messages[] = 'Only student users can have their passwords reset';
        }

        if ($this->has_temp_password && !$this->is_temp_password_expired) {
            $canReset = false;
            $messages[] = 'User already has a temporary password that is not expired';
        }

        return [
            'can_reset' => $canReset,
            'messages' => $messages,
            'user_info' => $this->getUserInfoArray(),
        ];
    }
}