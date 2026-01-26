<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\TeamList;
use App\Models\PlayerList;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormPlayerController extends Controller
{
    /**
     * Tampilkan form untuk pendaftaran player (baik Leader maupun Member)
     */
    public function showPlayerForm(Request $request, $team_id = null)
    {
        // Jika team_id dikirim via parameter
        if ($team_id) {
            $request->session()->put('current_team_id', $team_id);
        } else {
            // Ambil dari session (untuk Leader setelah create team)
            $team_id = $request->session()->get('current_team_id');
            
            if (!$team_id) {
                // Cek session lain
                $team_id = $request->session()->get('created_team_id') ?? 
                          $request->session()->get('joining_team_id');
                
                if (!$team_id) {
                    return redirect()->route('form.team.choice')
                        ->with('error', 'Silakan daftarkan atau bergabung dengan tim terlebih dahulu.');
                }
            }
        }

        // Ambil data tim
        $team = TeamList::findOrFail($team_id);
        
        // Tentukan kategori dari tim
        $teamCategory = $team->team_category;
        
        // Normalisasi kategori untuk player
        $category = strtolower($teamCategory);
        if (str_contains($category, 'putra')) {
            $category = 'putra';
        } elseif (str_contains($category, 'putri')) {
            $category = 'putri';
        } elseif ($teamCategory == 'Dancer') {
            $category = 'dancer';
        }

        // Cek apakah sudah ada Leader di kategori ini
        $isLeaderExist = PlayerList::where('team_id', $team_id)
            ->where('team_role', 'Leader')
            ->where('category', $category)
            ->exists();

        // Tentukan role: jika belum ada Leader, maka ini adalah Leader
        $role = $isLeaderExist ? 'Player' : 'Leader';

        // Ambil enum gender dari database
        $col = DB::selectOne("SHOW COLUMNS FROM player_list WHERE Field = 'gender'");
        preg_match("/^enum\((.*)\)$/", $col->Type, $matches);
        $genderOptions = collect(explode(',', str_replace("'", '', $matches[1])));
        
        // Fallback jika tidak ada enum
        if ($genderOptions->isEmpty()) {
            $genderOptions = collect(['Male', 'Female']);
        }

        // Ambil daftar ukuran kaos dan sepatu (bisa dari database atau static)
        $tshirtSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $shoesSizes = range(36, 46); // 36-46
        
        // Posisi basket (hanya untuk basket, bukan dancer)
        $basketballPositions = [
            'Point Guard (PG)',
            'Shooting Guard (SG)', 
            'Small Forward (SF)',
            'Power Forward (PF)',
            'Center (C)'
        ];
        
        // Grade/kelas
        $grades = ['X', 'XI', 'XII'];

        return view('user.form.form_player', compact(
            'team',
            'category',
            'role',
            'isLeaderExist',
            'genderOptions',
            'tshirtSizes',
            'shoesSizes',
            'basketballPositions',
            'grades'
        ));
    }

    /**
     * Proses pendaftaran player - FIXED untuk school_id
     */
    public function storePlayer(Request $request)
    {
        // Ambil team_id dari input atau session
        $teamId = $request->input('team_id') ?? 
                 session('current_team_id') ??
                 session('created_team_id') ??
                 session('joining_team_id');

        if (!$teamId) {
            return redirect()->route('form.team.choice')
                ->with('error', 'Tim tidak ditemukan. Silakan daftar ulang.');
        }

        // Ambil data tim
        $team = TeamList::findOrFail($teamId);
        $teamCategory = strtolower($team->team_category);
        
        // Normalisasi kategori
        $category = 'putra'; // default
        if (str_contains($teamCategory, 'putra')) {
            $category = 'putra';
        } elseif (str_contains($teamCategory, 'putri')) {
            $category = 'putri';
        } elseif ($teamCategory == 'dancer') {
            $category = 'dancer';
        }

        // Cek apakah sudah ada Leader
        $isLeaderExist = PlayerList::where('team_id', $teamId)
            ->where('team_role', 'Leader')
            ->where('category', $category)
            ->exists();

        // Tentukan role
        $teamRole = $isLeaderExist ? 'Player' : 'Leader';

        // ============================================
        // 🔥 FIX: Cari atau buat school di tabel schools
        // ============================================
        $school = School::where('school_name', $team->school_name)->first();
        
        // Jika sekolah belum ada di tabel schools, buat baru
        if (!$school) {
            $school = School::create([
                'school_name' => $team->school_name,
                'category_name' => 'SMA', // Default, bisa disesuaikan
                'type' => 'SWASTA', // Default, bisa disesuaikan
                'city_id' => 1, // Default city, sesuaikan dengan kebutuhan
            ]);
        }

        // Validasi rules
        $rules = [
            'nik' => 'required|unique:player_list,nik',
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'email' => 'required|email|unique:player_list,email',
            'phone' => 'required|string|max:20',
            'grade' => 'required|string|max:10',
            'sttb_year' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
            'height' => 'required|numeric|min:100|max:250',
            'weight' => 'required|numeric|min:30|max:150',
            'tshirt_size' => 'required|string|max:10',
            'shoes_size' => 'required|string|max:10',
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            
            // File uploads
            'birth_certificate' => 'required|file|mimes:pdf|max:1024',
            'kk' => 'required|file|mimes:pdf|max:1024',
            'shun' => 'required|file|mimes:pdf|max:1024',
            'report_identity' => 'required|file|mimes:pdf|max:1024',
            'last_report_card' => 'required|file|mimes:pdf|max:1024',
            'formal_photo' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'assignment_letter' => 'nullable|file|mimes:pdf|max:1024',
        ];

        // Tambahkan field khusus basket untuk non-dancer
        if ($category !== 'dancer') {
            $rules['basketball_position'] = 'nullable|string|max:50';
            $rules['jersey_number'] = 'nullable|numeric|min:0|max:99';
        }

        // Jika ini adalah Leader dan belum ada pembayaran sebelumnya
        if ($teamRole === 'Leader') {
            $rules['payment_proof'] = 'required|file|mimes:jpg,jpeg,png|max:2048';
        }

        $validated = $request->validate($rules);

        // Generate nama file
        $schoolSlug = Str::slug($team->school_name);
        $playerSlug = Str::slug($validated['name']);
        $timestamp = time();
        
        // Fungsi helper untuk menyimpan file
        $saveFile = function($field, $folder) use ($request, $schoolSlug, $playerSlug, $timestamp) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();
                $filename = "{$schoolSlug}_{$playerSlug}_{$field}_{$timestamp}.{$extension}";
                
                return $file->storeAs($folder, $filename, 'public');
            }
            return null;
        };

        // Simpan semua dokumen
        Storage::disk('public')->makeDirectory('player_docs');

        // ============================================
        // 🔥 FIXED: Data untuk disimpan - pakai $school->id
        // ============================================
        $data = [
            'team_id' => $teamId,
            'nik' => $validated['nik'],
            'name' => $validated['name'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            
            // 🔥 INI YANG DIPERBAIKI: Simpan school_id (bukan nama)
            'school' => $school->id, // Simpan ID sekolah, bukan string
            
            'grade' => $validated['grade'],
            'sttb_year' => $validated['sttb_year'],
            'height' => $validated['height'],
            'weight' => $validated['weight'],
            'tshirt_size' => $validated['tshirt_size'],
            'shoes_size' => $validated['shoes_size'],
            'instagram' => $validated['instagram'] ?? null,
            'tiktok' => $validated['tiktok'] ?? null,
            'father_name' => $validated['father_name'] ?? null,
            'father_phone' => $validated['father_phone'] ?? null,
            'mother_name' => $validated['mother_name'] ?? null,
            'mother_phone' => $validated['mother_phone'] ?? null,
            'team_role' => $teamRole,
            'category' => $category,
            
            // File paths
            'birth_certificate' => $saveFile('birth_certificate', 'player_docs'),
            'kk' => $saveFile('kk', 'player_docs'),
            'shun' => $saveFile('shun', 'player_docs'),
            'report_identity' => $saveFile('report_identity', 'player_docs'),
            'last_report_card' => $saveFile('last_report_card', 'player_docs'),
            'formal_photo' => $saveFile('formal_photo', 'player_docs'),
            'assignment_letter' => $saveFile('assignment_letter', 'player_docs'),
        ];

        // Tambahkan field basket hanya untuk non-dancer
        if ($category !== 'dancer') {
            $data['basketball_position'] = $validated['basketball_position'] ?? null;
            $data['jersey_number'] = $validated['jersey_number'] ?? null;
        }

        // Simpan data player
        $player = PlayerList::create($data);

        // Jika ini Leader, simpan pembayaran
        if ($teamRole === 'Leader' && $request->hasFile('payment_proof')) {
            Storage::disk('public')->makeDirectory('team_payments');
            
            $paymentProof = $request->file('payment_proof');
            $paymentFilename = "{$schoolSlug}_{$category}_payment_" . time() . "." . $paymentProof->getClientOriginalExtension();
            $paymentPath = $paymentProof->storeAs('team_payments', $paymentFilename, 'public');
            
            // Simpan ke tabel team_payments (buat tabel jika belum ada)
            if (DB::getSchemaBuilder()->hasTable('team_payments')) {
                DB::table('team_payments')->insert([
                    'team_id' => $teamId,
                    'player_id' => $player->id,
                    'category' => $category,
                    'payment_proof' => $paymentPath,
                    'payment_status' => 'Pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Update tim bahwa Leader sudah membayar
            // Tambahkan kolom payment_status jika belum ada di team_list
            if (DB::getSchemaBuilder()->hasColumn('team_list', 'payment_status')) {
                $team->update([
                    'payment_status' => 'Pending',
                    'registered_by' => $validated['name']
                ]);
            }
        }

        // Clear session
        $request->session()->forget(['current_team_id', 'created_team_id', 'joining_team_id']);

        // Redirect ke halaman sukses
        return redirect()->route('form.player.success', [
            'team_id' => $teamId,
            'player_id' => $player->id
        ])->with('success', 'Pendaftaran berhasil!');
    }

    /**
     * Halaman sukses pendaftaran player
     */
    public function showSuccessPage($team_id, $player_id)
    {
        $team = TeamList::findOrFail($team_id);
        $player = PlayerList::with('schoolData')->findOrFail($player_id);
        
        return view('user.form.form_player_success', compact('team', 'player'));
    }

    /**
     * Cek NIK unik (AJAX)
     */
    public function checkNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|string'
        ]);
        
        $exists = PlayerList::where('nik', $request->nik)->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'NIK sudah terdaftar' : 'NIK tersedia'
        ]);
    }

    /**
     * Cek email unik (AJAX)
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $exists = PlayerList::where('email', $request->email)->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email sudah terdaftar' : 'Email tersedia'
        ]);
    }

    /**
     * Cek apakah player sudah ada di tim (Leader check)
     */
    public function checkLeaderExists(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:team_list,team_id',
            'category' => 'required|in:putra,putri,dancer'
        ]);
        
        $exists = PlayerList::where('team_id', $request->team_id)
            ->where('team_role', 'Leader')
            ->where('category', $request->category)
            ->exists();
        
        return response()->json([
            'exists' => $exists,
            'role' => $exists ? 'Player' : 'Leader'
        ]);
    }
}