<?php

namespace App\Http\Controllers\TeamVerification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeamList;
use App\Models\PlayerList;
use App\Exports\TeamsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function teamList(Request $request)
    {
        $query = TeamList::query();

        // Filter by school
        if ($request->filled('school')) {
            $query->where('school_name', $request->school);
        }

        // Filter by status - HANYA unverified/verified
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('team_category', $request->category);
        }

        // Filter by competition
        if ($request->filled('competition')) {
            $query->where('competition', 'like', '%' . $request->competition . '%');
        }

        // Filter by tahun (dari season)
        if ($request->filled('year')) {
            $query->where('season', 'like', '%' . $request->year . '%');
        }

        // Filter by locked status
        if ($request->filled('locked')) {
            $query->where('locked_status', $request->locked);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('school_name', 'like', '%' . $search . '%')
                    ->orWhere('team_name', 'like', '%' . $search . '%')
                    ->orWhere('referral_code', 'like', '%' . $search . '%')
                    ->orWhere('competition', 'like', '%' . $search . '%')
                    ->orWhere('series', 'like', '%' . $search . '%')
                    ->orWhere('registered_by', 'like', '%' . $search . '%');
            });
        }

        // Sort - DEFAULT: updated_at descending (data terbaru di atas)
        $sort = $request->get('sort', 'updated_at');
        $order = $request->get('order', 'desc');
        
        // Validasi kolom sort untuk menghindari SQL injection
        $allowedSortColumns = ['updated_at', 'created_at', 'school_name', 'team_category', 'verification_status', 'locked_status'];
        $sort = in_array($sort, $allowedSortColumns) ? $sort : 'updated_at';
        
        $query->orderBy($sort, $order);

        // Get available years for filter
        $years = TeamList::selectRaw('DISTINCT season')
            ->whereNotNull('season')
            ->orderBy('season', 'desc')
            ->pluck('season')
            ->unique()
            ->values();

        // Get unique values for filters
        $schools = TeamList::distinct('school_name')->orderBy('school_name')->pluck('school_name');
        $competitions = TeamList::distinct('competition')->whereNotNull('competition')->orderBy('competition')->pluck('competition');

        // Pagination - 50 per page untuk data lebih banyak dalam satu halaman
        $teamList = $query->paginate(50)->withQueryString();

        // Untuk debugging - bisa dihapus setelah fix
        \Log::info('Team List Query:', [
            'total' => $teamList->total(),
            'count' => $teamList->count(),
            'has_logo_count' => TeamList::whereNotNull('school_logo')->count()
        ]);

        return view('team_verification.tv_team_list', compact('teamList', 'schools', 'competitions', 'years'));
    }

    public function export(Request $request)
    {
        // Apply same filters as teamList
        $query = $this->applyFilters($request);

        // Get teams with filters applied
        $teams = $query->get();

        // Generate filename with timestamp
        $filename = 'teams_export_' . date('Y-m-d_H-i') . '.xlsx';

        // Export to Excel
        return Excel::download(new TeamsExport($teams), $filename);
    }

    private function applyFilters(Request $request)
    {
        $query = TeamList::query();

        if ($request->filled('school')) {
            $query->where('school_name', $request->school);
        }

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('team_category', $request->category);
        }

        if ($request->filled('competition')) {
            $query->where('competition', 'like', '%' . $request->competition . '%');
        }

        if ($request->filled('year')) {
            $query->where('season', 'like', '%' . $request->year . '%');
        }

        if ($request->filled('locked')) {
            $query->where('locked_status', $request->locked);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('school_name', 'like', '%' . $search . '%')
                    ->orWhere('team_name', 'like', '%' . $search . '%')
                    ->orWhere('referral_code', 'like', '%' . $search . '%')
                    ->orWhere('competition', 'like', '%' . $search . '%')
                    ->orWhere('registered_by', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('updated_at', 'desc');
    }

    public function teamShow($id)
    {
        // Gunakan find() untuk mencari berdasarkan primary key jika mungkin
        $team = TeamList::where('team_id', $id)->first();

        if (!$team) {
            abort(404, 'Team tidak ditemukan');
        }

        // PERBAIKAN: Tambahkan log untuk debug
        \Log::info('Team Detail:', [
            'team_id' => $team->team_id,
            'school_name' => $team->school_name,
            'school_logo' => $team->school_logo,
            'logo_exists' => $team->school_logo ? Storage::exists('public/uploads/school_logo/' . $team->school_logo) : false,
            'logo_path' => $team->school_logo ? public_path('uploads/school_logo/' . $team->school_logo) : null
        ]);

        // Ambil data pemain dengan team_id yang sama
        $players = PlayerList::where('team_id', $team->team_id)
            ->orderByRaw('CAST(jersey_number AS UNSIGNED) ASC') // Urutkan jersey sebagai angka
            ->orderBy('name', 'asc') // Kemudian urutkan nama
            ->get();

        return view('team_verification.tv_team_detail', compact('team', 'players'));
    }

    public function teamVerification()
    {
        $unverifiedTeams = TeamList::where('verification_status', 'unverified')
            ->orderBy('updated_at', 'desc')
            ->paginate(50);
        return view('team_verification.tv_team_verification', compact('unverifiedTeams'));
    }

    public function teamAwards()
    {
        return view('team_verification.tv_team_awards');
    }

    public function lock($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->locked_status = 'locked';
        $team->save();

        return back()->with('success', 'Tim berhasil dikunci!');
    }

    public function unlock($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->locked_status = 'unlocked';
        $team->save();

        return back()->with('success', 'Tim berhasil dibuka!');
    }

    public function verify($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->verification_status = 'verified';
        $team->save();

        return back()->with('success', 'Tim berhasil diverifikasi!');
    }

    public function unverify($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->verification_status = 'unverified';
        $team->save();

        return back()->with('success', 'Verifikasi tim berhasil dibatalkan!');
    }

    public function playerDetail($id)
    {
        $player = PlayerList::with('team')
            ->where('id', $id)
            ->first();

        if (!$player) {
            abort(404, 'Pemain tidak ditemukan');
        }

        // Ambil nama sekolah jika ada relasi school
        // Jika tidak ada, bisa ambil dari team->school_name
        $schoolName = null;
        if ($player->team) {
            $schoolName = $player->team->school_name;
        }

        return view('team_verification.tv_player_detail', compact('player', 'schoolName'));
    }

    // PERBAIKAN: Tambahkan method untuk cek logo
    public function checkLogoPath()
    {
        // Debug path logo
        $teams = TeamList::whereNotNull('school_logo')->limit(5)->get();
        
        $results = [];
        foreach ($teams as $team) {
            $path = public_path('uploads/school_logo/' . $team->school_logo);
            $results[] = [
                'team_id' => $team->team_id,
                'school_name' => $team->school_name,
                'school_logo' => $team->school_logo,
                'exists' => file_exists($path),
                'path' => $path
            ];
        }
        
        return response()->json($results);
    }
}