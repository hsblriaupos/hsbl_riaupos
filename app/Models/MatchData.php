<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchData extends Model
{
    protected $table = 'match_data';

    protected $fillable = [
        'upload_date',
        'main_title',
        'caption',
        'layout_image',
        'status',
        'series_name',
    ];

    // Casting untuk tipe data
    protected $casts = [
        'upload_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan MatchResult (jika ada relasi)
    public function matchResults()
    {
        return $this->hasMany(MatchResult::class, 'match_data_id');
    }

    // Scope untuk status
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessor untuk formatted upload date
    public function getFormattedUploadDateAttribute()
    {
        return $this->upload_date ? $this->upload_date->format('d F Y') : '-';
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'published' => '<span class="badge bg-success">Published</span>',
            'active' => '<span class="badge bg-primary">Active</span>',
            'archived' => '<span class="badge bg-warning">Archived</span>',
        ];
        
        return $badges[$this->status] ?? '<span class="badge bg-dark">Unknown</span>';
    }

    // Method untuk cek jika ada layout image
    public function getHasLayoutImageAttribute()
    {
        return !empty($this->layout_image) && file_exists(public_path('uploads/layouts/' . $this->layout_image));
    }

    // Method untuk mendapatkan layout image URL
    public function getLayoutImageUrlAttribute()
    {
        if ($this->has_layout_image) {
            return asset('uploads/layouts/' . $this->layout_image);
        }
        return asset('images/default-layout.jpg');
    }
}