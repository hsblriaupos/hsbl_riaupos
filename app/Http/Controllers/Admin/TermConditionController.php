<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        // Validasi
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:200',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'file' => 'required|file|mimes:pdf|max:2048', // Max 2MB
        ], [
            'title.required' => 'Judul dokumen wajib diisi',
            'title.max' => 'Judul maksimal 200 karakter',
            'year.required' => 'Tahun wajib diisi',
            'year.integer' => 'Tahun harus berupa angka',
            'year.min' => 'Tahun minimal 2000',
            'year.max' => 'Tahun tidak boleh lebih dari ' . (date('Y') + 5),
            'file.required' => 'File dokumen wajib diupload',
            'file.file' => 'File harus berupa dokumen',
            'file.mimes' => 'Format file harus PDF',
            'file.max' => 'Ukuran file maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        try {
            // Upload file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                // Validasi ukuran file
                if ($file->getSize() > 2 * 1024 * 1024) { // 2MB in bytes
                    return redirect()->back()
                        ->with('error', 'Ukuran file melebihi 2MB')
                        ->withInput();
                }
                
                // Validasi tipe file
                if ($file->getClientOriginalExtension() !== 'pdf') {
                    return redirect()->back()
                        ->with('error', 'Hanya file PDF yang diizinkan')
                        ->withInput();
                }
                
                // Generate unique filename
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $sanitizedName = Str::slug($originalName, '_');
                $filename = 'term_' . $sanitizedName . '_' . time() . '.pdf';
                
                // Simpan file ke storage
                $path = $file->storeAs('public/term_conditions', $filename);
                
                // Simpan ke database
                TermCondition::create([
                    'title' => $request->title,
                    'year' => $request->year,
                    'document' => 'storage/term_conditions/' . $filename,
                    'status' => 'active', // Default value
                ]);

                return redirect()->route('admin.term_conditions.index')
                    ->with('success', 'Dokumen Syarat & Ketentuan berhasil diupload!');
            }

            return redirect()->back()
                ->with('error', 'File tidak ditemukan')
                ->withInput();

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
            
            // Hapus file dari storage
            if ($term->document) {
                // Konversi path storage ke path file sebenarnya
                $filePath = str_replace('storage/', 'public/', $term->document);
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }
            
            // Hapus dari database
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
                    // Hapus file dari storage
                    if ($term->document) {
                        $filePath = str_replace('storage/', 'public/', $term->document);
                        if (Storage::exists($filePath)) {
                            Storage::delete($filePath);
                        }
                    }
                    
                    // Hapus dari database
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
     * Download the specified file.
     */
    public function download(string $id)
    {
        try {
            $term = TermCondition::findOrFail($id);
            
            if (!$term->document) {
                return redirect()->back()
                    ->with('error', 'File tidak ditemukan');
            }

            // Get storage path
            $filePath = str_replace('storage/', 'public/', $term->document);
            
            if (!Storage::exists($filePath)) {
                return redirect()->back()
                    ->with('error', 'File tidak ditemukan di server');
            }

            // Get original filename or generate one
            $originalName = pathinfo($term->document, PATHINFO_FILENAME);
            $filename = $originalName ?: 'syarat_ketentuan_' . $term->year . '.pdf';
            
            // Pastikan extension PDF
            if (!Str::endsWith(strtolower($filename), '.pdf')) {
                $filename .= '.pdf';
            }

            // Clean filename
            $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

            return Storage::download($filePath, $filename);

        } catch (\Exception $e) {
            \Log::error('TermCondition Download Error: ' . $e->getMessage());
            return redirect()->route('admin.term_conditions.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * View the specified file.
     */
    public function view(string $id)
    {
        try {
            $term = TermCondition::findOrFail($id);
            
            if (!$term->document) {
                return redirect()->back()
                    ->with('error', 'File tidak ditemukan');
            }

            // Get storage path
            $filePath = str_replace('storage/', 'public/', $term->document);
            
            if (!Storage::exists($filePath)) {
                return redirect()->back()
                    ->with('error', 'File tidak ditemukan di server');
            }

            // Get file content
            $fileContent = Storage::get($filePath);
            
            // Get original filename
            $originalName = pathinfo($term->document, PATHINFO_BASENAME);

            return response($fileContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . ($originalName ?: 'document.pdf') . '"');

        } catch (\Exception $e) {
            \Log::error('TermCondition View Error: ' . $e->getMessage());
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
            'status' => 'required|in:active,inactive,draft',
        ], [
            'title.required' => 'Judul dokumen wajib diisi',
            'title.max' => 'Judul maksimal 200 karakter',
            'year.required' => 'Tahun wajib diisi',
            'year.integer' => 'Tahun harus berupa angka',
            'year.min' => 'Tahun minimal 2000',
            'year.max' => 'Tahun tidak boleh lebih dari ' . (date('Y') + 5),
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
            
            // Update data
            $term->title = $request->title;
            $term->year = $request->year;
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
}