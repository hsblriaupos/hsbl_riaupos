<?php

namespace App\Http\Controllers\TeamVerification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\TeamList;
use App\Models\School;

class TeamController extends Controller
{
    public function teamList()
    {
        $teamList = TeamList::all();
        return view('team_verification.tv_team_list', compact('teamList'));
    }

    public function teamShow($id)
    {
        // Cari tim berdasarkan team_id
        $team = TeamList::where('team_id', $id)->first();
        
        // Jika tidak ditemukan, buat data dummy untuk testing
        if (!$team) {
            $team = (object) [
                'team_id' => $id,
                'school_name' => 'SMAN 1 KAMPAR',
                'referral_code' => 'REF-' . $id,
                'season' => 'Honda DBL 2019',
                'series' => 'Seri Riau',
                'competition' => 'Honda DBL Riau Series 2019 - Bola Basket Putra',
                'team_category' => 'Basket Putra',
                'registered_by' => 'Muhammad Alfah Reza',
                'locked_status' => 'unlocked',
                'verification_status' => 'unverified',
                'created_at' => now(),
                'updated_at' => now(),
                'recommendation_letter' => null,
                'payment_proof' => null,
                'payment_status' => null,
                'koran' => null,
            ];
        }
        
        return view('team_verification.tv_team_detail', compact('team'));
    }

    public function teamVerification()
    {
        $unverifiedTeams = TeamList::where('verification_status', 'unverified')->get();
        return view('team_verification.tv_team_verification', compact('unverifiedTeams'));
    }

    public function teamAwards()
    {
        return view('team_verification.tv_team_awards');
    }

    // Method untuk lock team
    public function lock($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->locked_status = 'locked';
        $team->save();

        return back()->with('success', 'Tim berhasil dikunci!');
    }

    // Method untuk unlock team
    public function unlock($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->locked_status = 'unlocked';
        $team->save();

        return back()->with('success', 'Tim berhasil dibuka!');
    }

    // Method untuk verify team
    public function verify($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->verification_status = 'verified';
        $team->save();

        return back()->with('success', 'Tim berhasil diverifikasi!');
    }

    // Method untuk reject team
    public function reject($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->verification_status = 'rejected';
        $team->save();

        return back()->with('success', 'Tim berhasil ditolak!');
    }

    public function create()
    {
        $schools = School::all();
        return view('team_verification.tv_team_create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required',
            'referral_code' => 'required|unique:team_lists',
            'season' => 'required',
            'series' => 'required',
            'competition' => 'required',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer',
            'registered_by' => 'required',
        ]);

        TeamList::create($validated);

        return redirect()->route('admin.tv_team_list')->with('success', 'Team berhasil ditambahkan!');
    }
}