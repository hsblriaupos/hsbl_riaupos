<?php

namespace App\Http\Controllers\Publication\Gallery;

use App\Http\Controllers\Controller;
use App\Models\MediaGallery;
use App\Models\TeamList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PhotosController extends Controller
{
    /**
     * Display a listing of the photo galleries.
     */
    public function index(Request $request)
    {
        // Start query - TIDAK PERLU withTrashed() karena tidak ada soft delete
        $query = MediaGallery::query();
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('school_name', 'like', "%{$search}%")
                  ->orWhere('original_filename', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('competition')) {
            $query->where('competition', $request->competition);
        }
        
        if ($request->filled('season')) {
            $query->where('season', $request->season);
        }
        
        if ($request->filled('series')) {
            $query->where('series', $request->series);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Get unique values for filter dropdowns
        $competitions = MediaGallery::distinct('competition')
            ->whereNotNull('competition')
            ->pluck('competition')
            ->filter()
            ->sort();
        
        $seasons = MediaGallery::distinct('season')
            ->whereNotNull('season')
            ->pluck('season')
            ->filter()
            ->sortDesc();
        
        $series = MediaGallery::distinct('series')
            ->whereNotNull('series')
            ->pluck('series')
            ->filter()
            ->sort();
        
        // Paginate results
        $perPage = $request->per_page ?? 10;
        $galleries = $query->latest()->paginate($perPage);
        
        return view('admin.media.gallery.photos_list', compact('galleries', 'competitions', 'seasons', 'series'));
    }
    
    /**
     * Show the form for creating a new photo gallery.
     */
    public function create()
    {
        return view('admin.media.gallery.photos_form');
    }
    
    /**
     * Show the form for editing the specified photo gallery.
     */
    public function edit($id)
    {
        $gallery = MediaGallery::findOrFail($id);
        return view('admin.media.gallery.photos_edit', compact('gallery'));
    }
    
    /**
     * Store a newly created photo gallery in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'file' => 'required|file|mimes:zip,rar,7z|max:5120000', // 5GB
            'competition' => 'required|string|max:100',
            'season' => 'required|string|max:50',
            'series' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:published,draft,archived',
        ], [
            'file.required' => 'ZIP file is required.',
            'file.mimes' => 'File must be a ZIP, RAR, or 7Z archive.',
            'file.max' => 'File size must not exceed 5GB.',
            'school_name.required' => 'School name is required.',
            'competition.required' => 'Competition is required.',
            'season.required' => 'Season is required.',
            'series.required' => 'Series is required.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }
        
        // Handle school name
        $schoolName = $request->school_name;
        if ($schoolName === 'other' && $request->filled('manual_school_name')) {
            $schoolName = $request->manual_school_name;
        }
        
        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = $file->getMimeType();
            
            // Generate unique filename
            $filename = 'gallery_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Store file
            $path = $file->storeAs('public/gallery', $filename);
            
            // Create gallery record
            MediaGallery::create([
                'school_name' => $schoolName,
                'file' => 'gallery/' . $filename,
                'original_filename' => $originalFilename,
                'file_size' => $fileSize,
                'file_type' => $fileType,
                'competition' => $request->competition,
                'season' => $request->season,
                'series' => $request->series,
                'description' => $request->description,
                'status' => $request->status,
                'download_count' => 0,
            ]);
            
            return redirect()->route('admin.gallery.photos.index')
                ->with('success', 'Photo gallery uploaded successfully!');
        }
        
        return redirect()->back()
            ->with('error', 'File upload failed. Please try again.')
            ->withInput();
    }
    
    /**
     * Update the specified photo gallery in storage.
     */
    public function update(Request $request, $id)
    {
        $gallery = MediaGallery::findOrFail($id);
        
        // Validate request
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:zip,rar,7z|max:5120000',
            'competition' => 'required|string|max:100',
            'season' => 'required|string|max:50',
            'series' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:published,draft,archived',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }
        
        // Handle school name
        $schoolName = $request->school_name;
        if ($schoolName === 'other' && $request->filled('manual_school_name')) {
            $schoolName = $request->manual_school_name;
        }
        
        // Update basic fields
        $gallery->update([
            'school_name' => $schoolName,
            'competition' => $request->competition,
            'season' => $request->season,
            'series' => $request->series,
            'description' => $request->description,
            'status' => $request->status,
        ]);
        
        // Handle file update if new file is provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFilename = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = $file->getMimeType();
            
            // Generate unique filename
            $filename = 'gallery_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            
            // Delete old file if exists
            if ($gallery->file && Storage::exists('public/' . $gallery->file)) {
                Storage::delete('public/' . $gallery->file);
            }
            
            // Store new file
            $path = $file->storeAs('public/gallery', $filename);
            
            // Update file information
            $gallery->update([
                'file' => 'gallery/' . $filename,
                'original_filename' => $originalFilename,
                'file_size' => $fileSize,
                'file_type' => $fileType,
            ]);
        }
        
        return redirect()->route('admin.gallery.photos.index')
            ->with('success', 'Photo gallery updated successfully!');
    }
    
    /**
     * Remove the specified photo gallery from storage.
     * PERMANENT DELETE (tidak ada soft delete)
     */
    public function destroy($id)
    {
        $gallery = MediaGallery::findOrFail($id);
        
        // Delete file from storage
        if ($gallery->file && Storage::exists('public/' . $gallery->file)) {
            Storage::delete('public/' . $gallery->file);
        }
        
        // PERMANENT DELETE - Tidak ada soft delete
        $gallery->delete();
        
        return redirect()->route('admin.gallery.photos.index')
            ->with('success', 'Photo gallery deleted permanently!');
    }
    
    /**
     * Bulk delete photo galleries.
     * PERMANENT DELETE (tidak ada soft delete)
     */
    public function bulkDestroy(Request $request)
    {
        // Get selected IDs from request
        $selectedIds = $request->input('selected', []);
        
        if (empty($selectedIds)) {
            return redirect()->back()
                ->with('error', 'No items selected for deletion.');
        }
        
        // Convert to array if not already
        if (!is_array($selectedIds)) {
            $selectedIds = [$selectedIds];
        }
        
        // Validate each ID exists in the database
        $validator = Validator::make(['selected' => $selectedIds], [
            'selected' => 'required|array|min:1',
            'selected.*' => 'exists:media_gallery,id',
        ], [
            'selected.required' => 'No items selected for deletion.',
            'selected.*.exists' => 'One or more selected items do not exist.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', $validator->errors()->first());
        }
        
        $deletedCount = 0;
        
        foreach ($selectedIds as $id) {
            try {
                $gallery = MediaGallery::find($id);
                
                if ($gallery) {
                    // Delete file from storage
                    if ($gallery->file && Storage::exists('public/' . $gallery->file)) {
                        Storage::delete('public/' . $gallery->file);
                    }
                    
                    // PERMANENT DELETE - Tidak ada soft delete
                    $gallery->delete();
                    $deletedCount++;
                }
            } catch (\Exception $e) {
                // Log error but continue with other deletions
                \Log::error('Error deleting gallery ' . $id . ': ' . $e->getMessage());
            }
        }
        
        $message = $deletedCount == 1 
            ? '1 photo gallery deleted permanently!' 
            : "{$deletedCount} photo galleries deleted permanently!";
        
        return redirect()->route('admin.gallery.photos.index')
            ->with('success', $message);
    }
    
    /**
     * Bulk download photo galleries.
     */
    public function bulkDownload(Request $request)
    {
        // Get selected IDs from request
        $selectedIds = $request->input('selected', []);
        
        if (empty($selectedIds)) {
            return redirect()->back()
                ->with('error', 'No items selected for download.');
        }
        
        // Convert to array if not already
        if (!is_array($selectedIds)) {
            $selectedIds = [$selectedIds];
        }
        
        // Limit to 10 files maximum
        if (count($selectedIds) > 10) {
            return redirect()->back()
                ->with('error', 'Maximum 10 files can be downloaded at once.');
        }
        
        // Validate each ID exists in the database
        $validator = Validator::make(['selected' => $selectedIds], [
            'selected' => 'required|array|min:1|max:10',
            'selected.*' => 'exists:media_gallery,id',
        ], [
            'selected.max' => 'Maximum 10 files can be downloaded at once.',
            'selected.*.exists' => 'One or more selected items do not exist.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', $validator->errors()->first());
        }
        
        // Get selected galleries
        $galleries = MediaGallery::whereIn('id', $selectedIds)->get();
        
        if ($galleries->isEmpty()) {
            return redirect()->back()
                ->with('error', 'No valid galleries selected.');
        }
        
        // Create temporary directory
        $tempDir = storage_path('app/temp/' . Str::random(20));
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        $zip = new \ZipArchive();
        $zipFileName = 'galleries_' . date('Y-m-d_H-i') . '.zip';
        $zipPath = $tempDir . '/' . $zipFileName;
        
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            return redirect()->back()
                ->with('error', 'Cannot create ZIP file.');
        }
        
        // Add each gallery file to ZIP
        $addedFiles = 0;
        foreach ($galleries as $gallery) {
            if ($gallery->file && Storage::exists('public/' . $gallery->file)) {
                $filePath = Storage::path('public/' . $gallery->file);
                $safeFilename = $this->makeSafeFilename($gallery->original_filename ?: basename($gallery->file));
                
                if ($zip->addFile($filePath, $safeFilename)) {
                    $addedFiles++;
                    
                    // Increment download count
                    $gallery->increment('download_count');
                }
            }
        }
        
        $zip->close();
        
        if ($addedFiles === 0) {
            // Clean up temp directory
            $this->cleanupTempDirectory($tempDir);
            
            return redirect()->back()
                ->with('error', 'No valid files found to download.');
        }
        
        // Return the ZIP file for download
        $headers = [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipFileName . '"',
        ];
        
        $response = response()->download($zipPath, $zipFileName, $headers);
        
        // Clean up temporary directory after download
        $response->deleteFileAfterSend(true);
        register_shutdown_function(function () use ($tempDir) {
            $this->cleanupTempDirectory($tempDir);
        });
        
        return $response;
    }
    
    /**
     * Download the specified photo gallery.
     */
    public function download($id)
    {
        $gallery = MediaGallery::findOrFail($id);
        
        // Check if file exists
        if (!$gallery->file || !Storage::exists('public/' . $gallery->file)) {
            return redirect()->back()
                ->with('error', 'File not found. It may have been deleted.');
        }
        
        // Increment download count
        $gallery->increment('download_count');
        
        // Get file path and original filename
        $filePath = Storage::path('public/' . $gallery->file);
        $originalFilename = $gallery->original_filename ?: 'gallery_' . $gallery->id . '.zip';
        
        // Return file download response
        return response()->download($filePath, $originalFilename, [
            'Content-Type' => $gallery->file_type ?: 'application/zip',
        ]);
    }
    
    /**
     * Increment download count via AJAX.
     */
    public function incrementDownload($id)
    {
        $gallery = MediaGallery::find($id);
        
        if ($gallery) {
            $gallery->increment('download_count');
            
            return response()->json([
                'success' => true,
                'download_count' => $gallery->download_count,
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Gallery not found',
        ], 404);
    }
    
    /**
     * Change gallery status (publish/unpublish).
     */
    public function publish($id)
    {
        $gallery = MediaGallery::findOrFail($id);
        $gallery->update(['status' => 'published']);
        
        return redirect()->back()
            ->with('success', 'Gallery published successfully!');
    }
    
    /**
     * Change gallery status (publish/unpublish).
     */
    public function unpublish($id)
    {
        $gallery = MediaGallery::findOrFail($id);
        $gallery->update(['status' => 'draft']);
        
        return redirect()->back()
            ->with('success', 'Gallery unpublished successfully!');
    }
    
    /**
     * Toggle gallery status.
     */
    public function toggleStatus($id)
    {
        $gallery = MediaGallery::findOrFail($id);
        $newStatus = $gallery->status === 'published' ? 'draft' : 'published';
        $gallery->update(['status' => $newStatus]);
        
        $action = $newStatus === 'published' ? 'published' : 'unpublished';
        
        return redirect()->back()
            ->with('success', "Gallery {$action} successfully!");
    }
    
    /**
     * Get school data for autocomplete or dropdown population.
     */
    public function getSchools(Request $request)
    {
        $query = $request->input('q');
        
        $schools = TeamList::select('school_name')
            ->whereNotNull('school_name')
            ->where('school_name', '!=', '')
            ->when($query, function ($q) use ($query) {
                return $q->where('school_name', 'like', "%{$query}%");
            })
            ->distinct()
            ->orderBy('school_name')
            ->limit(20)
            ->pluck('school_name');
        
        return response()->json($schools);
    }
    
    /**
     * Get competition data for dropdown.
     */
    public function getCompetitions(Request $request)
    {
        $competitions = TeamList::select('competition')
            ->whereNotNull('competition')
            ->where('competition', '!=', '')
            ->distinct()
            ->orderBy('competition')
            ->pluck('competition');
        
        return response()->json($competitions);
    }
    
    /**
     * Get seasons for dropdown.
     */
    public function getSeasons(Request $request)
    {
        $seasons = TeamList::select('season')
            ->whereNotNull('season')
            ->where('season', '!=', '')
            ->distinct()
            ->orderBy('season', 'desc')
            ->pluck('season');
        
        return response()->json($seasons);
    }
    
    /**
     * Get series for dropdown.
     */
    public function getSeries(Request $request)
    {
        $series = TeamList::select('series')
            ->whereNotNull('series')
            ->where('series', '!=', '')
            ->distinct()
            ->orderBy('series')
            ->pluck('series');
        
        return response()->json($series);
    }
    
    /**
     * Format bytes to human readable format.
     */
    public function formatBytes($bytes, $precision = 2)
    {
        if ($bytes <= 0) return '0 Bytes';
        
        $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));
        
        return round($bytes / pow(1024, $i), $precision) . ' ' . $units[$i];
    }
    
    /**
     * Show the form for adding a new photo gallery.
     * Alias for create method to match route naming.
     */
    public function form()
    {
        return $this->create();
    }
    
    /**
     * Get gallery statistics.
     */
    public function statistics()
    {
        $totalGalleries = MediaGallery::count();
        $totalDownloads = MediaGallery::sum('download_count');
        $totalFileSize = MediaGallery::sum('file_size');
        
        $byCompetition = MediaGallery::select('competition', DB::raw('count(*) as count'))
            ->whereNotNull('competition')
            ->groupBy('competition')
            ->orderBy('count', 'desc')
            ->get();
        
        $byStatus = MediaGallery::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        $recentGalleries = MediaGallery::latest()
            ->limit(10)
            ->get();
        
        return view('admin.media.gallery.photos_statistics', compact(
            'totalGalleries',
            'totalDownloads',
            'totalFileSize',
            'byCompetition',
            'byStatus',
            'recentGalleries'
        ));
    }
    
    /**
     * Helper method to make safe filenames for ZIP archive
     */
    private function makeSafeFilename($filename)
    {
        // Remove path traversal attempts
        $filename = basename($filename);
        
        // Remove special characters
        $filename = preg_replace('/[^\w\.\-]/', '_', $filename);
        
        // Ensure extension is present
        if (!preg_match('/\.\w+$/', $filename)) {
            $filename .= '.zip';
        }
        
        return $filename;
    }
    
    /**
     * Clean up temporary directory
     */
    private function cleanupTempDirectory($tempDir)
    {
        if (file_exists($tempDir)) {
            $files = glob($tempDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($tempDir);
        }
    }
}