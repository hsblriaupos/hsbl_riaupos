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
        // Start query
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
        
        if ($request->filled('has_photo')) {
            if ($request->has_photo === 'yes') {
                $query->whereNotNull('photo')->where('photo', '!=', '');
            } elseif ($request->has_photo === 'no') {
                $query->whereNull('photo')->orWhere('photo', '');
            }
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
        $perPage = $request->per_page ?? 15;
        $galleries = $query->latest()->paginate($perPage)->withQueryString();
        
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
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
            'file' => 'required|file|mimes:zip,rar,7z|max:5120000', // 5GB
            'competition' => 'required|string|max:100',
            'season' => 'required|string|max:50',
            'series' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:published,draft,archived',
        ], [
            'photo.required' => 'Cover photo is required.',
            'photo.image' => 'Cover photo must be an image.',
            'photo.mimes' => 'Cover photo must be a JPG, PNG, GIF, or WEBP file.',
            'photo.max' => 'Cover photo size must not exceed 5MB.',
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
        
        DB::beginTransaction();
        
        try {
            // Handle photo upload - FIXED PATH
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                
                // Generate clean school name for filename
                $cleanSchoolName = preg_replace('/[^a-z0-9]/i', '-', strtolower($schoolName));
                $cleanSchoolName = trim(preg_replace('/-+/', '-', $cleanSchoolName), '-');
                
                // Generate unique filename for photo
                $photoFilename = $cleanSchoolName . '_cover_' . time() . '.' . $photo->getClientOriginalExtension();
                
                // FIX: Store photo in photos/cover directory using public disk
                // This will save to: storage/app/public/photos/cover/filename.jpg
                $photoPath = $photo->storeAs('photos/cover', $photoFilename, 'public');
                
                // No need to remove 'public/' because we're using the public disk correctly
                // Path yang disimpan di database: 'photos/cover/filename.jpg'
            }
            
            // Handle file upload - FIXED PATH
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalFilename = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getMimeType();
                
                // Generate clean school name for filename
                $cleanSchoolName = preg_replace('/[^a-z0-9]/i', '-', strtolower($schoolName));
                $cleanSchoolName = trim(preg_replace('/-+/', '-', $cleanSchoolName), '-');
                
                // Generate unique filename for ZIP
                $filename = $cleanSchoolName . '_gallery_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                
                // FIX: Store file in gallery directory using public disk
                // This will save to: storage/app/public/gallery/filename.zip
                $filePath = $file->storeAs('gallery', $filename, 'public');
                
                // Create gallery record with correct paths
                MediaGallery::create([
                    'school_name' => $schoolName,
                    'photo' => $photoPath, // This will be 'photos/cover/filename.jpg'
                    'file' => $filePath,    // This will be 'gallery/filename.zip'
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
            }
            
            DB::commit();
            
            return redirect()->route('admin.gallery.photos.index')
                ->with('success', 'Photo gallery uploaded successfully with cover photo!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded files if any - FIXED PATH CHECKING
            if (isset($photoPath) && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            \Log::error('Gallery upload failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Failed to upload gallery. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update the specified photo gallery in storage.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $gallery = MediaGallery::findOrFail($id);
        
        // Validate request
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB optional
            'file' => 'nullable|file|mimes:zip,rar,7z|max:5120000', // 5GB optional
            'competition' => 'required|string|max:100',
            'season' => 'required|string|max:50',
            'series' => 'required|string|max:50',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:published,draft,archived',
        ], [
            'photo.image' => 'Cover photo must be an image.',
            'photo.mimes' => 'Cover photo must be a JPG, PNG, GIF, or WEBP file.',
            'photo.max' => 'Cover photo size must not exceed 5MB.',
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
        
        DB::beginTransaction();
        
        try {
            // Prepare update data
            $updateData = [
                'school_name' => $schoolName,
                'competition' => $request->competition,
                'season' => $request->season,
                'series' => $request->series,
                'description' => $request->description,
                'status' => $request->status,
            ];
            
            // Handle photo update if new photo is provided - FIXED PATH
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                
                // Generate clean school name for filename
                $cleanSchoolName = preg_replace('/[^a-z0-9]/i', '-', strtolower($schoolName));
                $cleanSchoolName = trim(preg_replace('/-+/', '-', $cleanSchoolName), '-');
                
                // Generate unique filename for photo
                $photoFilename = $cleanSchoolName . '_cover_' . time() . '.' . $photo->getClientOriginalExtension();
                
                // Delete old photo if exists - FIXED PATH CHECKING
                if ($gallery->photo && Storage::disk('public')->exists($gallery->photo)) {
                    Storage::disk('public')->delete($gallery->photo);
                }
                
                // Store new photo using public disk
                $photoPath = $photo->storeAs('photos/cover', $photoFilename, 'public');
                $updateData['photo'] = $photoPath;
            }
            
            // Handle file update if new file is provided - FIXED PATH
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $originalFilename = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $fileType = $file->getMimeType();
                
                // Generate clean school name for filename
                $cleanSchoolName = preg_replace('/[^a-z0-9]/i', '-', strtolower($schoolName));
                $cleanSchoolName = trim(preg_replace('/-+/', '-', $cleanSchoolName), '-');
                
                // Generate unique filename for ZIP
                $filename = $cleanSchoolName . '_gallery_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                
                // Delete old file if exists - FIXED PATH CHECKING
                if ($gallery->file && Storage::disk('public')->exists($gallery->file)) {
                    Storage::disk('public')->delete($gallery->file);
                }
                
                // Store new file using public disk
                $filePath = $file->storeAs('gallery', $filename, 'public');
                
                $updateData['file'] = $filePath;
                $updateData['original_filename'] = $originalFilename;
                $updateData['file_size'] = $fileSize;
                $updateData['file_type'] = $fileType;
            }
            
            // Update gallery record
            $gallery->update($updateData);
            
            DB::commit();
            
            return redirect()->route('admin.gallery.photos.index')
                ->with('success', 'Photo gallery updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Gallery update failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Failed to update gallery. Please try again. Error: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Remove the specified photo gallery from storage.
     * PERMANENT DELETE (tidak ada soft delete)
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $gallery = MediaGallery::findOrFail($id);
        
        DB::beginTransaction();
        
        try {
            // Delete photo file from storage - FIXED PATH CHECKING
            if ($gallery->photo && Storage::disk('public')->exists($gallery->photo)) {
                Storage::disk('public')->delete($gallery->photo);
            }
            
            // Delete ZIP file from storage - FIXED PATH CHECKING
            if ($gallery->file && Storage::disk('public')->exists($gallery->file)) {
                Storage::disk('public')->delete($gallery->file);
            }
            
            // PERMANENT DELETE - Tidak ada soft delete
            $gallery->delete();
            
            DB::commit();
            
            return redirect()->route('admin.gallery.photos.index')
                ->with('success', 'Photo gallery deleted permanently!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Gallery deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete gallery. Please try again.');
        }
    }
    
    /**
     * Bulk delete photo galleries.
     * PERMANENT DELETE (tidak ada soft delete)
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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
        
        DB::beginTransaction();
        
        try {
            $deletedCount = 0;
            
            foreach ($selectedIds as $id) {
                $gallery = MediaGallery::find($id);
                
                if ($gallery) {
                    // Delete photo file from storage - FIXED PATH CHECKING
                    if ($gallery->photo && Storage::disk('public')->exists($gallery->photo)) {
                        Storage::disk('public')->delete($gallery->photo);
                    }
                    
                    // Delete ZIP file from storage - FIXED PATH CHECKING
                    if ($gallery->file && Storage::disk('public')->exists($gallery->file)) {
                        Storage::disk('public')->delete($gallery->file);
                    }
                    
                    // PERMANENT DELETE
                    $gallery->delete();
                    $deletedCount++;
                }
            }
            
            DB::commit();
            
            $message = $deletedCount == 1 
                ? '1 photo gallery deleted permanently!' 
                : "{$deletedCount} photo galleries deleted permanently!";
            
            return redirect()->route('admin.gallery.photos.index')
                ->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Bulk deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete galleries. Please try again.');
        }
    }
    
    /**
     * Bulk download photo galleries.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
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
            // Add cover photo if exists - FIXED PATH CHECKING
            if ($gallery->photo && Storage::disk('public')->exists($gallery->photo)) {
                $photoPath = Storage::disk('public')->path($gallery->photo);
                $photoFilename = 'cover_' . $gallery->school_name . '_' . basename($gallery->photo);
                $photoFilename = $this->makeSafeFilename($photoFilename);
                
                if ($zip->addFile($photoPath, 'covers/' . $photoFilename)) {
                    $addedFiles++;
                }
            }
            
            // Add ZIP file if exists - FIXED PATH CHECKING
            if ($gallery->file && Storage::disk('public')->exists($gallery->file)) {
                $filePath = Storage::disk('public')->path($gallery->file);
                $safeFilename = $this->makeSafeFilename($gallery->original_filename ?: basename($gallery->file));
                
                // Organize by school name
                $schoolFolder = preg_replace('/[^a-z0-9]/i', '_', $gallery->school_name);
                $zipPath = $schoolFolder . '/' . $safeFilename;
                
                if ($zip->addFile($filePath, $zipPath)) {
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
     * 
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function download($id)
    {
        $gallery = MediaGallery::findOrFail($id);
        
        // Check if file exists - FIXED PATH CHECKING
        if (!$gallery->file || !Storage::disk('public')->exists($gallery->file)) {
            return redirect()->back()
                ->with('error', 'File not found. It may have been deleted.');
        }
        
        // Increment download count
        $gallery->increment('download_count');
        
        // Get file path and original filename
        $filePath = Storage::disk('public')->path($gallery->file);
        $originalFilename = $gallery->original_filename ?: 'gallery_' . $gallery->id . '.zip';
        
        // Return file download response
        return response()->download($filePath, $originalFilename, [
            'Content-Type' => $gallery->file_type ?: 'application/zip',
        ]);
    }
    
    /**
     * Download cover photo only.
     * 
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadCover($id)
    {
        $gallery = MediaGallery::findOrFail($id);
        
        // Check if photo exists - FIXED PATH CHECKING
        if (!$gallery->hasPhoto() || !Storage::disk('public')->exists($gallery->photo)) {
            return redirect()->back()
                ->with('error', 'Cover photo not found.');
        }
        
        // Get file path and filename
        $filePath = Storage::disk('public')->path($gallery->photo);
        $filename = 'cover_' . $gallery->school_name . '_' . basename($gallery->photo);
        
        // Return file download response
        return response()->download($filePath, $filename);
    }
    
    /**
     * Increment download count via AJAX.
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
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
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
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
     * 
     * @return \Illuminate\View\View
     */
    public function statistics()
    {
        $totalGalleries = MediaGallery::count();
        $totalDownloads = MediaGallery::sum('download_count');
        $totalFileSize = MediaGallery::sum('file_size');
        $totalWithPhotos = MediaGallery::whereNotNull('photo')->where('photo', '!=', '')->count();
        
        $byCompetition = MediaGallery::select('competition', DB::raw('count(*) as count'))
            ->whereNotNull('competition')
            ->groupBy('competition')
            ->orderBy('count', 'desc')
            ->get();
        
        $byStatus = MediaGallery::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        $byPhotoStatus = [
            'with_photo' => $totalWithPhotos,
            'without_photo' => $totalGalleries - $totalWithPhotos,
        ];
        
        $recentGalleries = MediaGallery::latest()
            ->limit(10)
            ->get();
        
        return view('admin.media.gallery.photos_statistics', compact(
            'totalGalleries',
            'totalDownloads',
            'totalFileSize',
            'totalWithPhotos',
            'byCompetition',
            'byStatus',
            'byPhotoStatus',
            'recentGalleries'
        ));
    }
    
    /**
     * Helper method to make safe filenames for ZIP archive
     * 
     * @param string $filename
     * @return string
     */
    private function makeSafeFilename($filename)
    {
        // Remove path traversal attempts
        $filename = basename($filename);
        
        // Remove special characters
        $filename = preg_replace('/[^\w\.\-]/', '_', $filename);
        
        // Limit length
        if (strlen($filename) > 100) {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $name = pathinfo($filename, PATHINFO_FILENAME);
            $name = substr($name, 0, 90);
            $filename = $name . '.' . $ext;
        }
        
        return $filename;
    }
    
    /**
     * Clean up temporary directory
     * 
     * @param string $tempDir
     * @return void
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
    
    /**
     * Update cover photo only.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCover(Request $request, $id)
    {
        $gallery = MediaGallery::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                
                // Generate clean school name for filename
                $cleanSchoolName = preg_replace('/[^a-z0-9]/i', '-', strtolower($gallery->school_name));
                $cleanSchoolName = trim(preg_replace('/-+/', '-', $cleanSchoolName), '-');
                
                // Generate unique filename for photo
                $photoFilename = $cleanSchoolName . '_cover_' . time() . '.' . $photo->getClientOriginalExtension();
                
                // Delete old photo if exists - FIXED PATH CHECKING
                if ($gallery->photo && Storage::disk('public')->exists($gallery->photo)) {
                    Storage::disk('public')->delete($gallery->photo);
                }
                
                // Store new photo using public disk
                $photoPath = $photo->storeAs('photos/cover', $photoFilename, 'public');
                $gallery->photo = $photoPath;
                $gallery->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Cover photo updated successfully!',
                    'photo_url' => $gallery->photo_url
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No photo file provided.'
            ], 400);
            
        } catch (\Exception $e) {
            \Log::error('Cover update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cover photo.'
            ], 500);
        }
    }
}