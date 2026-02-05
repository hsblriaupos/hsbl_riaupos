<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DancerList extends Model
{
    use HasFactory;

    protected $table = 'dancer_list';
    protected $primaryKey = 'dancer_id';
    public $timestamps = true;

    protected $fillable = [
        'team_id',
        'school_id', // 🔥 TAMBAH INI
        'nik',
        'name',
        'birthdate',
        'gender',
        'email',
        'phone',
        'school_name', // Tetap ada untuk backup
        'grade',
        'sttb_year',
        'height',
        'weight',
        'tshirt_size',
        'shoes_size',
        'instagram',
        'tiktok',
        'father_name',
        'father_phone',
        'mother_name',
        'mother_phone',
        'birth_certificate',
        'kk',
        'shun',
        'report_identity',
        'last_report_card',
        'formal_photo',
        'assignment_letter',
        'role',
        'verification_status'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'sttb_year' => 'integer'
    ];

    /* ================= RELATIONSHIPS ================= */
    
    public function team()
    {
        return $this->belongsTo(TeamList::class, 'team_id', 'team_id');
    }
    
    // 🔥 TAMBAH RELASI KE SCHOOL
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
    
    // 🔥 TAMBAH ACCESSOR UNTUK DAPATKAN NAMA SEKOLAH DARI RELASI
    public function getSchoolNameFromRelationAttribute()
    {
        return $this->school ? $this->school->school_name : $this->school_name;
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
    
    // 🔥 TAMBAH SCOPE UNTUK SEKOLAH
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}