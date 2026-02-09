<?php

namespace App\Http\Controllers\Publication\Gallery;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideosController extends Controller
{
    /**
     * Display a listing of the videos.
     */
    public function index(Request $request)
    {
        $query = Video::query();
        
        // Search by title or description
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('video_code', 'like', "%{$search}%");
            });
        }
        
        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by year
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('created_at', $request->year);
        }
        
        // Order by latest
        $query->orderBy('created_at', 'desc');
        
        // Pagination
        $perPage = $request->per_page ?? 10;
        $videos = $query->paginate($perPage)->withQueryString();
        
        return view('admin.media.gallery.videos_list', compact('videos'));
    }

    /**
     * Show the form for creating a new video.
     */
    public function create()
    {
        // Generate video code untuk form
        $videoCode = 'VID-' . time() . rand(100, 999);
        return view('admin.media.gallery.videos_form', compact('videoCode'));
    }

    /**
     * Store a newly created video in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_link' => 'required|url',
            'type' => 'required|in:video,live',
            'status' => 'required|in:draft,view',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:1024',
        ]);
        
        // Generate video code otomatis
        $validated['video_code'] = 'VID-' . time() . rand(100, 999);
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            
            // Validate file size (1MB max)
            if ($file->getSize() > 1048576) {
                return back()->withErrors(['thumbnail' => 'Thumbnail size should not exceed 1MB.'])->withInput();
            }
            
            // Create directory if not exists
            $directory = 'uploads/videos/thumbnails';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory, 0755, true);
            }
            
            // Generate filename: video_code_title
            $titleSlug = Str::slug($validated['title']);
            $filename = $validated['video_code'] . '_' . $titleSlug . '.' . $file->getClientOriginalExtension();
            
            // Store file
            $path = $file->storeAs($directory, $filename, 'public');
            $validated['thumbnail'] = 'storage/' . $path;
        } else {
            // Set default thumbnail
            $validated['thumbnail'] = null;
        }
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        
        // Ensure slug is unique
        $slug = $validated['slug'];
        $counter = 1;
        while (Video::where('slug', $slug)->exists()) {
            $slug = $validated['slug'] . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;
        
        Video::create($validated);
        
        return redirect()->route('admin.videos.index')
                         ->with('success', 'Video created successfully.');
    }

    /**
     * Display the specified video.
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);
        
        // Check if request is AJAX (for Quick View modal)
        if (request()->ajax() || request()->expectsJson() || request()->wantsJson() || 
            request()->header('X-Requested-With') == 'XMLHttpRequest') {
            
            // Return JSON response
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $video->id,
                    'video_code' => $video->video_code,
                    'title' => $video->title,
                    'description' => $video->description,
                    'youtube_link' => $video->youtube_link,
                    'thumbnail' => $video->thumbnail ? asset($video->thumbnail) : null,
                    'type' => $video->type,
                    'status' => $video->status,
                    'created_at' => $video->created_at->toDateTimeString(),
                    'updated_at' => $video->updated_at->toDateTimeString(),
                ]
            ]);
        }
        
        return view('admin.media.gallery.videos_show', compact('video'));
    }

    /**
     * Show the form for editing the specified video.
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('admin.media.gallery.videos_edit', compact('video'));
    }

    /**
     * Update the specified video in storage.
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'youtube_link' => 'required|url',
            'type' => 'required|in:video,live',
            'status' => 'required|in:draft,view',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:1024',
            'slug' => 'required|string|max:255|unique:media_videos,slug,' . $id,
        ]);
        
        // Keep existing video code
        $validated['video_code'] = $video->video_code;
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            
            // Validate file size
            if ($file->getSize() > 1048576) {
                return back()->withErrors(['thumbnail' => 'Thumbnail size should not exceed 1MB.'])->withInput();
            }
            
            // Delete old thumbnail if exists
            if ($video->thumbnail && Storage::exists(str_replace('storage/', 'public/', $video->thumbnail))) {
                Storage::delete(str_replace('storage/', 'public/', $video->thumbnail));
            }
            
            // Create directory if not exists
            $directory = 'uploads/videos/thumbnails';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory, 0755, true);
            }
            
            // Generate filename
            $titleSlug = Str::slug($validated['title']);
            $filename = $validated['video_code'] . '_' . $titleSlug . '.' . $file->getClientOriginalExtension();
            
            // Store file
            $path = $file->storeAs($directory, $filename, 'public');
            $validated['thumbnail'] = 'storage/' . $path;
            
        } elseif ($request->has('remove_thumbnail') && $request->remove_thumbnail == '1') {
            // Remove thumbnail if requested
            if ($video->thumbnail && Storage::exists(str_replace('storage/', 'public/', $video->thumbnail))) {
                Storage::delete(str_replace('storage/', 'public/', $video->thumbnail));
            }
            $validated['thumbnail'] = null;
            
        } else {
            // Keep existing thumbnail
            unset($validated['thumbnail']);
        }
        
        $video->update($validated);
        
        return redirect()->route('admin.videos.index')
                         ->with('success', 'Video updated successfully.');
    }

    /**
     * Remove the specified video from storage.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        
        // Delete thumbnail if exists
        if ($video->thumbnail && Storage::exists(str_replace('storage/', 'public/', $video->thumbnail))) {
            Storage::delete(str_replace('storage/', 'public/', $video->thumbnail));
        }
        
        $video->delete();
        
        // Return JSON response if AJAX request
        if (request()->ajax() || request()->expectsJson() || 
            request()->header('X-Requested-With') == 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Video deleted successfully.'
            ]);
        }
        
        return redirect()->route('admin.videos.index')
                         ->with('success', 'Video deleted successfully.');
    }
    
    /**
     * Bulk delete videos - SIMPLE VERSION (sama seperti news_list)
     * PERBAIKAN: Handle jika tidak ada selected
     */
    public function bulkDestroy(Request $request)
    {
        try {
            // Debug log
            \Log::info('Bulk Delete Request:', $request->all());
            
            // PERBAIKAN: Gunakan input() bukan validate() agar lebih fleksibel
            $selectedIds = (array) $request->input('selected', []);
            
            // Filter array untuk menghapus null/empty values
            $selectedIds = array_filter($selectedIds);
            
            if (empty($selectedIds)) {
                return redirect()->route('admin.videos.index')
                    ->with('error', 'No videos selected for deletion.');
            }
            
            // Validasi IDs (pastikan numeric)
            $validIds = [];
            foreach ($selectedIds as $id) {
                if (is_numeric($id) && $id > 0) {
                    $validIds[] = $id;
                }
            }
            
            if (empty($validIds)) {
                return redirect()->route('admin.videos.index')
                    ->with('error', 'Invalid video IDs provided.');
            }
            
            // Get videos with thumbnails
            $videos = Video::whereIn('id', $validIds)->get();
            
            // Delete thumbnails
            foreach ($videos as $video) {
                if ($video->thumbnail && Storage::exists(str_replace('storage/', 'public/', $video->thumbnail))) {
                    Storage::delete(str_replace('storage/', 'public/', $video->thumbnail));
                }
            }
            
            // Bulk delete
            $deletedCount = Video::whereIn('id', $validIds)->delete();
            
            return redirect()->route('admin.videos.index')
                ->with('success', "Successfully deleted {$deletedCount} video(s)");
                
        } catch (\Exception $e) {
            \Log::error('Bulk delete error: ' . $e->getMessage());
            
            return redirect()->route('admin.videos.index')
                ->with('error', 'Failed to delete videos: ' . $e->getMessage());
        }
    }
    
    /**
     * Get video details for AJAX request (Quick View)
     * PERBAIKAN: Gunakan find() bukan findOrFail() untuk error handling yang lebih baik
     */
    public function getVideoAjax($id)
    {
        try {
            $video = Video::find($id);
            
            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found'
                ], 404);
            }
            
            // Extract YouTube ID for thumbnail fallback
            $youtubeId = $this->extractYouTubeId($video->youtube_link);
            
            // Tentukan thumbnail URL
            $thumbnail = $video->thumbnail ? asset($video->thumbnail) : null;
            
            // Jika thumbnail tidak ada, gunakan thumbnail dari YouTube
            if (!$thumbnail && $youtubeId) {
                $thumbnail = "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
            }
            
            // Jika masih tidak ada, gunakan placeholder
            if (!$thumbnail) {
                $thumbnail = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="256" height="144"><rect width="256" height="144" fill="#f1f5f9"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="Arial" font-size="14" fill="#999">No Thumbnail</text></svg>');
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $video->id,
                    'video_code' => $video->video_code ?? 'N/A',
                    'title' => $video->title ?? 'No Title',
                    'description' => $video->description ?? '',
                    'youtube_link' => $video->youtube_link ?? '#',
                    'thumbnail' => $thumbnail,
                    'type' => $video->type ?? 'video',
                    'status' => $video->status ?? 'draft',
                    'created_at' => $video->created_at ? $video->created_at->toDateTimeString() : now()->toDateTimeString(),
                    'updated_at' => $video->updated_at ? $video->updated_at->toDateTimeString() : now()->toDateTimeString(),
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get video AJAX error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load video details'
            ], 500);
        }
    }
    
    /**
     * Extract YouTube video ID from URL
     */
    private function extractYouTubeId($url)
    {
        if (empty($url)) {
            return null;
        }
        
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
     * Toggle video status (quick toggle)
     */
    public function toggleStatus($id)
    {
        try {
            $video = Video::findOrFail($id);
            $newStatus = $video->status == 'view' ? 'draft' : 'view';
            $video->update(['status' => $newStatus]);
            
            $statusText = $newStatus == 'view' ? 'Published' : 'Draft';
            
            return redirect()->back()
                ->with('success', "Video status changed to " . $statusText);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error changing video status: ' . $e->getMessage());
        }
    }
}