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
        'nik',
        'name',
        'birthdate',
        'gender',
        'email',
        'phone',
        'school',
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
        'is_finalized',
        'finalized_at',
        'unlocked_by_admin',
        'unlocked_at',
    ];

    // Tambahkan ini untuk cast tanggal
    protected $dates = [
        'birthdate',
        'finalized_at',
        'unlocked_at',
        'created_at',
        'updated_at'
    ];

    // Relasi ke tim
    public function team()
    {
        return $this->belongsTo(TeamList::class, 'team_id', 'team_id');
    }

    // Relasi ke sekolah - HAPUS jika tidak ada tabel schools
    // public function schoolData()
    // {
    //     return $this->belongsTo(School::class, 'school', 'id');
    // }
}