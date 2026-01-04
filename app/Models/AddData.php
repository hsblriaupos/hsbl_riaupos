<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddData extends Model
{
    use HasFactory;

    protected $table = 'add_data'; // Tentukan nama tabel

    protected $fillable = [
        'season_name',
        'series_name',
        'competition',
        'competition_type',
        'phase'
    ];
}