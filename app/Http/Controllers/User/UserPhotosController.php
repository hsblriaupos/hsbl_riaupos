<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MediaGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class UserPhotosController extends Controller
{
    /**
     * Display a listing of photo galleries for public users.
     */
    public function index(Request $request)
    {
        // Start query - only published galleries
        $query = MediaGallery::where('status', 'published');
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('school_name', 'like', "%{$search}%")
                  ->orWhere('competition', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply competition filter
        if ($request->filled('competition')) {
            $query->where('competition', $request->competition);
        }
        
        // Apply season filter
        if ($request->filled('season')) {
            $query->where('season', $request->season);
        }
        
        // Apply series filter
        if ($request->filled('series')) {
            $query->where('series', $request->series);
        }
        
        // Apply sorting
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'downloads':
                $query->orderBy('download_count', 'desc');
                break;
            case 'name':
                $query->orderBy('school_name', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }
        
        // Get unique values for filter dropdowns (only from published galleries)
        $competitions = MediaGallery::where('status', 'published')
            ->distinct('competition')
            ->whereNotNull('competition')
            ->pluck('competition')
            ->filter()
            ->sort()
            ->values();
        
        $seasons = MediaGallery::where('status', 'published')
            ->distinct('season')
            ->whereNotNull('season')
            ->pluck('season')
            ->filter()
            ->sortDesc()
            ->values();
        
        $series = MediaGallery::where('status', 'published')
            ->distinct('series')
            ->whereNotNull('series')
            ->pluck('series')
            ->filter()
            ->sort()
            ->values();
        
        // Paginate results - show 12 per page for grid layout
        $perPage = $request->per_page ?? 12;
        $galleries = $query->paginate($perPage);
        
        // Check if there are active filters
        $hasActiveFilters = $this->hasActiveFilters($request);
        $activeFilterCount = $this->countActiveFilters($request);
        
        return view('user.media.gallery.photos_list', compact(
            'galleries', 
            'competitions', 
            'seasons', 
            'series',
            'hasActiveFilters',
            'activeFilterCount'
        ));
    }
    
    /**
     * Check if there are active filters in request
     */
    private function hasActiveFilters(Request $request)
    {
        return $request->filled('search') || 
               $request->filled('competition') || 
               $request->filled('season') || 
               $request->filled('series') || 
               ($request->filled('sort') && $request->sort !== 'newest');
    }
    
    /**
     * Count active filters in request
     */
    private function countActiveFilters(Request $request)
    {
        $count = 0;
        
        if ($request->filled('search')) $count++;
        if ($request->filled('competition')) $count++;
        if ($request->filled('season')) $count++;
        if ($request->filled('series')) $count++;
        if ($request->filled('sort') && $request->sort !== 'newest') $count++;
        
        return $count;
    }
    
    /**
     * Download the specified photo gallery - OPTIMIZED VERSION
     */
    public function download($id)
    {
        // Cari gallery tanpa loading relationships yang tidak perlu
        $gallery = MediaGallery::select('id', 'file', 'original_filename', 'file_type', 'download_count', 'school_name')
                              ->where('status', 'published')
                              ->findOrFail($id);
        
        // Check if file exists
        if (!$gallery->file || !Storage::exists('public/' . $gallery->file)) {
            // Return JSON response untuk AJAX atau redirect untuk browser biasa
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found.',
                    'redirect' => route('user.gallery.photos.index')
                ], 404);
            }
            
            return redirect()->route('user.gallery.photos.index')
                ->with('error', 'File not found. It may have been deleted.');
        }
        
        // Increment download count ASYNCHRONOUSLY (tidak blocking)
        $this->incrementDownloadAsync($gallery);
        
        // Get file path
        $filePath = Storage::path('public/' . $gallery->file);
        $originalFilename = $gallery->original_filename ?: 
                           'hsbl_gallery_' . str_replace(' ', '_', $gallery->school_name ?? '') . '_' . $gallery->id . '.zip';
        
        // Clean filename for download
        $originalFilename = $this->cleanFilename($originalFilename);
        
        // Return file download response langsung tanpa processing tambahan
        return Response::download($filePath, $originalFilename, [
            'Content-Type' => $gallery->file_type ?: 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $originalFilename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
    
    /**
     * Increment download count asynchronously tanpa blocking
     */
    private function incrementDownloadAsync($gallery)
    {
        try {
            // Gunakan query langsung untuk lebih cepat
            \DB::table('media_gallery')
                ->where('id', $gallery->id)
                ->increment('download_count');
                
        } catch (\Exception $e) {
            // Log error tapi jangan stop download
            \Log::error('Error incrementing download count for gallery ' . $gallery->id . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Clean filename for download.
     */
    private function cleanFilename($filename)
    {
        // Remove path traversal attempts
        $filename = basename($filename);
        
        // Replace spaces and special characters
        $filename = preg_replace('/[^\w\.\-]/', '_', $filename);
        
        // Ensure it has a .zip extension if missing
        if (!preg_match('/\.(zip|rar|7z)$/i', $filename)) {
            $filename .= '.zip';
        }
        
        return $filename;
    }
    
    /**
     * Get gallery details for modal via AJAX.
     */
    public function getDetails($id)
    {
        $gallery = MediaGallery::where('status', 'published')->find($id);
        
        if (!$gallery) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery not found or not published.',
            ], 404);
        }
        
        $data = [
            'id' => $gallery->id,
            'school_name' => $gallery->school_name,
            'competition' => $gallery->competition,
            'season' => $gallery->season,
            'series' => $gallery->series,
            'file_type' => $gallery->file_type,
            'original_filename' => $gallery->original_filename,
            'description' => $gallery->description,
            'status' => $gallery->status,
            'download_count' => $gallery->download_count,
            'file_size' => $gallery->file_size,
            'created_at' => $gallery->created_at->format('F d, Y'),
            'updated_at' => $gallery->updated_at->format('F d, Y'),
            'file_size_formatted' => $this->formatBytes($gallery->file_size),
            'download_url' => route('user.gallery.photos.download', $gallery->id),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
    
    /**
     * Get filter options for dropdowns via AJAX.
     */
    public function getFilterOptions(Request $request)
    {
        $type = $request->get('type', 'competition');
        
        switch ($type) {
            case 'competition':
                $data = MediaGallery::where('status', 'published')
                    ->distinct('competition')
                    ->whereNotNull('competition')
                    ->orderBy('competition')
                    ->pluck('competition');
                break;
                
            case 'season':
                $data = MediaGallery::where('status', 'published')
                    ->distinct('season')
                    ->whereNotNull('season')
                    ->orderBy('season', 'desc')
                    ->pluck('season');
                break;
                
            case 'series':
                $data = MediaGallery::where('status', 'published')
                    ->distinct('series')
                    ->whereNotNull('series')
                    ->orderBy('series')
                    ->pluck('series');
                break;
                
            case 'school':
                $data = MediaGallery::where('status', 'published')
                    ->distinct('school_name')
                    ->whereNotNull('school_name')
                    ->orderBy('school_name')
                    ->pluck('school_name');
                break;
                
            default:
                $data = collect();
        }
        
        return response()->json([
            'success' => true,
            'data' => $data->values(),
        ]);
    }
    
    /**
     * Get statistics for the statistics section.
     */
    public function getStatistics()
    {
        $totalGalleries = MediaGallery::where('status', 'published')->count();
        $totalDownloads = MediaGallery::where('status', 'published')->sum('download_count');
        $uniqueSchools = MediaGallery::where('status', 'published')
            ->distinct('school_name')
            ->count('school_name');
        $uniqueCompetitions = MediaGallery::where('status', 'published')
            ->distinct('competition')
            ->count('competition');
        
        // Get recent galleries (last 5)
        $recentGalleries = MediaGallery::where('status', 'published')
            ->latest()
            ->limit(5)
            ->get(['id', 'school_name', 'competition', 'created_at']);
        
        // Get most downloaded galleries
        $topDownloads = MediaGallery::where('status', 'published')
            ->orderBy('download_count', 'desc')
            ->limit(5)
            ->get(['id', 'school_name', 'download_count']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_galleries' => $totalGalleries,
                'total_downloads' => $totalDownloads,
                'unique_schools' => $uniqueSchools,
                'unique_competitions' => $uniqueCompetitions,
                'recent_galleries' => $recentGalleries,
                'top_downloads' => $topDownloads,
            ],
        ]);
    }
    
    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes <= 0) {
            return '0 Bytes';
        }
        
        $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        
        return round($bytes / pow(1024, $i), $precision) . ' ' . $units[$i];
    }
    
    /**
     * Search galleries via AJAX for real-time search.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'data' => [],
            ]);
        }
        
        $galleries = MediaGallery::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('school_name', 'like', "%{$query}%")
                  ->orWhere('competition', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('school_name')
            ->limit(10)
            ->get(['id', 'school_name', 'competition', 'season', 'series']);
        
        return response()->json([
            'success' => true,
            'data' => $galleries,
        ]);
    }
    
    /**
     * Show gallery details for modal view.
     */
    public function show($id)
    {
        $gallery = MediaGallery::where('status', 'published')->findOrFail($id);
        
        // Format data for JSON response
        $formattedGallery = [
            'id' => $gallery->id,
            'school_name' => $gallery->school_name,
            'competition' => $gallery->competition,
            'season' => $gallery->season,
            'series' => $gallery->series,
            'file_type' => $gallery->file_type,
            'original_filename' => $gallery->original_filename,
            'description' => $gallery->description,
            'status' => $gallery->status,
            'download_count' => $gallery->download_count,
            'file_size' => $gallery->file_size,
            'created_at' => $gallery->created_at->toISOString(),
            'updated_at' => $gallery->updated_at->toISOString(),
        ];
        
        return response()->json($formattedGallery);
    }
}