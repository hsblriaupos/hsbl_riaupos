<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TermConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terms = TermCondition::orderBy('year', 'desc')
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.term_conditions.index', compact('terms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi untuk link Google Drive
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'links' => 'required|url|max:500',
        ], [
            'title.required' => 'Judul dokumen wajib diisi',
            'title.max' => 'Judul maksimal 200 karakter',
            'year.required' => 'Tahun wajib diisi',
            'year.integer' => 'Tahun harus berupa angka',
            'year.min' => 'Tahun minimal 2000',
            'year.max' => 'Tahun tidak boleh lebih dari ' . (date('Y') + 5),
            'links.required' => 'Link Google Drive wajib diisi',
            'links.url' => 'Format link tidak valid',
            'links.max' => 'Link maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        try {
            // Validasi tambahan untuk Google Drive link
            $links = trim($request->links);
            
            // Tambahkan https:// jika belum ada
            if (!preg_match('/^https?:\/\//', $links)) {
                $links = 'https://' . $links;
            }
            
            // ✅ PERBAIKAN: Validasi apakah link Google Drive valid (file ATAU folder)
            $validationResult = $this->isValidGoogleDriveLink($links);
            if (!$validationResult['valid']) {
                return redirect()->back()
                    ->with('error', 'Link Google Drive tidak valid. ' . $validationResult['message'])
                    ->withInput();
            }

            // Simpan ke database
            TermCondition::create([
                'title' => $request->title,
                'year' => $request->year,
                'links' => $links,
                'status' => 'active', // Default value
            ]);

            return redirect()->route('admin.term_conditions.index')
                ->with('success', 'Link Google Drive berhasil disimpan!');

        } catch (\Exception $e) {
            // Debug error untuk mengetahui penyebab sebenarnya
            \Log::error('TermCondition Store Error: ' . $e->getMessage());
            \Log::error('Request Data: ', $request->all());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $term = TermCondition::findOrFail($id);
            
            // Hapus dari database (tidak perlu hapus file karena menggunakan link)
            $term->delete();

            return redirect()->route('admin.term_conditions.index')
                ->with('success', 'Dokumen berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('TermCondition Destroy Error: ' . $e->getMessage());
            return redirect()->route('admin.term_conditions.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete selected items.
     */
    public function destroySelected(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:term_conditions,id',
        ]);

        try {
            $selectedIds = $request->selected_ids;
            $deletedCount = 0;

            foreach ($selectedIds as $id) {
                $term = TermCondition::find($id);
                
                if ($term) {
                    // Hapus dari database (tidak perlu hapus file)
                    $term->delete();
                    $deletedCount++;
                }
            }

            return redirect()->route('admin.term_conditions.index')
                ->with('success', $deletedCount . ' dokumen berhasil dihapus!');

        } catch (\Exception $e) {
            \Log::error('TermCondition Bulk Destroy Error: ' . $e->getMessage());
            return redirect()->route('admin.term_conditions.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status aktif/tidak aktif
     */
    public function toggleStatus(string $id)
    {
        try {
            $term = TermCondition::findOrFail($id);
            
            // Validasi status yang valid
            $validStatuses = ['active', 'inactive', 'draft'];
            $currentStatus = $term->status;
            
            if (!in_array($currentStatus, $validStatuses)) {
                $currentStatus = 'active'; // Default jika status tidak valid
            }
            
            // Toggle status
            $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';
            $term->status = $newStatus;
            $term->save();

            return redirect()->route('admin.term_conditions.index')
                ->with('success', 'Status dokumen berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('TermCondition Toggle Status Error: ' . $e->getMessage());
            return redirect()->route('admin.term_conditions.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Edit the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $term = TermCondition::findOrFail($id);
            
            return view('admin.term_conditions.edit', compact('term'));
            
        } catch (\Exception $e) {
            \Log::error('TermCondition Edit Error: ' . $e->getMessage());
            return redirect()->route('admin.term_conditions.index')
                ->with('error', 'Dokumen tidak ditemukan');
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'links' => 'nullable|url|max:500',
            'status' => 'required|in:active,inactive,draft',
        ], [
            'title.required' => 'Judul dokumen wajib diisi',
            'title.max' => 'Judul maksimal 200 karakter',
            'year.required' => 'Tahun wajib diisi',
            'year.integer' => 'Tahun harus berupa angka',
            'year.min' => 'Tahun minimal 2000',
            'year.max' => 'Tahun tidak boleh lebih dari ' . (date('Y') + 5),
            'links.url' => 'Format link tidak valid',
            'links.max' => 'Link maksimal 500 karakter',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }
        
        try {
            $term = TermCondition::findOrFail($id);
            
            // Proses link jika ada
            $links = null;
            if ($request->filled('links')) {
                $links = trim($request->links);
                
                // Tambahkan https:// jika belum ada
                if (!preg_match('/^https?:\/\//', $links)) {
                    $links = 'https://' . $links;
                }
                
                // ✅ PERBAIKAN: Validasi apakah link Google Drive valid (opsional untuk update)
                $validationResult = $this->isValidGoogleDriveLink($links);
                if (!$validationResult['valid']) {
                    return redirect()->back()
                        ->with('error', 'Link Google Drive tidak valid. ' . $validationResult['message'])
                        ->withInput();
                }
            }
            
            // Update data
            $term->title = $request->title;
            $term->year = $request->year;
            $term->links = $links;
            $term->status = $request->status;
            $term->save();
            
            return redirect()->route('admin.term_conditions.index')
                ->with('success', 'Dokumen berhasil diperbarui!');
                
        } catch (\Exception $e) {
            \Log::error('TermCondition Update Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * ✅ PERBAIKAN: Validasi Google Drive Link (FILE ATAU FOLDER)
     * Return array dengan status valid dan pesan
     */
    private function isValidGoogleDriveLink($url)
    {
        // Pastikan URL mengandung drive.google.com
        if (!str_contains($url, 'drive.google.com')) {
            return [
                'valid' => false,
                'message' => 'URL harus dari domain drive.google.com'
            ];
        }

        // Pattern untuk mendeteksi berbagai format URL Google Drive (FILE)
        $filePatterns = [
            '/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/',
            '/drive\.google\.com\/open\?id=([a-zA-Z0-9_-]+)/',
            '/drive\.google\.com\/uc\?.*id=([a-zA-Z0-9_-]+)/',
            '/docs\.google\.com\/(?:document|spreadsheets|presentation)\/d\/([a-zA-Z0-9_-]+)/',
        ];

        // ✅ TAMBAHKAN: Pattern untuk FOLDER Google Drive
        $folderPatterns = [
            '/drive\.google\.com\/drive\/folders\/([a-zA-Z0-9_-]+)/',
            '/drive\.google\.com\/folderview\?id=([a-zA-Z0-9_-]+)/',
            '/drive\.google\.com\/drive\/u\/\d+\/folders\/([a-zA-Z0-9_-]+)/',
        ];

        // Cek pattern untuk file
        foreach ($filePatterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return [
                    'valid' => true,
                    'type' => 'file',
                    'message' => 'Link file Google Drive valid'
                ];
            }
        }

        // ✅ Cek pattern untuk folder
        foreach ($folderPatterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return [
                    'valid' => true,
                    'type' => 'folder',
                    'message' => 'Link folder Google Drive valid'
                ];
            }
        }

        return [
            'valid' => false,
            'message' => 'Format link tidak dikenali. Pastikan menggunakan link file atau folder Google Drive yang benar.'
        ];
    }

    /**
     * ✅ PERBAIKAN: Extract ID dari Google Drive link (FILE ATAU FOLDER)
     */
    private function extractGoogleDriveId($url)
    {
        // Pattern untuk file
        $filePatterns = [
            '/\/d\/([a-zA-Z0-9_-]+)/',
            '/id=([a-zA-Z0-9_-]+)/',
            '/file\/d\/([a-zA-Z0-9_-]+)/',
            '/open\?id=([a-zA-Z0-9_-]+)/',
        ];

        // ✅ Pattern untuk folder
        $folderPatterns = [
            '/\/folders\/([a-zA-Z0-9_-]+)/',
            '/folderview\?id=([a-zA-Z0-9_-]+)/',
            '/\/u\/\d+\/folders\/([a-zA-Z0-9_-]+)/',
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

        // ✅ Cek pattern folder
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
     * Get preview URL from Google Drive link
     */
    public function getPreviewUrl(string $id)
    {
        try {
            $term = TermCondition::findOrFail($id);
            
            $validationResult = $this->isValidGoogleDriveLink($term->links);
            if (!$validationResult['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link Google Drive tidak valid'
                ], 400);
            }

            $extracted = $this->extractGoogleDriveId($term->links);
            
            // Buat embed URL berdasarkan tipe (file atau folder)
            if ($extracted['type'] === 'file') {
                $embedUrl = "https://drive.google.com/file/d/{$extracted['id']}/preview";
            } else {
                // Untuk folder, gunakan URL folder biasa (tidak bisa diembed)
                $embedUrl = $term->links;
            }

            return response()->json([
                'success' => true,
                'embed_url' => $embedUrl,
                'type' => $extracted['type'],
                'title' => $term->title
            ]);

        } catch (\Exception $e) {
            \Log::error('TermCondition GetPreviewUrl Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate Google Drive link via AJAX
     */
    public function validateLink(Request $request)
    {
        $request->validate([
            'links' => 'required|url'
        ]);

        $validationResult = $this->isValidGoogleDriveLink($request->links);
        
        // Extract file/folder ID jika valid
        $extracted = null;
        if ($validationResult['valid']) {
            $extracted = $this->extractGoogleDriveId($request->links);
        }

        return response()->json([
            'valid' => $validationResult['valid'],
            'type' => $extracted['type'] ?? null,
            'id' => $extracted['id'] ?? null,
            'message' => $validationResult['message']
        ]);
    }

    /**
     * Get direct download link
     */
    public function getDirectDownloadLink(string $id)
    {
        try {
            $term = TermCondition::findOrFail($id);
            
            $validationResult = $this->isValidGoogleDriveLink($term->links);
            if (!$validationResult['valid']) {
                return redirect()->back()
                    ->with('error', 'Link Google Drive tidak valid');
            }

            $extracted = $this->extractGoogleDriveId($term->links);
            
            // Untuk file, buat link download langsung
            if ($extracted['type'] === 'file') {
                $downloadLink = "https://drive.google.com/uc?export=download&id={$extracted['id']}";
                return redirect()->away($downloadLink);
            }
            
            // Untuk folder, redirect ke folder itu sendiri
            return redirect()->away($term->links);

        } catch (\Exception $e) {
            \Log::error('TermCondition GetDirectDownloadLink Error: ' . $e->getMessage());
            return redirect()->route('admin.term_conditions.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}