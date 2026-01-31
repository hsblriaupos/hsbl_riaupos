<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerList extends Model
{
    use HasFactory;

    protected $table = 'player_list';
    protected $primaryKey = 'id';

    protected $fillable = [
        'team_id',
        'category',  // 🔥 FIX: 'category' bukan 'team_category'
        'role',      // 🔥 FIX: 'role' bukan 'team_role'
        'nik',
        'name',
        'birthdate',
        'gender',
        'email',
        'phone',
        'school_id', // 🔥 FIX: 'school_id' bukan 'school'
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
        'payment_proof', // 🔥 TAMBAH field payment_proof
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

    /**
     * Relasi ke TeamList
     */
    public function team()
    {
        return $this->belongsTo(TeamList::class, 'team_id', 'team_id');
    }

    /**
     * Relasi ke School (karena school_id adalah foreign key)
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
        return $query->where('role', 'Leader'); // 🔥 FIX: 'role' bukan 'team_role'
    }

    /**
     * Scope untuk mencari Player biasa
     */
    public function scopePlayer($query)
    {
        return $query->where('role', 'Player'); // 🔥 FIX: 'role' bukan 'team_role'
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
     * Accessor untuk nama sekolah
     */
    public function getSchoolNameAttribute()
    {
        return $this->school ? $this->school->school_name : 'Tidak diketahui';
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
        return $this->role === 'Leader'; // 🔥 FIX: 'role' bukan 'team_role'
    }

    /**
     * Cek apakah player adalah Player biasa
     */
    public function isRegularPlayer()
    {
        return $this->role === 'Player'; // 🔥 FIX: 'role' bukan 'team_role'
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
}