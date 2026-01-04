<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamList extends Model
{
    use HasFactory;

    protected $table = 'team_list';

    protected $primaryKey = 'team_id';
    
    public $incrementing = true; // Pastikan ini true jika team_id adalah auto-increment
    
    protected $keyType = 'int'; // Tipe data primary key

    protected $fillable = [
        'school_id',
        'school_name',
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
    ];
    
    // Relasi ke model School
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    // Relasi ke official
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
        return $this->players()->count() >= 12; // Contoh batas 12 pemain
    }
    public function leader()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}
