<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerList extends Model
{
    use HasFactory;

    protected $table = 'player_list';
    protected $primaryKey = 'id';

    // ✅ Eager load school relationship
    protected $with = ['school'];

    protected $fillable = [
        'team_id',
        'category',
        'role',
        'nik',
        'name',
        'birthdate',
        'gender',
        'email',
        'phone',
        'school_id', 
        'school_name',
        'grade',
        'sttb_year',
        'height',
        'weight',
        'tshirt_size',
        'shoes_size',
        'basketball_position',
        'jersey_number',
        'instagram',
        'tiktok',
        'father_name',
        'father_phone',
        'mother_name',
        'mother_phone',
        'birth_certificate',
        'kk',
        'report_identity',
        'shun',
        'last_report_card',
        'formal_photo',
        'assignment_letter',
        'payment_proof',
        'is_finalized',
        'finalized_at',
        'unlocked_by_admin',
        'unlocked_at',
    ];

    protected $dates = [
        'birthdate',
        'finalized_at',
        'unlocked_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_finalized' => 'boolean',
        'unlocked_by_admin' => 'boolean',
        'sttb_year' => 'integer',
        'height' => 'integer',
        'weight' => 'integer',
        'jersey_number' => 'integer',
    ];

    // ✅ Boot method untuk validasi school_id
    protected static function boot()
    {
        parent::boot();

        // Validasi sebelum create
        static::creating(function ($player) {
            // Jika school_id kosong, cari dari team
            if (empty($player->school_id) && $player->team_id) {
                $team = TeamList::find($player->team_id);
                if ($team) {
                    // Cari school berdasarkan school_id di team
                    if ($team->school_id) {
                        $school = School::find($team->school_id);
                        if ($school) {
                            $player->school_id = $school->id;
                        }
                    }
                    
                    // Jika masih kosong, cari berdasarkan nama sekolah
                    if (empty($player->school_id)) {
                        $school = School::where('school_name', $team->school_name)->first();
                        if ($school) {
                            $player->school_id = $school->id;
                            // Update team juga
                            $team->update(['school_id' => $school->id]);
                        }
                    }
                    
                    // Jika masih kosong, buat sekolah baru
                    if (empty($player->school_id)) {
                        $school = School::create([
                            'school_name' => $team->school_name,
                            'category_name' => 'SMA',
                            'type' => 'SWASTA',
                            'city_id' => 1,
                        ]);
                        $player->school_id = $school->id;
                        $team->update(['school_id' => $school->id]);
                    }
                }
            }
        });

        // Setelah create, verifikasi school_id
        static::created(function ($player) {
            if (empty($player->school_id)) {
                \Log::error('Player created without school_id!', [
                    'player_id' => $player->id,
                    'team_id' => $player->team_id
                ]);
            }
        });
    }

    /**
     * Relasi ke TeamList
     */
    public function team()
    {
        return $this->belongsTo(TeamList::class, 'team_id', 'team_id');
    }

    /**
     * Relasi ke School
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'id');
    }

    /**
     * Scope untuk mencari Leader
     */
    public function scopeLeader($query)
    {
        return $query->where('role', 'Leader');
    }

    /**
     * Scope untuk mencari Player biasa
     */
    public function scopePlayer($query)
    {
        return $query->where('role', 'Player');
    }

    /**
     * Scope untuk kategori tertentu
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope untuk tim tertentu
     */
    public function scopeTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    /**
     * Scope untuk sekolah tertentu
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Accessor untuk nama sekolah (dari relasi)
     */
    public function getSchoolNameAttribute()
    {
        return $this->school ? $this->school->school_name : ($this->team ? $this->team->school_name : 'Tidak diketahui');
    }

    /**
     * Accessor untuk role dengan badge
     */
    public function getRoleBadgeAttribute()
    {
        return $this->role === 'Leader' 
            ? '<span class="badge bg-warning">Leader</span>' 
            : '<span class="badge bg-info">Player</span>';
    }

    /**
     * Accessor untuk kategori dengan badge
     */
    public function getCategoryBadgeAttribute()
    {
        $badges = [
            'putra' => 'bg-primary',
            'putri' => 'bg-danger',
            'dancer' => 'bg-success'
        ];
        
        $color = $badges[$this->category] ?? 'bg-secondary';
        $label = ucfirst($this->category);
        
        return "<span class='badge {$color}'>{$label}</span>";
    }

    /**
     * Cek apakah player adalah Leader
     */
    public function isLeader()
    {
        return $this->role === 'Leader';
    }

    /**
     * Cek apakah player adalah Player biasa
     */
    public function isRegularPlayer()
    {
        return $this->role === 'Player';
    }

    /**
     * Cek apakah player sudah final
     */
    public function isFinalized()
    {
        return (bool) $this->is_finalized;
    }

    /**
     * Cek apakah player sudah di-unlock oleh admin
     */
    public function isUnlockedByAdmin()
    {
        return (bool) $this->unlocked_by_admin;
    }

    /**
     * Cek apakah player memiliki bukti pembayaran
     */
    public function hasPaymentProof()
    {
        return !empty($this->payment_proof);
    }

    /**
     * Cek apakah player memiliki school_id yang valid
     */
    public function hasValidSchool()
    {
        return !empty($this->school_id) && $this->school;
    }
}