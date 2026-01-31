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

    protected $fillable = [
        'school_id',
        'school_name',
        'school_logo',  
        'referral_code',
        'season',
        'series',
        'competition',
        'team_category',
        'registered_by',
        'locked_status',
        'verification_status',
        'recommendation_letter',
        'payment_proof',
        'payment_status',
        'koran',
        'is_leader_paid', 
        'payment_date',   
    ];
    
    protected $appends = [
        'school_logo_url'
    ];

    protected $casts = [
        'locked_status' => 'string',
        'verification_status' => 'string',
        'is_leader_paid' => 'boolean',
        'payment_date' => 'datetime',
    ];
    
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    
    public function officials()
    {
        return $this->hasMany(OfficialList::class, 'team_id');
    }
    
    public static function generateReferralCode($schoolName)
    {
        return substr(md5($schoolName . time()), 0, 8);
    }
    
    public function players()
    {
        return $this->hasMany(PlayerList::class, 'team_id');
    }
    
    public function hasReachedPlayerLimit()
    {
        return $this->players()->count() >= 12;
    }
    
    public function leader()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Accessor untuk mendapatkan URL logo sekolah tim
     */
    public function getSchoolLogoUrlAttribute()
    {
        // Prioritas 1: Logo yang diupload spesifik untuk tim ini
        if ($this->school_logo) {
            return Storage::url($this->school_logo);
        }
        
        // Prioritas 2: Logo dari tabel schools
        if ($this->school && $this->school->school_logo) {
            return Storage::url($this->school->school_logo);
        }
        
        // Fallback: Logo default
        return asset('images/default-school-logo.png');
    }

    /**
     * Cek apakah tim memiliki logo spesifik
     */
    public function hasCustomLogo()
    {
        return !empty($this->school_logo);
    }
}