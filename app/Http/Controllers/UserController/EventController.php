<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\AddData;
use App\Models\TeamList;
use App\Models\PlayerList;
use App\Models\OfficialList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function showTeamRegisterForm()
    {
        $schools = School::all();

        $competitions = DB::table('add_data')
            ->whereNotNull('competition')
            ->pluck('competition');

        $seasons = DB::table('add_data')
            ->whereNotNull('season_name')
            ->pluck('season_name');

        $series = DB::table('add_data')
            ->whereNotNull('series_name')
            ->pluck('series_name');

        $teamCategories = DB::select("SHOW COLUMNS FROM team_list WHERE Field = 'team_category'");
        preg_match("/^enum\((.*)\)$/", $teamCategories[0]->Type, $matches);
        $teamCategoryEnums = collect(explode(',', str_replace("'", "", $matches[1])));

        return view('user.team_register', compact(
            'schools',
            'competitions',
            'seasons',
            'series',
            'teamCategoryEnums'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'competition' => 'required',
            'season' => 'required',
            'series' => 'required',
            'team_category' => 'required',
            'recommendation_letter' => 'required|file|mimes:pdf|max:2048',
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $school = School::findOrFail($validated['school_id']);
        $schoolNameSlug = Str::slug($school->school_name); // Membuat slug dari nama sekolah

        // Menyimpan dokumen dengan nama yang diubah
        if ($request->hasFile('recommendation_letter')) {
            $recommendationPath = $request->file('recommendation_letter')->storeAs('public/team_docs', $schoolNameSlug . '_recommendation_letter.' . $request->file('recommendation_letter')->getClientOriginalExtension());
            Log::info('Recommendation letter stored at: ' . $recommendationPath);
        }
        if ($request->hasFile('payment_proof')) {
            $paymentPath = $request->file('payment_proof')->storeAs('public/team_docs', $schoolNameSlug . '_payment_proof.' . $request->file('payment_proof')->getClientOriginalExtension());
            Log::info('Payment proof stored at: ' . $paymentPath);
        }
        if ($request->hasFile('koran')) {
            $koranPath = $request->file('koran')->storeAs('public/team_docs', $schoolNameSlug . '_koran.' . $request->file('koran')->getClientOriginalExtension());
            Log::info('Koran stored at: ' . $koranPath);
        }
        $referralCode = strtoupper(Str::slug($school->school_name)) . '-' . strtoupper(Str::random(4));

        TeamList::create([
            'school_name' => $school->school_name,
            'referral_code' => $referralCode,
            'competition' => $validated['competition'],
            'season' => $validated['season'],
            'series' => $validated['series'],
            'team_category' => $validated['team_category'],
            'recommendation_letter' => str_replace('public/', '', $recommendationPath),
            'payment_proof' => str_replace('public/', '', $paymentPath),
            'koran' => str_replace('public/', '', $koranPath),
            'payment_status' => 'Pending',
            'locked_status' => 'Unlocked',
            'verification_status' => 'Unverified',
            'registered_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('user.team.register')->with('success', 'Tim berhasil didaftarkan!');
    }



    public function showPlayerForm($team_id)
    {
        $team = TeamList::findOrFail($team_id);
        return view('user.player_form', compact('team'));
    }

    public function storePlayer(Request $request)
    {

        $validated = $request->validate([
            'team_id' => 'required|exists:team_list,team_id',
            'nik' => 'required',
            'name' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'school' => 'required',
            'grade' => 'required',
            'sttb_year' => 'required',
            'height' => 'required',
            'weight' => 'required',
            'tshirt_size' => 'required',
            'shoes_size' => 'required',
            'basketball_position' => 'nullable',
            'jersey_number' => 'nullable',
            'instagram' => 'nullable',
            'tiktok' => 'nullable',
            'birth_certificate' => 'required|file',
            'kk' => 'required|file',
            'shun' => 'required|file',
            'report_identity' => 'required|file',
            'last_report_card' => 'required|file',
            'formal_photo' => 'required|file',
            'assignment_letter' => 'nullable|file'
        ]);

        $existingLeader = PlayerList::where('team_id', $request->team_id)
            ->where('team_role', 'Leader')
            ->first();

        $teamRole = $existingLeader ? 'Player' : 'Leader';

        try {
            PlayerList::create(array_merge(
                $validated,
                [
                    'team_role' => $teamRole,
                    'birth_certificate' => $request->file('birth_certificate')->store('public/player_docs'),
                    'kk' => $request->file('kk')->store('public/player_docs'),
                    'shun' => $request->file('shun')->store('public/player_docs'),
                    'report_identity' => $request->file('report_identity')->store('public/player_docs'),
                    'last_report_card' => $request->file('last_report_card')->store('public/player_docs'),
                    'formal_photo' => $request->file('formal_photo')->store('public/player_docs'),
                    'assignment_letter' => $request->file('assignment_letter')?->store('public/player_docs'),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }


        return back()->with('success', 'Data pemain berhasil disimpan.');
    }
}
