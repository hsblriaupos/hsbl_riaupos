<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'media_videos';

    protected $fillable = [
        'video_code', 'title', 'thumbnail', 'description',
        'youtube_link', 'slug', 'type', 'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope for published videos
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'view');
    }

    /**
     * Scope for draft videos
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for video type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for searching
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('video_code', 'like', "%{$search}%");
        });
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail && file_exists(public_path($this->thumbnail))) {
            return asset($this->thumbnail);
        }
        
        // Return YouTube thumbnail if no custom thumbnail
        if ($this->youtube_link) {
            $youtubeId = $this->extractYouTubeId($this->youtube_link);
            if ($youtubeId) {
                return "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
            }
        }
        
        return asset('images/default-video-thumbnail.jpg');
    }

    /**
     * Get embed URL for YouTube
     */
    public function getEmbedUrlAttribute()
    {
        if ($this->youtube_link) {
            $youtubeId = $this->extractYouTubeId($this->youtube_link);
            if ($youtubeId) {
                return "https://www.youtube.com/embed/{$youtubeId}";
            }
        }
        
        return $this->youtube_link;
    }

    /**
     * Extract YouTube video ID
     */
    private function extractYouTubeId($url)
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/.*[?&]v=([^&]+)/',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }

    /**
     * Check if video is published
     */
    public function isPublished()
    {
        return $this->status == 'view';
    }

    /**
     * Check if video is live type
     */
    public function isLive()
    {
        return $this->type == 'live';
    }
}