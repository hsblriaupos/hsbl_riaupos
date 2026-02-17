<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TermCondition extends Model
{
    protected $table = 'term_conditions';

    protected $fillable = [
        'title',
        'links', // ✅ MENYIMPAN LINK GOOGLE DRIVE (FILE ATAU FOLDER)
        'year',
        'status',
    ];

    protected $casts = [
        'year' => 'integer',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_badge',
        'status_text',
        'google_drive_info', // ✅ INFORMASI LENGKAP (ID DAN TIPE)
        'google_drive_file_id', // ✅ FILE ID (UNTUK FILE)
        'google_drive_folder_id', // ✅ FOLDER ID (UNTUK FOLDER)
        'google_drive_embed_url', // ✅ EMBED URL (HANYA UNTUK FILE)
        'has_valid_link', // ✅ CEK VALIDITAS LINK
        'link_type', // ✅ TIPE LINK: 'file' ATAU 'folder'
        'is_file', // ✅ CEK APakah FILE
        'is_folder', // ✅ CEK APAKAH FOLDER
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
     * Scope untuk mencari berdasarkan link (jika ada)
     */
    public function scopeHasLink($query)
    {
        return $query->whereNotNull('links')->where('links', '!=', '');
    }

    /**
     * ✅ PERBAIKAN: Ambil informasi lengkap Google Drive (ID dan tipe)
     */
    public function getGoogleDriveInfoAttribute()
    {
        if (!$this->links) {
            return null;
        }

        $url = $this->links;

        // Pattern untuk FILE Google Drive
        $filePatterns = [
            '/\/d\/([a-zA-Z0-9_-]+)/',
            '/id=([a-zA-Z0-9_-]+)/',
            '/file\/d\/([a-zA-Z0-9_-]+)/',
            '/open\?id=([a-zA-Z0-9_-]+)/',
            '/uc\?.*id=([a-zA-Z0-9_-]+)/',
        ];

        // ✅ Pattern untuk FOLDER Google Drive
        $folderPatterns = [
            '/\/folders\/([a-zA-Z0-9_-]+)/',
            '/folderview\?id=([a-zA-Z0-9_-]+)/',
            '/\/drive\/folders\/([a-zA-Z0-9_-]+)/',
            '/\/drive\/u\/\d+\/folders\/([a-zA-Z0-9_-]+)/',
        ];

        // Cek pattern file
        foreach ($filePatterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return [
                    'id' => $matches[1],
                    'type' => 'file'
                ];
            }
        }

        // Cek pattern folder
        foreach ($folderPatterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return [
                    'id' => $matches[1],
                    'type' => 'folder'
                ];
            }
        }

        return null;
    }

    /**
     * ✅ PERBAIKAN: Ambil Google Drive File ID (untuk file)
     */
    public function getGoogleDriveFileIdAttribute()
    {
        $info = $this->google_drive_info;
        
        if ($info && $info['type'] === 'file') {
            return $info['id'];
        }

        return null;
    }

    /**
     * ✅ PERBAIKAN: Ambil Google Drive Folder ID (untuk folder)
     */
    public function getGoogleDriveFolderIdAttribute()
    {
        $info = $this->google_drive_info;
        
        if ($info && $info['type'] === 'folder') {
            return $info['id'];
        }

        return null;
    }

    /**
     * ✅ PERBAIKAN: Dapatkan tipe link (file/folder)
     */
    public function getLinkTypeAttribute()
    {
        $info = $this->google_drive_info;
        return $info ? $info['type'] : null;
    }

    /**
     * ✅ PERBAIKAN: Cek apakah link adalah file
     */
    public function getIsFileAttribute()
    {
        return $this->link_type === 'file';
    }

    /**
     * ✅ PERBAIKAN: Cek apakah link adalah folder
     */
    public function getIsFolderAttribute()
    {
        return $this->link_type === 'folder';
    }

    /**
     * ✅ PERBAIKAN: Generate embed URL untuk Google Drive (HANYA UNTUK FILE)
     */
    public function getGoogleDriveEmbedUrlAttribute()
    {
        if (!$this->is_file || !$this->google_drive_file_id) {
            return null;
        }

        // URL untuk preview/embed Google Drive
        return "https://drive.google.com/file/d/{$this->google_drive_file_id}/preview";
    }

    /**
     * ✅ PERBAIKAN: Cek apakah link valid (mengandung file ID ATAU folder ID)
     */
    public function getHasValidLinkAttribute()
    {
        return !is_null($this->google_drive_info);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
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
        $status = strtolower($this->status);
        
        return match($status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'draft' => 'Draft',
            default => 'Unknown',
        };
    }

    /**
     * ✅ PERBAIKAN: Validasi sebelum menyimpan
     */
    protected static function boot()
    {
        parent::boot();

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

            // ✅ VALIDASI LINK GOOGLE DRIVE (WAJIB VALID)
            if (!empty($model->links)) {
                // Cek apakah link Google Drive valid (file ATAU folder)
                if (!$model->has_valid_link) {
                    throw new \Exception('Link Google Drive tidak valid. Pastikan menggunakan link file atau folder Google Drive yang benar.');
                }
            }
        });

        // ✅ TIDAK PERLU LAGI MENGHAPUS FILE KARENA MENGGUNAKAN LINK
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
     * Set links dengan validasi
     */
    public function setLinksAttribute($value)
    {
        // Bersihkan URL dari whitespace
        $value = trim($value);
        
        // Jika kosong, set ke null
        if (empty($value)) {
            $this->attributes['links'] = null;
            return;
        }

        // Hapus trailing slash
        $value = rtrim($value, '/');

        // Tambahkan https:// jika tidak ada protocol
        if (!preg_match('/^https?:\/\//', $value)) {
            $value = 'https://' . $value;
        }

        $this->attributes['links'] = $value;
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
     * Check if has valid Google Drive link
     */
    public function hasValidLink()
    {
        return $this->has_valid_link;
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
     * ✅ PERBAIKAN: Get direct download link untuk Google Drive (HANYA UNTUK FILE)
     */
    public function getDirectDownloadLink()
    {
        if (!$this->is_file || !$this->google_drive_file_id) {
            return $this->links; // Untuk folder, kembalikan link folder
        }

        return "https://drive.google.com/uc?export=download&id={$this->google_drive_file_id}";
    }

    /**
     * ✅ PERBAIKAN: Get thumbnail/image preview URL (HANYA UNTUK FILE)
     */
    public function getThumbnailUrl()
    {
        if (!$this->is_file || !$this->google_drive_file_id) {
            return null;
        }

        return "https://drive.google.com/thumbnail?id={$this->google_drive_file_id}";
    }

    /**
     * ✅ PERBAIKAN: Get view URL (untuk file atau folder)
     */
    public function getViewUrl()
    {
        if ($this->is_file) {
            return "https://drive.google.com/file/d/{$this->google_drive_file_id}/view";
        }
        
        return $this->links; // Untuk folder, kembalikan link asli
    }

    /**
     * ✅ PERBAIKAN: Get icon class berdasarkan tipe
     */
    public function getIconClassAttribute()
    {
        if ($this->is_file) {
            return 'fa-file-pdf text-danger';
        } elseif ($this->is_folder) {
            return 'fa-folder text-warning';
        }
        
        return 'fa-link text-primary';
    }

    /**
     * ✅ PERBAIKAN: Get formatted link untuk display
     */
    public function getFormattedLinkAttribute()
    {
        if (!$this->links) {
            return null;
        }

        if ($this->is_file) {
            return 'File PDF';
        } elseif ($this->is_folder) {
            return 'Folder';
        }

        return 'Link';
    }

    /**
     * ✅ PERBAIKAN: Get document info array (LENGKAP)
     */
    public function getDocumentInfo()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'year' => $this->year,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'links' => $this->links,
            'has_valid_link' => $this->has_valid_link,
            'link_type' => $this->link_type,
            'is_file' => $this->is_file,
            'is_folder' => $this->is_folder,
            'google_drive_file_id' => $this->google_drive_file_id,
            'google_drive_folder_id' => $this->google_drive_folder_id,
            'embed_url' => $this->google_drive_embed_url,
            'view_url' => $this->getViewUrl(),
            'download_url' => $this->getDirectDownloadLink(),
            'thumbnail_url' => $this->getThumbnailUrl(),
            'icon_class' => $this->icon_class,
            'formatted_link' => $this->formatted_link,
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}