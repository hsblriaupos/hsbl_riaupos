<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MediaGallery extends Model
{
    use HasFactory;

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
        'photo',
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
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_file_size',
        'photo_url',
        'has_photo',
        'season_series',
        'photo_extension',
        'status_badge',
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
     * Get photo URL - REVISED & FIXED
     * Foto disimpan di storage/app/public/photos/cover/filename.jpg
     * Database menyimpan: 'photos/cover/filename.jpg'
     * URL yang benar: http://localhost:8000/storage/photos/cover/filename.jpg
     *
     * @return string|null
     */
    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return null;
        }
        
        // Jika photo sudah berisi URL lengkap
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }
        
        // Bersihkan path dari berbagai kemungkinan prefix yang tidak diinginkan
        $cleanPath = $this->cleanPhotoPath($this->photo);
        
        // Return URL dengan prefix 'storage/'
        return asset('storage/' . $cleanPath);
    }

    /**
     * Clean photo path - Helper method
     * Menghapus berbagai prefix yang tidak diinginkan dari path
     *
     * @param string $path
     * @return string
     */
    protected function cleanPhotoPath($path)
    {
        if (empty($path)) {
            return '';
        }
        
        // Hapus 'public/' dari path jika ada
        $cleanPath = str_replace('public/', '', $path);
        
        // Hapus 'storage/' dari path jika ada
        $cleanPath = str_replace('storage/', '', $cleanPath);
        
        // Hapus 'photos/cover/' ganda jika ada (contoh: photos/cover/photos/cover/file.jpg)
        $cleanPath = preg_replace('#^(photos/cover/)+#', 'photos/cover/', $cleanPath);
        
        // Hapus 'photos/cover' ganda di tengah path
        $cleanPath = preg_replace('#(photos/cover){2,}#', 'photos/cover', $cleanPath);
        
        // Pastikan path dimulai dengan 'photos/cover/'
        if (strpos($cleanPath, 'photos/cover/') !== 0) {
            // Jika path hanya berisi nama file (tidak ada slash)
            if (strpos($cleanPath, '/') === false) {
                $cleanPath = 'photos/cover/' . $cleanPath;
            }
            // Jika path sudah memiliki folder lain, biarkan apa adanya
            // (misalnya 'cover/filename.jpg' atau 'uploads/photo.jpg')
        }
        
        // Bersihkan multiple slash
        $cleanPath = preg_replace('#/+#', '/', $cleanPath);
        
        return $cleanPath;
    }

    /**
     * Get photo storage path untuk pengecekan file
     *
     * @return string
     */
    protected function getPhotoStoragePath()
    {
        if (!$this->photo) {
            return '';
        }
        
        // Gunakan method yang sama untuk membersihkan path
        return $this->cleanPhotoPath($this->photo);
    }

    /**
     * Cek apakah memiliki foto - Menggunakan pengecekan file yang sebenarnya
     *
     * @return bool
     */
    public function getHasPhotoAttribute()
    {
        return $this->hasPhoto();
    }

    /**
     * Cek apakah memiliki foto (method version) - IMPROVED
     * Mengecek apakah file benar-benar ada di storage
     *
     * @return bool
     */
    public function hasPhoto()
    {
        if (is_null($this->photo) || $this->photo === '') {
            return false;
        }
        
        // Dapatkan path yang sudah dibersihkan
        $path = $this->getPhotoStoragePath();
        
        if (empty($path)) {
            return false;
        }
        
        // Cek file di storage public disk
        return Storage::disk('public')->exists($path);
    }

    /**
     * Get photo extension.
     *
     * @return string|null
     */
    public function getPhotoExtensionAttribute()
    {
        if (!$this->photo) {
            return null;
        }
        
        $path = $this->cleanPhotoPath($this->photo);
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    /**
     * Cek apakah photo adalah gambar.
     *
     * @return bool
     */
    public function isPhotoImage()
    {
        if (!$this->photo) {
            return false;
        }
        
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
        return in_array($this->photo_extension, $imageExtensions);
    }

    /**
     * Get photo filename only (without path)
     *
     * @return string|null
     */
    public function getPhotoFilenameAttribute()
    {
        if (!$this->photo) {
            return null;
        }
        
        $path = $this->cleanPhotoPath($this->photo);
        return basename($path);
    }

    /**
     * Get photo directory path
     *
     * @return string|null
     */
    public function getPhotoDirectoryAttribute()
    {
        if (!$this->photo) {
            return null;
        }
        
        $path = $this->cleanPhotoPath($this->photo);
        return dirname($path);
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
     * Scope untuk file yang memiliki photo.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasPhoto($query)
    {
        return $query->whereNotNull('photo')->where('photo', '!=', '');
    }

    /**
     * Scope untuk file yang tidak memiliki photo.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNoPhoto($query)
    {
        return $query->whereNull('photo')->orWhere('photo', '');
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
        return $this->getStatusBadge();
    }

    /**
     * Dapatkan status badge (method version).
     *
     * @return array
     */
    public function getStatusBadge()
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

    /**
     * Get file URL - Untuk ZIP file
     *
     * @return string|null
     */
    public function getFileUrlAttribute()
    {
        if (!$this->file) {
            return null;
        }
        
        // Jika file sudah berisi URL lengkap
        if (filter_var($this->file, FILTER_VALIDATE_URL)) {
            return $this->file;
        }
        
        // Hapus 'public/' dari path jika ada
        $cleanPath = str_replace('public/', '', $this->file);
        
        // Hapus 'storage/' dari path jika ada
        $cleanPath = str_replace('storage/', '', $cleanPath);
        
        return asset('storage/' . $cleanPath);
    }

    /**
     * Cek apakah file ZIP ada di storage
     *
     * @return bool
     */
    public function hasFile()
    {
        if (is_null($this->file) || $this->file === '') {
            return false;
        }
        
        // Hapus 'public/' dari path jika ada
        $cleanPath = str_replace('public/', '', $this->file);
        
        // Hapus 'storage/' dari path jika ada
        $cleanPath = str_replace('storage/', '', $cleanPath);
        
        return Storage::disk('public')->exists($cleanPath);
    }

    /**
     * Debug method - Untuk melihat path yang sebenarnya
     * Hanya digunakan untuk debugging
     *
     * @return array
     */
    public function getPhotoDebugInfo()
    {
        return [
            'original_photo' => $this->photo,
            'cleaned_path' => $this->cleanPhotoPath($this->photo),
            'storage_path' => $this->getPhotoStoragePath(),
            'full_storage_path' => $this->photo ? Storage::disk('public')->path($this->getPhotoStoragePath()) : null,
            'exists_in_storage' => $this->hasPhoto(),
            'photo_url' => $this->photo_url,
            'photo_filename' => $this->photo_filename,
            'photo_extension' => $this->photo_extension,
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Event ketika model akan dihapus
        static::deleting(function ($gallery) {
            // Hapus file photo jika ada
            if ($gallery->photo) {
                $photoPath = $gallery->getPhotoStoragePath();
                
                if (Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
            }
            
            // Hapus file ZIP jika ada
            if ($gallery->file) {
                $filePath = str_replace('public/', '', $gallery->file);
                $filePath = str_replace('storage/', '', $filePath);
                
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        });

        // Event ketika model disimpan
        static::saved(function ($gallery) {
            // Log untuk debugging jika diperlukan
            if (config('app.debug') && $gallery->photo) {
                \Log::info('Gallery saved with photo:', [
                    'id' => $gallery->id,
                    'photo' => $gallery->photo,
                    'photo_url' => $gallery->photo_url,
                    'has_photo' => $gallery->hasPhoto()
                ]);
            }
        });
    }
}