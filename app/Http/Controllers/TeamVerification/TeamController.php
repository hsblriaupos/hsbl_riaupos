<?php

namespace App\Http\Controllers\TeamVerification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeamList;
use App\Models\PlayerList;
use App\Exports\TeamsExport;
use Maatwebsite\Excel\Facades\Excel;

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
                    ->orWhere('referral_code', 'like', '%' . $search . '%')
                    ->orWhere('competition', 'like', '%' . $search . '%')
                    ->orWhere('series', 'like', '%' . $search . '%')
                    ->orWhere('registered_by', 'like', '%' . $search . '%');
            });
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
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

        // Pagination - 25 per page
        $teamList = $query->paginate(25)->withQueryString();

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
                    ->orWhere('referral_code', 'like', '%' . $search . '%')
                    ->orWhere('competition', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function teamShow($id)
    {
        // Gunakan find() untuk mencari berdasarkan primary key jika mungkin
        $team = TeamList::where('team_id', $id)->first();

        if (!$team) {
            abort(404, 'Team tidak ditemukan');
        }

        // Ambil data pemain dengan team_id yang sama
        $players = PlayerList::where('team_id', $team->team_id)
            ->orderByRaw('CAST(jersey_number AS UNSIGNED) ASC') // Urutkan jersey sebagai angka
            ->orderBy('name', 'asc') // Kemudian urutkan nama
            ->get();

        return view('team_verification.tv_team_detail', compact('team', 'players'));
    }

    public function teamVerification()
    {
        $unverifiedTeams = TeamList::where('verification_status', 'unverified')->paginate(25);
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
}
