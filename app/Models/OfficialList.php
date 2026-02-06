<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficialList extends Model
{
    use HasFactory;

    protected $table = 'official_list';
    protected $primaryKey = 'official_id';
    public $timestamps = true;

    // ✅ Eager load relationships
    protected $with = ['school', 'team'];

    protected $fillable = [
        'team_id',
        'school_id',
        'nik',
        'name',
        'birthdate',
        'gender',
        'email',
        'phone',
        'school_name',
        'height',
        'weight',
        'team_role',
        'tshirt_size',
        'shoes_size',
        'instagram',
        'tiktok',
        'formal_photo',
        'license_photo',
        'identity_card',
        'role',
        'verification_status',
        'is_finalized',
        'finalized_at',
        'unlocked_by_admin',
        'unlocked_at'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_finalized' => 'boolean',
        'unlocked_by_admin' => 'boolean',
        'finalized_at' => 'datetime',
        'unlocked_at' => 'datetime'
    ];

    /* ================= BOOT METHOD ================= */
    protected static function boot()
    {
        parent::boot();

        // Auto-set school_id dari team jika kosong
        static::creating(function ($official) {
            if (empty($official->school_id) && $official->team_id) {
                $team = TeamList::find($official->team_id);
                if ($team) {
                    // Cari school dari team
                    if ($team->school_id) {
                        $school = School::find($team->school_id);
                        if ($school) {
                            $official->school_id = $school->id;
                        }
                    }
                    
                    // Jika masih kosong, cari berdasarkan nama sekolah
                    if (empty($official->school_id)) {
                        $school = School::where('school_name', $team->school_name)->first();
                        if ($school) {
                            $official->school_id = $school->id;
                        }
                    }
                    
                    // Jika masih kosong, buat sekolah baru
                    if (empty($official->school_id)) {
                        $school = School::create([
                            'school_name' => $team->school_name,
                            'category_name' => 'SMA',
                            'type' => 'SWASTA',
                            'city_id' => 1, // Default city
                        ]);
                        $official->school_id = $school->id;
                        $team->update(['school_id' => $school->id]);
                    }
                }
            }
            
            // Set school_name jika ada school_id
            if ($official->school_id && empty($official->school_name)) {
                $school = School::find($official->school_id);
                if ($school) {
                    $official->school_name = $school->school_name;
                }
            }
        });
    }

    /* ================= RELATIONSHIPS ================= */
    
    public function team()
    {
        return $this->belongsTo(TeamList::class, 'team_id', 'team_id');
    }
    
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    /* ================= ACCESSORS ================= */
    
    public function getFormalPhotoUrlAttribute()
    {
        if ($this->formal_photo && \Storage::exists($this->formal_photo)) {
            return \Storage::url($this->formal_photo);
        }
        return asset('images/default-profile.png');
    }
    
    public function getLicensePhotoUrlAttribute()
    {
        if ($this->license_photo && \Storage::exists($this->license_photo)) {
            return \Storage::url($this->license_photo);
        }
        return asset('images/default-document.png');
    }
    
    public function getIdentityCardUrlAttribute()
    {
        if ($this->identity_card && \Storage::exists($this->identity_card)) {
            return \Storage::url($this->identity_card);
        }
        return asset('images/default-document.png');
    }

    public function getAgeAttribute()
    {
        return \Carbon\Carbon::parse($this->birthdate)->age;
    }

    public function getVerificationStatusLabelAttribute()
    {
        return [
            'unverified' => 'Belum Diverifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak'
        ][$this->verification_status] ?? $this->verification_status;
    }

    public function getRoleLabelAttribute()
    {
        return [
            'Leader' => 'Kapten',
            'Member' => 'Anggota'
        ][$this->role] ?? $this->role;
    }
    
    public function getTeamRoleLabelAttribute()
    {
        return [
            'Coach' => 'Pelatih',
            'Manager' => 'Manajer',
            'Medical Support' => 'Dukungan Medis',
            'Assistant Coach' => 'Asisten Pelatih',
            'Pendamping' => 'Pendamping'
        ][$this->team_role] ?? $this->team_role;
    }
    
    public function getGenderLabelAttribute()
    {
        return [
            'male' => 'Laki-laki',
            'female' => 'Perempuan'
        ][$this->gender] ?? $this->gender;
    }

    /* ================= SCOPES ================= */
    
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeLeaders($query)
    {
        return $query->where('role', 'Leader');
    }

    public function scopeMembers($query)
    {
        return $query->where('role', 'Member');
    }

    public function scopeByTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }
    
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
    
    public function scopeByTeamRole($query, $role)
    {
        return $query->where('team_role', $role);
    }

    /* ================= METHODS ================= */
    
    public function isLeader()
    {
        return $this->role === 'Leader';
    }
    
    public function isFinalized()
    {
        return (bool) $this->is_finalized;
    }
    
    public function isUnlockedByAdmin()
    {
        return (bool) $this->unlocked_by_admin;
    }
    
    public function hasValidSchool()
    {
        return !empty($this->school_id) && $this->school;
    }
}