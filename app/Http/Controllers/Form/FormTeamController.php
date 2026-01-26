<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\TeamList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormTeamController extends Controller
{
    /**
     * Tampilkan form pertama - Pilih Join Team atau Create Team
     */
    public function showChoiceForm()
    {
        // Ambil data untuk dropdown competition, season, series
        $competitions = DB::table('add_data')
            ->whereNotNull('competition')
            ->distinct()
            ->pluck('competition');

        $seasons = DB::table('add_data')
            ->whereNotNull('season_name')
            ->distinct()
            ->pluck('season_name');

        $series = DB::table('add_data')
            ->whereNotNull('series_name')
            ->distinct()
            ->pluck('series_name');

        // Ambil enum team_category
        $teamCategories = DB::select("SHOW COLUMNS FROM team_list WHERE Field = 'team_category'");
        preg_match("/^enum\((.*)\)$/", $teamCategories[0]->Type, $matches);
        $teamCategoryEnums = collect(explode(',', str_replace("'", "", $matches[1])));

        return view('user.form.form_team', compact(
            'competitions',
            'seasons',
            'series',
            'teamCategoryEnums'
        ));
    }

    /**
     * Tampilkan form untuk CREATE TEAM (Leader)
     */
    public function showCreateForm(Request $request)
    {
        // Ambil data untuk dropdown
        $competitions = DB::table('add_data')
            ->whereNotNull('competition')
            ->distinct()
            ->pluck('competition');

        $seasons = DB::table('add_data')
            ->whereNotNull('season_name')
            ->distinct()
            ->pluck('season_name');

        $series = DB::table('add_data')
            ->whereNotNull('series_name')
            ->distinct()
            ->pluck('series_name');

        // Ambil enum team_category
        $teamCategories = DB::select("SHOW COLUMNS FROM team_list WHERE Field = 'team_category'");
        preg_match("/^enum\((.*)\)$/", $teamCategories[0]->Type, $matches);
        $teamCategoryEnums = collect(explode(',', str_replace("'", "", $matches[1])));

        // Ambil daftar sekolah untuk autocomplete
        $schools = School::all();

        return view('user.form.form_create_team', compact(
            'schools',
            'competitions',
            'seasons',
            'series',
            'teamCategoryEnums'
        ));
    }

    /**
     * Tampilkan form untuk JOIN TEAM (Masukkan referral code)
     */
    public function showJoinForm()
    {
        return view('user.form.form_join_team');
    }

    /**
     * Proses JOIN TEAM dengan referral code
     */
    public function joinTeam(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|exists:team_list,referral_code'
        ]);

        // Cari tim berdasarkan referral code
        $team = TeamList::where('referral_code', $request->referral_code)->first();

        // Cek apakah tim sudah locked
        if ($team->locked_status == 'locked') {
            return redirect()->back()->withErrors(['referral_code' => 'Tim ini sudah terkunci dan tidak menerima anggota baru.']);
        }

        // Simpan tim_id ke session untuk form selanjutnya
        session(['joining_team_id' => $team->team_id]);
        session(['joining_team_category' => $team->team_category]);

        // Redirect ke form registrasi anggota
        return redirect()->route('form.player.create', ['team_id' => $team->team_id]);
    }

    /**
     * Proses CREATE TEAM (Leader pertama)
     */
    public function createTeam(Request $request)
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'competition' => 'required',
            'season' => 'required',
            'series' => 'required',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer',
            'registered_by' => 'required|string|max:255',
            'recommendation_letter' => 'required|file|mimes:pdf|max:2048',
            'koran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Cek apakah sekolah sudah ada
        $school = School::where('school_name', $validated['school_name'])->first();

        // Jika sekolah belum ada, buat baru
        if (!$school) {
            $school = School::create([
                'school_name' => $validated['school_name'],
                'category_name' => 'SMA', // Default, bisa diubah sesuai kebutuhan
                'type' => 'SWASTA', // Default, bisa diubah sesuai kebutuhan
                'city_id' => 1, // Default city, sesuaikan dengan kebutuhan
            ]);
        }

        // Generate referral code unik
        $baseSlug = Str::slug($school->school_name);
        $referralCode = strtoupper($baseSlug) . '-' . strtoupper(Str::random(4));

        // Simpan file
        Storage::disk('public')->makeDirectory('team_docs');

        $recommendationPath = $request->file('recommendation_letter')
            ->storeAs(
                'team_docs',
                "{$baseSlug}_recommendation_letter_" . time() . "." . $request->file('recommendation_letter')->extension(),
                'public'
            );

        $koranPath = $request->file('koran')
            ->storeAs(
                'team_docs',
                "{$baseSlug}_koran_" . time() . "." . $request->file('koran')->extension(),
                'public'
            );

        // Buat tim baru (Leader pertama)
        $team = TeamList::create([
            'school_name' => $school->school_name,
            'referral_code' => $referralCode,
            'competition' => $validated['competition'],
            'season' => $validated['season'],
            'series' => $validated['series'],
            'team_category' => $validated['team_category'],
            'registered_by' => $validated['registered_by'],
            'locked_status' => 'unlocked',
            'verification_status' => 'unverified',
            'recommendation_letter' => $recommendationPath,
            'koran' => $koranPath,
        ]);

        // Simpan tim_id ke session
        session(['created_team_id' => $team->team_id]);
        session(['created_team_category' => $team->team_category]);

        // Redirect ke form pembayaran atau form anggota (Leader pertama)
        return redirect()->route('form.team.success', ['team_id' => $team->team_id])
            ->with('success', 'Tim berhasil dibuat! Sekarang kamu adalah Leader tim ini.')
            ->with('referral_code', $referralCode);
    }

    /**
     * Halaman sukses pembuatan tim
     */
    public function showSuccessPage($team_id)
    {
        $team = TeamList::findOrFail($team_id);
        
        return view('user.form.form_team_success', compact('team'));
    }

    /**
     * Cek ketersediaan sekolah untuk autocomplete
     */
    public function checkSchool(Request $request)
    {
        $query = $request->get('query');
        
        $schools = School::where('school_name', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'school_name']);

        return response()->json($schools);
    }

    /**
     * Verifikasi apakah tim sudah ada untuk kategori tertentu
     */
    public function checkExistingTeam(Request $request)
    {
        $request->validate([
            'school_name' => 'required',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer'
        ]);

        $existingTeam = TeamList::where('school_name', $request->school_name)
            ->where('team_category', $request->team_category)
            ->where('season', $request->season) // Tambahkan season jika perlu
            ->first();

        if ($existingTeam) {
            return response()->json([
                'exists' => true,
                'team' => $existingTeam,
                'message' => 'Tim dengan kategori ini sudah terdaftar! Apakah ingin bergabung dengan tim yang sudah ada?'
            ]);
        }

        return response()->json(['exists' => false]);
    }
}