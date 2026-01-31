<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name', 
        'city_id', 
        'category_name', 
        'type',
        'school_logo'  // Tambahkan ini
    ];

    protected $appends = [
        'logo_url'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function teams()
    {
        return $this->hasMany(TeamList::class, 'school_id');
    }

    /**
     * Accessor untuk mendapatkan URL logo sekolah
     */
    public function getLogoUrlAttribute()
    {
        if ($this->school_logo) {
            return Storage::url($this->school_logo);
        }
        
        return asset('images/default-school-logo.png');
    }

    /**
     * Cek apakah sekolah memiliki logo
     */
    public function hasLogo()
    {
        return !empty($this->school_logo);
    }
}