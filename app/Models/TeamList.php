<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamList extends Model
{
    use HasFactory;

    protected $table = 'team_list';

    protected $primaryKey = 'team_id';
    
    public $incrementing = true;
    
    protected $keyType = 'int';

    protected $fillable = [
        'school_id',
        'school_name',
        'school_logo', // TAMBAHKAN INI
        'referral_code',
        'season',
        'series',
        'competition',
        'team_category',
        'team_name', // TAMBAHKAN INI jika ada
        'registered_by',
        'locked_status',
        'verification_status',
        'recommendation_letter',
        'payment_proof',
        'payment_status',
        'koran',
        'created_at', // TAMBAHKAN jika tidak otomatis
        'updated_at', // TAMBAHKAN jika tidak otomatis
    ];
    
    // PERBAIKAN: Cast untuk enum dengan nilai default
    protected $casts = [
        'locked_status' => 'string',
        'verification_status' => 'string',
        'payment_status' => 'string',
    ];
    
    // PERBAIKAN: Atribut default
    protected $attributes = [
        'locked_status' => 'unlocked',
        'verification_status' => 'unverified',
        'payment_status' => 'pending',
    ];
    
    // PERBAIKAN: Timestamps default true
    public $timestamps = true;
    
    // PERBAIKAN: Date casts
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    
    public function officials()
    {
        return $this->hasMany(OfficialList::class, 'team_id', 'team_id');
    }
    
    public static function generateReferralCode($schoolName)
    {
        return strtoupper(substr(md5($schoolName . time()), 0, 8));
    }
    
    public function players()
    {
        return $this->hasMany(PlayerList::class, 'team_id', 'team_id');
    }
    
    public function hasReachedPlayerLimit()
    {
        return $this->players()->count() >= 12;
    }
    
    public function leader()
    {
        return $this->belongsTo(User::class, 'registered_by', 'id');
    }
    
    // PERBAIKAN: Tambahkan aksesor untuk URL logo
    public function getSchoolLogoUrlAttribute()
    {
        if (!$this->school_logo) {
            return null;
        }
        
        // Cek apakah logo ada di storage atau public
        $publicPath = public_path('uploads/school_logo/' . $this->school_logo);
        $storagePath = storage_path('app/public/uploads/school_logo/' . $this->school_logo);
        
        if (file_exists($publicPath)) {
            return asset('uploads/school_logo/' . $this->school_logo);
        } elseif (file_exists($storagePath)) {
            return asset('storage/uploads/school_logo/' . $this->school_logo);
        }
        
        return null;
    }
    
    // PERBAIKAN: Tambahkan aksesor untuk status label
    public function getVerificationStatusLabelAttribute()
    {
        $statuses = [
            'unverified' => 'Belum Diverifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
        ];
        
        return $statuses[$this->verification_status] ?? $this->verification_status;
    }
    
    public function getLockedStatusLabelAttribute()
    {
        $statuses = [
            'unlocked' => 'Terbuka',
            'locked' => 'Terkunci',
        ];
        
        return $statuses[$this->locked_status] ?? $this->locked_status;
    }
    
    // PERBAIKAN: Scope untuk filter yang sering digunakan
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }
    
    public function scopeUnverified($query)
    {
        return $query->where('verification_status', 'unverified');
    }
    
    public function scopeLocked($query)
    {
        return $query->where('locked_status', 'locked');
    }
    
    public function scopeUnlocked($query)
    {
        return $query->where('locked_status', 'unlocked');
    }
    
    // PERBAIKAN: Cek apakah tim memiliki logo
    public function hasLogo()
    {
        if (!$this->school_logo) {
            return false;
        }
        
        $publicPath = public_path('uploads/school_logo/' . $this->school_logo);
        $storagePath = storage_path('app/public/uploads/school_logo/' . $this->school_logo);
        
        return file_exists($publicPath) || file_exists($storagePath);
    }
    
    // PERBAIKAN: Get total players count
    public function getPlayersCountAttribute()
    {
        return $this->players()->count();
    }
    
    // PERBAIKAN: Get total officials count
    public function getOfficialsCountAttribute()
    {
        return $this->officials()->count();
    }
    
    // PERBAIKAN: Is team complete (has minimum players)
    public function isComplete()
    {
        return $this->players()->count() >= 5; // Minimum 5 pemain
    }
}