<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Tambahkan baris ini

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // Tambahkan HasRoles di sini

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar', // ✅ Sudah ada
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ✅ TAMBAHKAN: Get the avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            // ✅ PASTIKAN: Path sudah benar (tanpa 'storage/' diawal)
            // Jika di database: 'avatars/students/filename.jpg'
            // Maka: asset('storage/avatars/students/filename.jpg')
            return asset('storage/' . $this->avatar);
        }
        
        // Fallback ke avatar generator
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1565c0&color=fff';
    }

    /**
     * ✅ TAMBAHKAN: Check if user has avatar
     */
    public function hasAvatar()
    {
        return !empty($this->avatar) && file_exists(storage_path('app/public/' . $this->avatar));
    }

    /**
     * ✅ TAMBAHKAN: Get user role name
     */
    public function getRoleNameAttribute()
    {
        return ucfirst($this->role);
    }

    /**
     * ✅ TAMBAHKAN: Scope untuk mencari student
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    /**
     * ✅ TAMBAHKAN: Scope untuk mencari admin
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * ✅ TAMBAHKAN: Check if user is student
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * ✅ TAMBAHKAN: Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * ✅ TAMBAHKAN: Get display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->name ?: $this->email;
    }

    /**
     * ✅ TAMBAHKAN: Get initial for avatar
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            $initials = strtoupper(substr($this->name, 0, 2));
        }
        
        return $initials;
    }
}