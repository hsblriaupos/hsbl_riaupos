<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TermCondition extends Model
{
    protected $table = 'term_conditions';

    protected $fillable = [
        'title',
        'year',
        'document',
        'status',
    ];

    protected $casts = [
        'year' => 'integer',
        'status' => 'string', // ✅ TAMBAHKAN CASTING UNTUK STATUS
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'download_url',
        'file_path',
        'status_badge',
        'status_text',
        'file_size_formatted',
        'original_filename',
        'file_exists',
        'is_pdf',
    ];

    /**
     * Scope untuk urutkan data
     */
    public function scopeOrderByLatest($query)
    {
        return $query->orderBy('year', 'desc')
                     ->orderBy('title', 'asc');
    }

    /**
     * Scope untuk data aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk tahun tertentu
     */
    public function scopeYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope untuk pencarian title
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', '%' . $search . '%');
    }

    /**
     * Get download URL
     */
    public function getDownloadUrlAttribute()
    {
        if (!$this->document) {
            return null;
        }

        // Jika document sudah mengandung 'storage/', gunakan langsung
        if (strpos($this->document, 'storage/') === 0) {
            return Storage::url($this->document);
        }

        // Jika hanya nama file, tambahkan path default
        return Storage::url('term_conditions/' . $this->document);
    }

    /**
     * Get file path untuk storage
     */
    public function getFilePathAttribute()
    {
        if (!$this->document) {
            return null;
        }

        // Konversi URL path ke storage path
        if (strpos($this->document, 'storage/') === 0) {
            return str_replace('storage/', 'public/', $this->document);
        }

        return 'public/term_conditions/' . $this->document;
    }

    /**
     * Cek apakah file ada di storage
     */
    public function getFileExistsAttribute()
    {
        if (!$this->file_path) {
            return false;
        }

        try {
            return Storage::exists($this->file_path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        // ✅ PERBAIKAN: Gunakan status yang sudah di-cast
        $status = strtolower($this->status);
        
        return match($status) {
            'active' => 'badge bg-success',
            'inactive' => 'badge bg-secondary',
            'draft' => 'badge bg-warning',
            default => 'badge bg-info',
        };
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        // ✅ PERBAIKAN: Gunakan status yang sudah di-cast
        $status = strtolower($this->status);
        
        return match($status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'draft' => 'Draft',
            default => 'Unknown',
        };
    }

    /**
     * Get original filename
     */
    public function getOriginalFilenameAttribute()
    {
        if (!$this->document) {
            return null;
        }

        // Jika path URL, ambil nama file saja
        if (strpos($this->document, '/') !== false) {
            return basename($this->document);
        }

        return $this->document;
    }

    /**
     * Get file size
     */
    public function getFileSizeAttribute()
    {
        if (!$this->file_path || !$this->file_exists) {
            return null;
        }

        try {
            return Storage::size($this->file_path);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get formatted file size
     */
    public function getFileSizeFormattedAttribute()
    {
        $size = $this->file_size;
        
        if (!$size) {
            return '0 KB';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Get file extension
     */
    public function getFileExtensionAttribute()
    {
        if (!$this->document) {
            return null;
        }

        return strtolower(pathinfo($this->document, PATHINFO_EXTENSION));
    }

    /**
     * Check if file is PDF
     */
    public function getIsPdfAttribute()
    {
        return $this->file_extension === 'pdf';
    }

    /**
     * Validasi sebelum menyimpan
     */
    protected static function boot()
    {
        parent::boot();

        // ✅ PERBAIKAN: Validasi sebelum menyimpan
        static::saving(function ($model) {
            // Pastikan status memiliki nilai default jika null
            if (empty($model->status)) {
                $model->status = 'active';
            }
            
            // Pastikan status hanya berisi nilai yang valid
            $validStatuses = ['active', 'inactive', 'draft'];
            if (!in_array(strtolower($model->status), $validStatuses)) {
                $model->status = 'active';
            }
            
            // Pastikan tahun valid
            if ($model->year < 2000 || $model->year > date('Y') + 5) {
                throw new \Exception('Tahun harus antara 2000 dan ' . (date('Y') + 5));
            }
        });

        // ✅ PERBAIKAN: Hapus file ketika model dihapus
        static::deleting(function ($model) {
            if ($model->file_path) {
                try {
                    if (Storage::exists($model->file_path)) {
                        Storage::delete($model->file_path);
                    }
                } catch (\Exception $e) {
                    // Log error tetapi jangan hentikan proses
                    \Log::error('Gagal menghapus file term condition: ' . $e->getMessage());
                }
            }
        });
    }

    /**
     * Set status dengan validasi
     */
    public function setStatusAttribute($value)
    {
        $validStatuses = ['active', 'inactive', 'draft'];
        $status = strtolower(trim($value));
        
        if (in_array($status, $validStatuses)) {
            $this->attributes['status'] = $status;
        } else {
            $this->attributes['status'] = 'active'; // Default
        }
    }

    /**
     * Set tahun dengan validasi
     */
    public function setYearAttribute($value)
    {
        $year = (int) $value;
        $currentYear = date('Y');
        
        if ($year >= 2000 && $year <= $currentYear + 5) {
            $this->attributes['year'] = $year;
        } else {
            throw new \Exception('Tahun harus antara 2000 dan ' . ($currentYear + 5));
        }
    }

    /**
     * Get all valid status options
     */
    public static function getStatusOptions()
    {
        return [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'draft' => 'Draft',
        ];
    }

    /**
     * Check if document is active
     */
    public function isActive()
    {
        return strtolower($this->status) === 'active';
    }

    /**
     * Check if document is inactive
     */
    public function isInactive()
    {
        return strtolower($this->status) === 'inactive';
    }

    /**
     * Check if document is draft
     */
    public function isDraft()
    {
        return strtolower($this->status) === 'draft';
    }

    /**
     * Activate document
     */
    public function activate()
    {
        $this->status = 'active';
        return $this->save();
    }

    /**
     * Deactivate document
     */
    public function deactivate()
    {
        $this->status = 'inactive';
        return $this->save();
    }

    /**
     * Get storage path with fallback
     */
    public function getStoragePath()
    {
        return $this->file_path;
    }

    /**
     * Get public URL with fallback
     */
    public function getPublicUrl()
    {
        return $this->download_url;
    }

    /**
     * Get document info array
     */
    public function getDocumentInfo()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'file_exists' => $this->file_exists,
            'file_size' => $this->file_size_formatted,
            'original_filename' => $this->original_filename,
            'download_url' => $this->download_url,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}