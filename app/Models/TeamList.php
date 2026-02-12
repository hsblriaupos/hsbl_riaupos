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
        'koran',
        'jersey_home',        // 🔥 TAMBAHKAN
        'jersey_away',        // 🔥 TAMBAHKAN
        'jersey_alternate',   // 🔥 TAMBAHKAN
        'payment_proof',
        'payment_status',
        'is_leader_paid',
        'payment_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'locked_status' => 'string',
        'verification_status' => 'string',
        'payment_status' => 'string',
        'is_leader_paid' => 'boolean',
        'payment_date' => 'datetime',
    ];

    protected $attributes = [
        'locked_status' => 'unlocked',
        'verification_status' => 'unverified',
        'payment_status' => 'pending',
        'is_leader_paid' => false,
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

    public function dancers()
    {
        return $this->hasMany(DancerList::class, 'team_id', 'team_id');
    }

    public function leader()
    {
        return $this->belongsTo(PlayerList::class, 'registered_by', 'name')
            ->where('role', 'Leader');
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
     * Accessor URL logo sekolah/tim
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

    /**
     * 🔥 Accessor URL Jersey Home
     */
    public function getJerseyHomeUrlAttribute()
    {
        if ($this->jersey_home) {
            return Storage::url($this->jersey_home);
        }
        return null;
    }

    /**
     * 🔥 Accessor URL Jersey Away
     */
    public function getJerseyAwayUrlAttribute()
    {
        if ($this->jersey_away) {
            return Storage::url($this->jersey_away);
        }
        return null;
    }

    /**
     * 🔥 Accessor URL Jersey Alternate
     */
    public function getJerseyAlternateUrlAttribute()
    {
        if ($this->jersey_alternate) {
            return Storage::url($this->jersey_alternate);
        }
        return null;
    }

    /**
     * 🔥 Cek apakah tim sudah upload jersey
     */
    public function hasJersey()
    {
        return !empty($this->jersey_home) || !empty($this->jersey_away) || !empty($this->jersey_alternate);
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

    public function getDancersCountAttribute()
    {
        return $this->dancers()->count();
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

    public function scopePaid($query)
    {
        return $query->where('is_leader_paid', true);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_leader_paid', false);
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

    public function hasPaymentProof()
    {
        return !empty($this->payment_proof);
    }

    public function hasReferralCode()
    {
        return !empty($this->referral_code);
    }
}