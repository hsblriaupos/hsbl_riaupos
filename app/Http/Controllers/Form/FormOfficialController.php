<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\OfficialList;
use App\Models\TeamList;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FormOfficialController extends Controller
{
    /**
     * Show official form
     */
    public function showOfficialForm(Request $request, $team_id)
    {
        $team = TeamList::findOrFail($team_id);
        
        // Cek apakah tim sudah locked
        if ($team->locked_status === 'locked') {
            return redirect()->route('form.team.choice')
                ->with('error', 'Tim ini sudah dikunci dan tidak dapat menambah official.');
        }
        
        // Ambil session untuk menentukan role
        $canBeLeader = session('current_can_be_leader', false);
        $role = $canBeLeader ? 'Leader' : 'Member';
        
        // Ambil kategori tim dari nama tim atau team_type
        $teamCategory = $this->determineTeamCategory($team);
        
        return view('user.form.form_official', compact('team_id', 'team', 'role', 'canBeLeader', 'teamCategory'));
    }

    /**
     * Store official data
     */
    public function storeOfficial(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'team_id' => 'required|exists:team_list,team_id',
                'nik' => 'required|unique:official_list,nik|digits:16',
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'gender' => 'required|in:male,female',
                'email' => 'required|email|unique:official_list,email',
                'phone' => 'required|string|max:15',
                'school' => 'required|string|max:255',
                'height' => 'nullable|numeric|min:100|max:250',
                'weight' => 'nullable|numeric|min:30|max:200',
                'team_role' => 'required|in:Coach,Manager,Medical Support,Assistant Coach,Pendamping',
                'category' => 'required|in:basket_putra,basket_putri,dancer,lainnya', // Validasi baru
                'tshirt_size' => 'nullable|string|max:10',
                'shoes_size' => 'nullable|string|max:10',
                'instagram' => 'nullable|string|max:255',
                'tiktok' => 'nullable|string|max:255',
                'formal_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'license_photo' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'identity_card' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'terms' => 'required|accepted',
            ], [
                'nik.unique' => 'NIK sudah terdaftar.',
                'nik.digits' => 'NIK harus 16 digit.',
                'email.unique' => 'Email sudah terdaftar.',
                'formal_photo.required' => 'Foto formal wajib diunggah.',
                'identity_card.required' => 'Foto KTP/SIM wajib diunggah.',
                'team_role.required' => 'Pilih peran dalam tim.',
                'category.required' => 'Pilih kategori official.', // Pesan error baru
                'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
                'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }
            
            $team = TeamList::findOrFail($request->team_id);
            
            // Cek apakah tim sudah locked
            if ($team->locked_status === 'locked') {
                return redirect()->back()
                    ->with('error', 'Tim ini sudah dikunci dan tidak dapat menambah official.')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }
            
            // Cari atau buat sekolah
            $school = School::where('school_name', $request->school)->first();
            if (!$school) {
                $school = School::create([
                    'school_name' => $request->school,
                    'category_name' => 'SMA',
                    'type' => 'SWASTA',
                    'city_id' => 1,
                ]);
                
                // Update team dengan school_id baru
                $team->update(['school_id' => $school->id]);
            }
            
            // Handle file uploads
            $formalPhotoPath = null;
            $licensePhotoPath = null;
            $identityCardPath = null;
            
            if ($request->hasFile('formal_photo')) {
                $formalPhotoPath = $request->file('formal_photo')->store('uploads/officials/formal_photos', 'public');
            }
            
            if ($request->hasFile('license_photo')) {
                $licensePhotoPath = $request->file('license_photo')->store('uploads/officials/license_photos', 'public');
            }
            
            if ($request->hasFile('identity_card')) {
                $identityCardPath = $request->file('identity_card')->store('uploads/officials/identity_cards', 'public');
            }
            
            // Cek jika ini official pertama, set sebagai Leader
            // Gunakan session untuk menentukan apakah bisa jadi leader
            $canBeLeader = session('current_can_be_leader', false);
            $role = $canBeLeader ? 'Leader' : 'Member';
            
            // Double check: jika sudah ada leader, tetap jadi member
            if ($role === 'Leader') {
                $existingLeaderCount = OfficialList::where('team_id', $request->team_id)
                    ->where('role', 'Leader')
                    ->count();
                    
                if ($existingLeaderCount > 0) {
                    $role = 'Member';
                }
            }
            
            // Create official dengan category
            $official = OfficialList::create([
                'team_id' => $request->team_id,
                'school_id' => $school->id,
                'nik' => $request->nik,
                'name' => $request->name,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'email' => $request->email,
                'phone' => $request->phone,
                'school_name' => $school->school_name,
                'height' => $request->height,
                'weight' => $request->weight,
                'team_role' => $request->team_role,
                'category' => $request->category, // Tambah ini
                'tshirt_size' => $request->tshirt_size,
                'shoes_size' => $request->shoes_size,
                'instagram' => $request->instagram,
                'tiktok' => $request->tiktok,
                'formal_photo' => $formalPhotoPath,
                'license_photo' => $licensePhotoPath,
                'identity_card' => $identityCardPath,
                'role' => $role,
                'verification_status' => 'unverified',
            ]);
            
            DB::commit();
            
            // Clear session untuk official
            session()->forget('current_can_be_leader');
            
            return redirect()->route('form.official.success', [
                'team_id' => $request->team_id,
                'official_id' => $official->official_id
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing official: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput()
                ->with('team_id', $request->team_id);
        }
    }

    /**
     * Show success page
     */
    public function showSuccessPage($team_id, $official_id)
    {
        $official = OfficialList::findOrFail($official_id);
        $team = TeamList::findOrFail($team_id);
        
        return view('user.form.form_official_success', compact('official', 'team'));
    }

    /**
     * Check NIK availability
     */
    public function checkNik(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|digits:16'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message' => 'NIK harus 16 digit'
            ], 422);
        }
        
        $exists = OfficialList::where('nik', $request->nik)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'NIK sudah terdaftar' : 'NIK tersedia'
        ]);
    }

    /**
     * Check Email availability
     */
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message' => 'Format email tidak valid'
            ], 422);
        }
        
        $exists = OfficialList::where('email', $request->email)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Email sudah terdaftar' : 'Email tersedia'
        ]);
    }

    /**
     * Check if leader already exists in team
     */
    public function checkLeaderExists(Request $request)
    {
        $exists = OfficialList::where('team_id', $request->team_id)
            ->where('role', 'Leader')
            ->exists();
            
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Tim ini sudah memiliki Leader official' : 'Belum ada Leader official'
        ]);
    }

    /**
     * Check team payment status
     */
    public function checkTeamPayment(Request $request)
    {
        $team = TeamList::find($request->team_id);
        
        if (!$team) {
            return response()->json([
                'paid' => false,
                'message' => 'Tim tidak ditemukan'
            ]);
        }
        
        return response()->json([
            'paid' => $team->payment_status === 'paid',
            'message' => $team->payment_status === 'paid' 
                ? 'Tim sudah melakukan pembayaran' 
                : 'Tim belum melakukan pembayaran'
        ]);
    }

    /**
     * Determine team category from team name or type
     */
    private function determineTeamCategory($team)
    {
        $teamName = strtolower($team->team_name ?? '');
        
        // Cek berdasarkan nama tim
        if (str_contains($teamName, 'putra') || str_contains($teamName, 'boys')) {
            return 'basket_putra';
        } elseif (str_contains($teamName, 'putri') || str_contains($teamName, 'girls')) {
            return 'basket_putri';
        } elseif (str_contains($teamName, 'dancer') || str_contains($teamName, 'cheer')) {
            return 'dancer';
        }
        
        // Default
        return 'basket_putra'; // atau 'lainnya' sesuai kebutuhan
    }
}