<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaGallery extends Model
{
    use HasFactory; // HAPUS SoftDeletes

    /**
     * Nama tabel yang digunakan.
     *
     * @var string
     */
    protected $table = 'media_gallery';

    /**
     * Kolom yang dapat diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_name',
        'file',
        'original_filename',
        'file_size',
        'file_type',
        'competition',
        'season',
        'series',
        'description',
        'status',
        'download_count',
    ];

    /**
     * Casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // HAPUS 'deleted_at' => 'datetime',
    ];

    /**
     * Format file size menjadi readable.
     *
     * @return string
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size ?? 0;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    /**
     * Format season-series.
     *
     * @return string
     */
    public function getSeasonSeriesAttribute()
    {
        return $this->season . ($this->series ? ' - ' . $this->series : '');
    }

    /**
     * Cek apakah file adalah draft.
     *
     * @return bool
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Cek apakah file sudah published.
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === 'published';
    }

    /**
     * Cek apakah file diarsipkan.
     *
     * @return bool
     */
    public function isArchived()
    {
        return $this->status === 'archived';
    }

    /**
     * Scope untuk file yang published.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope untuk pencarian sekolah.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $schoolName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySchool($query, $schoolName)
    {
        return $query->where('school_name', 'like', '%' . $schoolName . '%');
    }

    /**
     * Scope untuk kompetisi tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $competition
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCompetition($query, $competition)
    {
        return $query->where('competition', $competition);
    }

    /**
     * Scope untuk season tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $season
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySeason($query, $season)
    {
        return $query->where('season', $season);
    }

    /**
     * Scope untuk series tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $series
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySeries($query, $series)
    {
        return $query->where('series', $series);
    }

    /**
     * Scope untuk status tertentu.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan search.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('school_name', 'like', '%' . $search . '%')
              ->orWhere('original_filename', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    /**
     * Increment download count.
     *
     * @return void
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Format file size secara statis.
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function formatBytesStatic($bytes, $precision = 2)
    {
        $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        
        if ($bytes <= 0) return '0 Bytes';
        
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Dapatkan status badge.
     *
     * @return array
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => [
                'class' => 'bg-warning bg-opacity-20 text-warning border border-warning border-opacity-50',
                'icon' => 'fas fa-edit',
                'text' => 'Draft'
            ],
            'published' => [
                'class' => 'bg-success bg-opacity-20 text-success border border-success border-opacity-50',
                'icon' => 'fas fa-check-circle',
                'text' => 'Published'
            ],
            'archived' => [
                'class' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25',
                'icon' => 'fas fa-archive',
                'text' => 'Archived'
            ]
        ];

        return $badges[$this->status] ?? [
            'class' => 'bg-light text-dark',
            'icon' => 'fas fa-question-circle',
            'text' => ucfirst($this->status)
        ];
    }
}