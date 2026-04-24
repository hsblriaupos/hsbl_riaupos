<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'category',
        'tshirt_size',
        'shoes_size',  // ← Tetap string, sesuai migration
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
        'unlocked_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_finalized' => 'boolean',
        'unlocked_by_admin' => 'boolean',
        'finalized_at' => 'datetime',
        'unlocked_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
        // ⚠️ HAPUS 'shoes_size' dari casts karena di DB tipenya VARCHAR
    ];

    /* ================= BOOT METHOD ================= */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($official) {
            // Auto-set school_id dari team jika kosong
            if (empty($official->school_id) && $official->team_id) {
                $team = TeamList::find($official->team_id);
                if ($team) {
                    if ($team->school_id) {
                        $school = School::find($team->school_id);
                        if ($school) {
                            $official->school_id = $school->id;
                        }
                    }
                    
                    if (empty($official->school_id)) {
                        $school = School::where('school_name', $team->school_name)->first();
                        if ($school) {
                            $official->school_id = $school->id;
                        }
                    }
                    
                    if (empty($official->school_id)) {
                        $school = School::create([
                            'school_name' => $team->school_name,
                            'category_name' => 'SMA',
                            'type' => 'SWASTA',
                            'city_id' => 1,
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
            
            // Auto-set category dari tim jika kosong
            if (empty($official->category) && $official->team_id) {
                $team = TeamList::find($official->team_id);
                if ($team) {
                    $official->category = self::determineCategoryFromTeam($team);
                } else {
                    $official->category = 'lainnya';
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
        return $this->getFileUrl($this->formal_photo, 'images/default-profile.png');
    }
    
    public function getLicensePhotoUrlAttribute()
    {
        return $this->getFileUrl($this->license_photo, 'images/default-document.png');
    }
    
    public function getIdentityCardUrlAttribute()
    {
        return $this->getFileUrl($this->identity_card, 'images/default-document.png');
    }

    public function getAgeAttribute()
    {
        return $this->birthdate ? now()->diffInYears($this->birthdate) : null;
    }

    public function getVerificationStatusLabelAttribute()
    {
        $labels = [
            'unverified' => 'Belum Diverifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak'
        ];
        
        return $labels[$this->verification_status] ?? $this->verification_status;
    }

    public function getRoleLabelAttribute()
    {
        $labels = [
            'Leader' => 'Ketua',
            'Member' => 'Anggota'
        ];
        
        return $labels[$this->role] ?? $this->role;
    }
    
    public function getTeamRoleLabelAttribute()
    {
        $labels = [
            'Coach' => 'Pelatih',
            'Manager' => 'Manajer',
            'Medical Support' => 'Dukungan Medis',
            'Assistant Coach' => 'Asisten Pelatih',
            'Pendamping' => 'Pendamping'
        ];
        
        return $labels[$this->team_role] ?? $this->team_role;
    }
    
    public function getGenderLabelAttribute()
    {
        $labels = [
            'male' => 'Laki-laki',
            'female' => 'Perempuan'
        ];
        
        return $labels[$this->gender] ?? $this->gender;
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'basket_putra' => 'Basket Putra',
            'basket_putri' => 'Basket Putri',
            'dancer' => 'Dancer',
            'lainnya' => 'Lainnya'
        ];
        
        return $labels[$this->category] ?? 'Lainnya';
    }

    public function getCategoryBadgeColorAttribute()
    {
        $colors = [
            'basket_putra' => 'primary',
            'basket_putri' => 'danger',
            'dancer' => 'warning',
            'lainnya' => 'secondary'
        ];
        
        return $colors[$this->category] ?? 'secondary';
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

    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
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

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeBasketPutra($query)
    {
        return $query->where('category', 'basket_putra');
    }

    public function scopeBasketPutri($query)
    {
        return $query->where('category', 'basket_putri');
    }

    public function scopeDancer($query)
    {
        return $query->where('category', 'dancer');
    }

    public function scopeFinalized($query)
    {
        return $query->where('is_finalized', true);
    }

    public function scopeNotFinalized($query)
    {
        return $query->where('is_finalized', false);
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

    public function isBasketPutra()
    {
        return $this->category === 'basket_putra';
    }

    public function isBasketPutri()
    {
        return $this->category === 'basket_putri';
    }

    public function isDancer()
    {
        return $this->category === 'dancer';
    }

    public function getRelatedPlayers()
    {
        if (!$this->team_id) {
            return collect();
        }

        if ($this->isBasketPutra()) {
            return PlayerList::where('team_id', $this->team_id)
                ->where('gender', 'male')
                ->get();
        } elseif ($this->isBasketPutri()) {
            return PlayerList::where('team_id', $this->team_id)
                ->where('gender', 'female')
                ->get();
        } elseif ($this->isDancer()) {
            return PlayerList::where('team_id', $this->team_id)
                ->where('position', 'like', '%dancer%')
                ->orWhere('category', 'dancer')
                ->get();
        }

        return PlayerList::where('team_id', $this->team_id)->get();
    }

    public function getCategoryOfficials()
    {
        if (!$this->team_id || !$this->category) {
            return collect();
        }

        return self::where('team_id', $this->team_id)
            ->where('category', $this->category)
            ->where('official_id', '!=', $this->official_id)
            ->get();
    }

    public function finalize()
    {
        $this->update([
            'is_finalized' => true,
            'finalized_at' => now()
        ]);
        
        return $this;
    }

    public function unlock()
    {
        $this->update([
            'unlocked_by_admin' => true,
            'unlocked_at' => now(),
            'is_finalized' => false,
            'finalized_at' => null
        ]);
        
        return $this;
    }

    public function verify()
    {
        $this->update(['verification_status' => 'verified']);
        return $this;
    }

    public function reject($reason = null)
    {
        $this->update(['verification_status' => 'rejected']);
        return $this;
    }

    /* ================= HELPER METHODS ================= */
    
    private function getFileUrl($path, $default)
    {
        if (!$path) {
            return asset($default);
        }
        
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }
        
        // Cek dengan prefix storage
        $storagePath = 'storage/' . $path;
        if (file_exists(public_path($storagePath))) {
            return asset($storagePath);
        }
        
        return asset($default);
    }

    private static function determineCategoryFromTeam($team)
    {
        $teamName = strtolower($team->team_name ?? '');
        $teamType = strtolower($team->team_type ?? '');
        $teamCategory = strtolower($team->category ?? '');
        
        if (str_contains($teamName, 'putra') || 
            str_contains($teamType, 'putra') || 
            str_contains($teamCategory, 'putra') ||
            str_contains($teamName, 'boys') ||
            str_contains($teamName, 'laki')) {
            return 'basket_putra';
        }
        
        if (str_contains($teamName, 'putri') || 
            str_contains($teamType, 'putri') || 
            str_contains($teamCategory, 'putri') ||
            str_contains($teamName, 'girls') ||
            str_contains($teamName, 'perempuan')) {
            return 'basket_putri';
        }
        
        if (str_contains($teamName, 'dancer') || 
            str_contains($teamName, 'cheer') || 
            str_contains($teamName, 'pemandu sorak') ||
            str_contains($teamType, 'dancer')) {
            return 'dancer';
        }
        
        if (isset($team->team_gender)) {
            return $team->team_gender === 'male' ? 'basket_putra' : 'basket_putri';
        }
        
        return 'lainnya';
    }

    public static function getCategories()
    {
        return [
            'basket_putra' => 'Basket Putra',
            'basket_putri' => 'Basket Putri',
            'dancer' => 'Dancer',
            'lainnya' => 'Lainnya'
        ];
    }
}