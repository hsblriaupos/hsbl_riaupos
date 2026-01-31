<?php

namespace App\Http\Controllers\Publication;

use App\Http\Controllers\Controller;
use App\Models\MatchResult;
use App\Models\TeamList; // Ganti School dengan TeamList
use App\Models\AddData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PubMatchResult extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MatchResult::with(['team1', 'team2']);
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('team1', function($q) use ($search) {
                    $q->where('school_name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('team2', function($q) use ($search) {
                    $q->where('school_name', 'like', '%' . $search . '%');
                })
                ->orWhere('competition', 'like', '%' . $search . '%')
                ->orWhere('phase', 'like', '%' . $search . '%')
                ->orWhere('series', 'like', '%' . $search . '%');
            });
        }
        
        // Season filter
        if ($request->filled('season')) {
            $query->where('season', $request->season);
        }
        
        // Competition filter
        if ($request->filled('competition')) {
            $query->where('competition', $request->competition);
        }
        
        // Competition type filter
        if ($request->filled('competition_type')) {
            $query->where('competition_type', $request->competition_type);
        }
        
        // Series filter
        if ($request->filled('series')) {
            $query->where('series', $request->series);
        }
        
        // Phase filter
        if ($request->filled('phase')) {
            $query->where('phase', $request->phase);
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Get unique seasons for filter dropdown dari AddData
        $seasons = AddData::select('season_name as season')
            ->distinct()
            ->whereNotNull('season_name')
            ->orderBy('season_name', 'desc')
            ->pluck('season');
        
        // Get unique competitions for filter dropdown dari AddData
        $competitions = AddData::select('competition')
            ->distinct()
            ->whereNotNull('competition')
            ->orderBy('competition')
            ->pluck('competition');
        
        // Get unique competition types for filter dropdown dari AddData
        $competitionTypes = AddData::select('competition_type')
            ->distinct()
            ->whereNotNull('competition_type')
            ->orderBy('competition_type')
            ->pluck('competition_type');
        
        // Get unique series for filter dropdown dari data yang sudah ada
        $seriesList = MatchResult::select('series')
            ->distinct()
            ->whereNotNull('series')
            ->orderBy('series')
            ->pluck('series');
        
        // Get unique phases for filter dropdown dari AddData
        $phases = AddData::select('phase')
            ->distinct()
            ->whereNotNull('phase')
            ->orderBy('phase')
            ->pluck('phase');
        
        // Pagination with per_page parameter
        $perPage = $request->get('per_page', 10);
        $results = $query->orderBy('match_date', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate($perPage);
        
        return view('publication.pub_result', compact(
            'results', 
            'seasons',
            'competitions', 
            'competitionTypes',
            'seriesList',
            'phases'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ganti School dengan TeamList - ambil data dari tabel team_list
        $teams = TeamList::orderBy('school_name')->get(); // Menggunakan school_name dari team_list
        
        // Get data dari tabel AddData - TANPA ALIAS
        $seasons = AddData::select('season_name')
            ->distinct()
            ->whereNotNull('season_name')
            ->orderBy('season_name', 'desc')
            ->get();
        
        $competitions = AddData::select('competition')
            ->distinct()
            ->whereNotNull('competition')
            ->orderBy('competition')
            ->get();
        
        $competitionTypes = AddData::select('competition_type')
            ->distinct()
            ->whereNotNull('competition_type')
            ->orderBy('competition_type')
            ->get();
        
        // Series manual - Kabupaten/Kota di Provinsi Riau
        $series = [
            'Pekanbaru Series',
            'Dumai Series',
            'Siak Series',
            'Kampar Series',
            'Indragiri Hulu Series',
            'Indragiri Hilir Series',
            'Pelalawan Series',
            'Rokan Hulu Series',
            'Rokan Hilir Series',
            'Bengkalis Series',
            'Meranti Islands Series',
            'Kuantan Singingi Series',
        ];
        
        $phases = AddData::select('phase')
            ->distinct()
            ->whereNotNull('phase')
            ->orderBy('phase')
            ->get();
        
        // PERUBAHAN DI SINI: Ganti 'schools' dengan 'teams'
        return view('publication.pub_result-create', compact(
            'teams', // Ganti 'schools' dengan 'teams'
            'seasons',
            'competitions',
            'competitionTypes',
            'series',
            'phases'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ganti validasi exists dari schools ke team_lists
        $request->validate([
            'match_date' => 'required|date',
            'season' => 'required|string|max:100',
            'team1_id' => 'required|exists:team_lists,id', // Ganti schools menjadi team_lists
            'team2_id' => 'required|exists:team_lists,id|different:team1_id', // Ganti schools menjadi team_lists
            'score_1' => 'required|integer|min:0',
            'score_2' => 'required|integer|min:0',
            'competition' => 'required|string|max:100',
            'competition_type' => 'required|string|max:100',
            'series' => 'required|string|max:100',
            'phase' => 'required|string|max:100',
            'scoresheet' => 'nullable|file|mimes:xlsx,xls,xlsm,xlsb,csv|max:10240', // 10MB
        ]);
        
        try {
            DB::beginTransaction();
            
            $result = new MatchResult();
            $result->match_date = $request->match_date;
            $result->season = $request->season;
            $result->team1_id = $request->team1_id;
            $result->team2_id = $request->team2_id;
            $result->score_1 = $request->score_1;
            $result->score_2 = $request->score_2;
            $result->competition = $request->competition;
            $result->competition_type = $request->competition_type;
            $result->series = $request->series;
            $result->phase = $request->phase;
            
            // Set status dari action_type (default draft)
            $result->status = $request->get('action_type', 'draft');
            
            // Handle scoresheet upload (Excel)
            if ($request->hasFile('scoresheet')) {
                $file = $request->file('scoresheet');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '', $originalName);
                
                // Buat folder jika belum ada
                $directory = public_path('uploads/scoresheets');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Pindahkan file ke public/uploads/scoresheets
                $file->move($directory, $fileName);
                
                // Simpan path relatif untuk database
                $result->scoresheet = 'uploads/scoresheets/' . $fileName;
                
                // Simpan juga nama file asli
                $result->scoresheet_original_name = $originalName;
            }
            
            $result->save();
            
            DB::commit();
            
            return redirect()->route('admin.pub_result.index')
                ->with('success', 'Match result created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating match result: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $result = MatchResult::findOrFail($id);
        
        // Ganti School dengan TeamList
        $teams = TeamList::orderBy('school_name')->get();
        
        // Get data dari tabel AddData - TANPA ALIAS
        $seasons = AddData::select('season_name')
            ->distinct()
            ->whereNotNull('season_name')
            ->orderBy('season_name', 'desc')
            ->get();
        
        $competitions = AddData::select('competition')
            ->distinct()
            ->whereNotNull('competition')
            ->orderBy('competition')
            ->get();
        
        $competitionTypes = AddData::select('competition_type')
            ->distinct()
            ->whereNotNull('competition_type')
            ->orderBy('competition_type')
            ->get();
        
        // Series manual - Kabupaten/Kota di Provinsi Riau
        $series = [
            'Pekanbaru Series',
            'Dumai Series',
            'Siak Series',
            'Kampar Series',
            'Indragiri Hulu Series',
            'Indragiri Hilir Series',
            'Pelalawan Series',
            'Rokan Hulu Series',
            'Rokan Hilir Series',
            'Bengkalis Series',
            'Meranti Islands Series',
            'Kuantan Singingi Series',
        ];
        
        $phases = AddData::select('phase')
            ->distinct()
            ->whereNotNull('phase')
            ->orderBy('phase')
            ->get();
        
        // PERUBAHAN DI SINI: Ganti 'schools' dengan 'teams'
        return view('publication.pub_result-edit', compact(
            'result',
            'teams', // Ganti 'schools' dengan 'teams'
            'seasons',
            'competitions',
            'competitionTypes',
            'series',
            'phases'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Ganti validasi exists dari schools ke team_lists
        $request->validate([
            'match_date' => 'required|date',
            'season' => 'required|string|max:100',
            'team1_id' => 'required|exists:team_lists,id', // Ganti schools menjadi team_lists
            'team2_id' => 'required|exists:team_lists,id|different:team1_id', // Ganti schools menjadi team_lists
            'score_1' => 'required|integer|min:0',
            'score_2' => 'required|integer|min:0',
            'competition' => 'required|string|max:100',
            'competition_type' => 'required|string|max:100',
            'series' => 'required|string|max:100',
            'phase' => 'required|string|max:100',
            'scoresheet' => 'nullable|file|mimes:xlsx,xls,xlsm,xlsb,csv|max:10240', // 10MB
        ]);
        
        try {
            DB::beginTransaction();
            
            $result = MatchResult::findOrFail($id);
            
            // Cegah edit jika status done
            if ($result->status === 'done') {
                return redirect()->back()
                    ->with('error', 'Cannot edit result marked as done.');
            }
            
            // Handle scoresheet upload (Excel)
            if ($request->hasFile('scoresheet')) {
                // Delete old scoresheet if exists
                if ($result->scoresheet && file_exists(public_path($result->scoresheet))) {
                    unlink(public_path($result->scoresheet));
                }
                
                $file = $request->file('scoresheet');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '', $originalName);
                
                // Buat folder jika belum ada
                $directory = public_path('uploads/scoresheets');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Pindahkan file ke public/uploads/scoresheets
                $file->move($directory, $fileName);
                
                // Simpan path relatif
                $result->scoresheet = 'uploads/scoresheets/' . $fileName;
                
                // Simpan juga nama file asli
                $result->scoresheet_original_name = $originalName;
            }
            
            $result->match_date = $request->match_date;
            $result->season = $request->season;
            $result->team1_id = $request->team1_id;
            $result->team2_id = $request->team2_id;
            $result->score_1 = $request->score_1;
            $result->score_2 = $request->score_2;
            $result->competition = $request->competition;
            $result->competition_type = $request->competition_type;
            $result->series = $request->series;
            $result->phase = $request->phase;
            $result->save();
            
            DB::commit();
            
            return redirect()->route('admin.pub_result.index')
                ->with('success', 'Match result updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating match result: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $result = MatchResult::findOrFail($id);
            
            // Delete scoresheet file if exists
            if ($result->scoresheet && file_exists(public_path($result->scoresheet))) {
                unlink(public_path($result->scoresheet));
            }
            
            $result->delete();
            
            DB::commit();
            
            return redirect()->route('admin.pub_result.index')
                ->with('success', 'Match result deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting match result: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete results
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:match_results,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            $results = MatchResult::whereIn('id', $request->selected)->get();
            
            foreach ($results as $result) {
                // Delete scoresheet file if exists
                if ($result->scoresheet && file_exists(public_path($result->scoresheet))) {
                    unlink(public_path($result->scoresheet));
                }
                $result->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.pub_result.index')
                ->with('success', count($request->selected) . ' result(s) deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting results: ' . $e->getMessage());
        }
    }

    /**
     * Publish a result
     */
    public function publish(string $id)
    {
        try {
            $result = MatchResult::findOrFail($id);
            $result->status = 'publish';
            $result->save();
            
            return redirect()->back()
                ->with('success', 'Result published successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error publishing result: ' . $e->getMessage());
        }
    }

    /**
     * Unpublish a result
     */
    public function unpublish(string $id)
    {
        try {
            $result = MatchResult::findOrFail($id);
            $result->status = 'draft';
            $result->save();
            
            return redirect()->back()
                ->with('success', 'Result unpublished successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error unpublishing result: ' . $e->getMessage());
        }
    }

    /**
     * Mark result as done
     */
    public function done(string $id)
    {
        try {
            $result = MatchResult::findOrFail($id);
            $result->status = 'done';
            $result->save();
            
            return redirect()->back()
                ->with('success', 'Result marked as done.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error marking result as done: ' . $e->getMessage());
        }
    }

    /**
     * Bulk publish results
     */
    public function bulkPublish(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:match_results,id',
        ]);
        
        try {
            MatchResult::whereIn('id', $request->selected)
                    ->update(['status' => 'publish']);
            
            return redirect()->route('admin.pub_result.index')
                ->with('success', count($request->selected) . ' result(s) published successfully.');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error publishing results: ' . $e->getMessage());
        }
    }

    /**
     * Download scoresheet
     */
    public function downloadScoresheet($id)
    {
        $result = MatchResult::findOrFail($id);
        
        if (!$result->scoresheet || !file_exists(public_path($result->scoresheet))) {
            return redirect()->back()->with('error', 'Scoresheet file not found.');
        }
        
        $fileName = $result->scoresheet_original_name ?? basename($result->scoresheet);
        
        return response()->download(public_path($result->scoresheet), $fileName);
    }

    /**
     * Get series options
     */
    public function getSeriesOptions()
    {
        $series = [
            'Pekanbaru Series',
            'Dumai Series',
            'Siak Series',
            'Kampar Series',
            'Indragiri Hulu Series',
            'Indragiri Hilir Series',
            'Pelalawan Series',
            'Rokan Hulu Series',
            'Rokan Hilir Series',
            'Bengkalis Series',
            'Meranti Islands Series',
            'Kuantan Singingi Series',
        ];
        
        return $series;
    }
}