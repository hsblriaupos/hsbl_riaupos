<?php

namespace App\Http\Controllers\Publication;

use App\Http\Controllers\Controller;
use App\Models\MatchResult;
use App\Models\TeamList;
use App\Models\AddData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        // Ambil data dari tabel team_list - gunakan team_id sebagai value
        $teams = TeamList::select('team_id', 'school_name')
                        ->orderBy('school_name')
                        ->get();
        
        // Get data dari tabel AddData
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
        
        return view('publication.pub_result-create', compact(
            'teams',
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
        // DEBUG: Log semua input sebelum validasi
        Log::info('===== STORE METHOD STARTED =====');
        Log::info('All request data:', $request->all());
        Log::info('Team 1 ID:', ['value' => $request->team1_id, 'type' => gettype($request->team1_id)]);
        Log::info('Team 2 ID:', ['value' => $request->team2_id, 'type' => gettype($request->team2_id)]);
        Log::info('Action Type:', ['value' => $request->action_type]);
        
        // Validasi
        try {
            $validated = $request->validate([
                'match_date' => 'required|date',
                'season' => 'required|string|max:100',
                'team1_id' => 'required|integer|exists:team_list,team_id',
                'team2_id' => 'required|integer|exists:team_list,team_id|different:team1_id',
                'score_1' => 'required|integer|min:0',
                'score_2' => 'required|integer|min:0',
                'competition' => 'required|string|max:100',
                'competition_type' => 'required|string|max:100',
                'series' => 'required|string|max:100',
                'phase' => 'required|string|max:100',
                'scoresheet' => 'nullable|file|mimes:xlsx,xls,xlsm,xlsb,csv|max:10240',
                'action_type' => 'nullable|string|in:draft,publish',
            ]);
            
            Log::info('Validation passed:', $validated);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');
        }
        
        try {
            DB::beginTransaction();
            
            $result = new MatchResult();
            $result->match_date = $request->match_date;
            $result->season = $request->season;
            
            // Simpan ID tim
            $result->team1_id = (int)$request->team1_id;
            $result->team2_id = (int)$request->team2_id;
            
            Log::info('Saving team IDs:', [
                'team1_id' => $result->team1_id,
                'team2_id' => $result->team2_id
            ]);
            
            $result->score_1 = $request->score_1;
            $result->score_2 = $request->score_2;
            $result->competition = $request->competition;
            $result->competition_type = $request->competition_type;
            $result->series = $request->series;
            $result->phase = $request->phase;
            
            // Set status dari action_type dengan default 'draft'
            $result->status = $request->input('action_type', 'draft');
            
            // Handle scoresheet upload (Excel)
            if ($request->hasFile('scoresheet')) {
                Log::info('Processing scoresheet upload');
                
                $file = $request->file('scoresheet');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '', $originalName);
                
                // Buat folder jika belum ada
                $directory = public_path('uploads/scoresheets');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $file->move($directory, $fileName);
                
                $result->scoresheet = 'uploads/scoresheets/' . $fileName;
                $result->scoresheet_original_name = $originalName;
            }
            
            $result->save();
            
            DB::commit();
            
            $message = $result->status === 'publish' 
                ? 'Match result created and published successfully.' 
                : 'Match result saved as draft successfully.';
            
            return redirect()->route('admin.pub_result.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error creating match result:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
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
        $result = MatchResult::with(['team1', 'team2'])->findOrFail($id);
        
        // Ambil data dari tabel team_list
        $teams = TeamList::select('team_id', 'school_name')
                        ->orderBy('school_name')
                        ->get();
        
        // Get data dari tabel AddData
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
        
        // Series manual
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
        
        return view('publication.pub_result-edit', compact(
            'result',
            'teams',
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
        try {
            $validated = $request->validate([
                'match_date' => 'required|date',
                'season' => 'required|string|max:100',
                'team1_id' => 'required|integer|exists:team_list,team_id',
                'team2_id' => 'required|integer|exists:team_list,team_id|different:team1_id',
                'score_1' => 'required|integer|min:0',
                'score_2' => 'required|integer|min:0',
                'competition' => 'required|string|max:100',
                'competition_type' => 'required|string|max:100',
                'series' => 'required|string|max:100',
                'phase' => 'required|string|max:100',
                'scoresheet' => 'nullable|file|mimes:xlsx,xls,xlsm,xlsb,csv|max:10240',
                'action_type' => 'nullable|string|in:draft,publish',
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');
        }
        
        try {
            DB::beginTransaction();
            
            $result = MatchResult::findOrFail($id);
            
            if ($result->status === 'done') {
                return redirect()->back()
                    ->with('error', 'Cannot edit result marked as done.');
            }
            
            // Handle scoresheet upload
            if ($request->hasFile('scoresheet')) {
                // Delete old scoresheet if exists
                if ($result->scoresheet && file_exists(public_path($result->scoresheet))) {
                    unlink(public_path($result->scoresheet));
                }
                
                $file = $request->file('scoresheet');
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\.]/', '', $originalName);
                
                $directory = public_path('uploads/scoresheets');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                $file->move($directory, $fileName);
                
                $result->scoresheet = 'uploads/scoresheets/' . $fileName;
                $result->scoresheet_original_name = $originalName;
            }
            
            $result->match_date = $request->match_date;
            $result->season = $request->season;
            $result->team1_id = (int)$request->team1_id;
            $result->team2_id = (int)$request->team2_id;
            $result->score_1 = $request->score_1;
            $result->score_2 = $request->score_2;
            $result->competition = $request->competition;
            $result->competition_type = $request->competition_type;
            $result->series = $request->series;
            $result->phase = $request->phase;
            
            // Update status jika ada action_type dan bukan done
            if ($result->status !== 'done' && $request->has('action_type')) {
                $result->status = $request->action_type;
            }
            
            $result->save();
            
            DB::commit();
            
            $message = $result->status === 'publish' 
                ? 'Match result updated and published successfully.' 
                : 'Match result updated as draft successfully.';
            
            return redirect()->route('admin.pub_result.index')
                ->with('success', $message);
                
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
     * View result details (for API/Modal)
     */
    public function show($id)
    {
        try {
            $result = MatchResult::with(['team1', 'team2'])->findOrFail($id);
            
            // Gunakan method helper baru yang lebih baik
            $team1Data = $this->getTeamDisplayDataForModal($result->team1);
            $team2Data = $this->getTeamDisplayDataForModal($result->team2);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $result->id,
                    'match_date' => $result->match_date,
                    'match_date_formatted' => \Carbon\Carbon::parse($result->match_date)->format('d M Y'),
                    'season' => $result->season,
                    'team1' => $team1Data,
                    'team2' => $team2Data,
                    'score_1' => $result->score_1,
                    'score_2' => $result->score_2,
                    'score_formatted' => $result->score_1 . ' - ' . $result->score_2,
                    'competition' => $result->competition,
                    'competition_type' => $result->competition_type,
                    'series' => $result->series,
                    'phase' => $result->phase,
                    'status' => $result->status,
                    'status_badge_class' => $this->getStatusBadgeClass($result->status),
                    'scoresheet' => $result->scoresheet,
                    'scoresheet_original_name' => $result->scoresheet_original_name,
                    'created_at' => $result->created_at,
                    'updated_at' => $result->updated_at,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in show method:', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching result details'
            ], 500);
        }
    }
    
    /**
     * Helper method to get team data safely dengan path logo yang benar
     */
    private function getTeamData($team)
    {
        if (!$team) {
            return [
                'id' => null,
                'name' => 'Team Not Found',
                'logo' => null,
                'logo_url' => null,
                'logo_icon' => '<i class="fas fa-school text-secondary fa-2x"></i>',
                'has_logo' => false
            ];
        }
        
        $schoolLogo = $team->school_logo ?? null;
        $hasLogo = !empty($schoolLogo);
        $logoUrl = null;
        
        if ($hasLogo) {
            $logoUrl = $this->getLogoUrl($schoolLogo);
        }
        
        return [
            'id' => $team->team_id ?? $team->id ?? null,
            'name' => $team->school_name ?? 'N/A',
            'logo' => $schoolLogo,
            'logo_url' => $logoUrl,
            'logo_icon' => $hasLogo ? null : '<i class="fas fa-school text-secondary fa-2x"></i>',
            'has_logo' => $hasLogo
        ];
    }

    /**
     * Get team display data for modal (HTML ready)
     */
    private function getTeamDisplayDataForModal($team)
    {
        if (!$team) {
            return [
                'name' => 'Team Not Found',
                'logo_html_sm' => '<div class="school-logo-placeholder school-logo-sm">
                    <i class="fas fa-school text-secondary"></i>
                </div>',
                'logo_html_md' => '<div class="school-logo-placeholder school-logo-md">
                    <i class="fas fa-school text-secondary"></i>
                </div>',
                'logo_url' => null,
                'has_logo' => false
            ];
        }
        
        $schoolLogo = $team->school_logo ?? null;
        $hasLogo = !empty($schoolLogo);
        $logoUrl = $hasLogo ? $this->getLogoUrl($schoolLogo) : null;
        $schoolName = htmlspecialchars($team->school_name ?? 'N/A');
        $defaultLogoUrl = asset('assets/img/default-school.png');
        
        if ($hasLogo && $logoUrl) {
            $logoHtmlSm = '<img src="' . $logoUrl . '" 
                             alt="' . $schoolName . '" 
                             class="school-logo-sm rounded-circle border"
                             onerror="this.onerror=null; this.src=\'' . $defaultLogoUrl . '\'">';
            
            $logoHtmlMd = '<img src="' . $logoUrl . '" 
                             alt="' . $schoolName . '" 
                             class="school-logo-md rounded-circle border"
                             onerror="this.onerror=null; this.src=\'' . $defaultLogoUrl . '\'">';
        } else {
            $logoHtmlSm = '<div class="school-logo-placeholder school-logo-sm">
                <i class="fas fa-school text-secondary"></i>
            </div>';
            
            $logoHtmlMd = '<div class="school-logo-placeholder school-logo-md">
                <i class="fas fa-school text-secondary"></i>
            </div>';
        }
        
        return [
            'name' => $schoolName,
            'logo_html_sm' => $logoHtmlSm,
            'logo_html_md' => $logoHtmlMd,
            'logo_url' => $logoUrl,
            'has_logo' => $hasLogo
        ];
    }

    /**
     * Get correct logo URL based on stored path
     */
    private function getLogoUrl($logoPath)
    {
        if (!$logoPath) {
            return null;
        }
        
        // Jika path sudah full URL
        if (str_starts_with($logoPath, 'http://') || str_starts_with($logoPath, 'https://')) {
            return $logoPath;
        }
        
        // Jika path menggunakan storage Laravel (storage/school_logos/...)
        if (str_starts_with($logoPath, 'storage/')) {
            return asset($logoPath);
        }
        
        // Jika path relatif (school_logos/...)
        if (str_starts_with($logoPath, 'school_logos/')) {
            return asset('storage/' . $logoPath);
        }
        
        // Jika hanya nama file
        if (strpos($logoPath, '/') === false) {
            return asset('storage/school_logos/' . $logoPath);
        }
        
        // Fallback untuk path lama (uploads/school_logo/...)
        if (str_starts_with($logoPath, 'uploads/school_logo/')) {
            return asset($logoPath);
        }
        
        // Default return dengan storage path
        return asset('storage/school_logos/' . basename($logoPath));
    }

    /**
     * Get team data for display in views (untuk digunakan di blade) - PERBAIKAN
     */
    public static function getTeamDisplayData($team)
    {
        if (!$team) {
            return [
                'name' => 'Team Not Found',
                'display_html' => '<div class="d-flex align-items-center">
                    <div class="school-logo-placeholder school-logo-sm me-2">
                        <i class="fas fa-school text-secondary"></i>
                    </div>
                    <span class="text-truncate">Team Not Found</span>
                </div>',
                'logo_html' => '<div class="school-logo-placeholder school-logo-sm">
                    <i class="fas fa-school text-secondary"></i>
                </div>',
                'logo_url' => null,
                'has_logo' => false
            ];
        }
        
        $schoolLogo = $team->school_logo ?? null;
        $hasLogo = !empty($schoolLogo);
        $self = new self();
        $logoUrl = $hasLogo ? $self->getLogoUrl($schoolLogo) : null;
        $schoolName = htmlspecialchars($team->school_name ?? 'N/A');
        $defaultLogoUrl = asset('assets/img/default-school.png');
        
        if ($hasLogo && $logoUrl) {
            $logoHtml = '<img src="' . $logoUrl . '" 
                         alt="' . $schoolName . '" 
                         class="school-logo-sm rounded-circle border"
                         onerror="this.onerror=null; this.src=\'' . $defaultLogoUrl . '\'">';
            
            $displayHtml = '<div class="d-flex align-items-center">
                <div class="me-2">' . $logoHtml . '</div>
                <span class="text-truncate" style="max-width: 150px;">' . $schoolName . '</span>
            </div>';
        } else {
            $logoHtml = '<div class="school-logo-placeholder school-logo-sm">
                <i class="fas fa-school text-secondary"></i>
            </div>';
            
            $displayHtml = '<div class="d-flex align-items-center">
                <div class="school-logo-placeholder school-logo-sm me-2">
                    <i class="fas fa-school text-secondary"></i>
                </div>
                <span class="text-truncate" style="max-width: 150px;">' . $schoolName . '</span>
            </div>';
        }
        
        return [
            'name' => $schoolName,
            'display_html' => $displayHtml,
            'logo_html' => $logoHtml,
            'logo_url' => $logoUrl,
            'has_logo' => $hasLogo
        ];
    }

    /**
     * Get status badge class
     */
    private function getStatusBadgeClass($status)
    {
        switch ($status) {
            case 'draft':
                return 'bg-warning bg-opacity-20 text-warning border border-warning border-opacity-50';
            case 'publish':
                return 'bg-success bg-opacity-20 text-success border border-success border-opacity-50';
            case 'done':
                return 'bg-primary bg-opacity-20 text-primary border border-primary border-opacity-50';
            default:
                return 'bg-secondary bg-opacity-10 text-secondary';
        }
    }
}