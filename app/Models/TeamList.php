<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class TeamList extends Model
{
    use HasFactory;

    protected $table = 'team_list';
    protected $primaryKey = 'team_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'school_id',
        'school_name',
        'school_logo',
        'referral_code',
        'season',
        'series',
        'competition',
        'team_category',
        'team_name',
        'registered_by',
        'locked_status',
        'verification_status',
        'recommendation_letter',
        'payment_proof',
        'payment_status',
        'koran',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'locked_status' => 'string',
        'verification_status' => 'string',
        'payment_status' => 'string',
    ];

    protected $attributes = [
        'locked_status' => 'unlocked',
        'verification_status' => 'unverified',
        'payment_status' => 'pending',
    ];

    /* ================= RELATION ================= */

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function players()
    {
        return $this->hasMany(PlayerList::class, 'team_id', 'team_id');
    }

    public function officials()
    {
        return $this->hasMany(OfficialList::class, 'team_id', 'team_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'registered_by', 'id');
    }

    /* ================= BUSINESS LOGIC ================= */

    public static function generateReferralCode($schoolName)
    {
        return strtoupper(substr(md5($schoolName . time()), 0, 8));
    }

    public function hasReachedPlayerLimit()
    {
        return $this->players()->count() >= 12;
    }

    public function isComplete()
    {
        return $this->players()->count() >= 5;
    }

    /* ================= ACCESSOR ================= */

    /**
     * Accessor URL logo sekolah/tim (SATU-SATUNYA)
     */
    public function getSchoolLogoUrlAttribute()
    {
        // 1️⃣ Logo khusus tim
        if ($this->school_logo) {
            if (Storage::exists($this->school_logo)) {
                return Storage::url($this->school_logo);
            }

            $publicPath = public_path('uploads/school_logo/' . $this->school_logo);
            if (file_exists($publicPath)) {
                return asset('uploads/school_logo/' . $this->school_logo);
            }
        }

        // 2️⃣ Logo dari tabel school
        if ($this->school && $this->school->school_logo) {
            return Storage::url($this->school->school_logo);
        }

        // 3️⃣ Default
        return asset('images/default-school-logo.png');
    }

    public function getVerificationStatusLabelAttribute()
    {
        return [
            'unverified' => 'Belum Diverifikasi',
            'verified'   => 'Terverifikasi',
            'rejected'   => 'Ditolak',
        ][$this->verification_status] ?? $this->verification_status;
    }

    public function getLockedStatusLabelAttribute()
    {
        return [
            'unlocked' => 'Terbuka',
            'locked'   => 'Terkunci',
        ][$this->locked_status] ?? $this->locked_status;
    }

    public function getPlayersCountAttribute()
    {
        return $this->players()->count();
    }

    public function getOfficialsCountAttribute()
    {
        return $this->officials()->count();
    }

    /* ================= SCOPES ================= */

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

    /* ================= HELPERS ================= */

    public function hasLogo()
    {
        return !empty($this->school_logo);
    }

    public function hasCustomLogo()
    {
        return !empty($this->school_logo);
    }
}
