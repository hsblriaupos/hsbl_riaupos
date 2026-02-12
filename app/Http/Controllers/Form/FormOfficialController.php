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

        // ✅ CEK: Apakah tim ini valid untuk didaftarin official?
        if (!$team->referral_code) {
            return redirect()->route('form.team.choice')
                ->with('error', 'Referral code tidak valid. Hubungi admin untuk bantuan.');
        }

        // ✅ CEK: Apakah tim ini adalah tim Official? (Kalo iya, jangan dijadiin official!)
        if ($team->team_category === 'Official') {
            return redirect()->route('form.team.choice')
                ->with('error', 'Tim Official tidak dapat menambah official. Silakan gunakan referral code tim Basket Putra, Basket Putri, atau Dancer.');
        }

        // KIRIM DATA KE VIEW
        return view('user.form.form_official', compact(
            'team_id',
            'team'
        ));
    }

    /**
     * Store official data
     */
    public function storeOfficial(Request $request)
    {
        try {
            DB::beginTransaction();

            // ==================== VALIDASI DASAR ====================
            $validator = Validator::make($request->all(), [
                'team_id' => 'required|exists:team_list,team_id',
                'nik' => 'required|unique:official_list,nik|digits:16',
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
                'gender' => 'required|in:male,female',
                'email' => 'required|email|unique:official_list,email',
                'phone' => 'required|string|max:15',
                'school' => 'required|string|max:255',
                'height' => 'nullable|numeric|min:100|max:250',
                'weight' => 'nullable|numeric|min:30|max:200',
                'team_role' => 'required|in:Coach,Manager,Medical Support,Assistant Coach,Pendamping',
                'category' => 'required|in:basket_putra,basket_putri,dancer',
                'tshirt_size' => 'nullable|in:S,M,L,XL,XXL',
                'shoes_size' => 'nullable|integer|min:36|max:46',
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
                'birthdate.before_or_equal' => 'Usia minimal 18 tahun.',
                'formal_photo.required' => 'Foto formal wajib diunggah.',
                'identity_card.required' => 'Foto KTP/SIM wajib diunggah.',
                'team_role.required' => 'Pilih peran dalam tim.',
                'category.required' => 'Pilih kategori official.',
                'category.in' => 'Kategori tidak valid.',
                'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
                'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ==================== VALIDASI TIM ====================
            $team = TeamList::find($request->team_id);
            
            // ✅ CEK 1: Tim harus ada
            if (!$team) {
                return redirect()->back()
                    ->with('error', 'Referral code tidak valid! Tim tidak ditemukan.')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ✅ CEK 2: Tim tidak boleh locked
            if ($team->locked_status === 'locked') {
                return redirect()->back()
                    ->with('error', 'Tim ini sudah dikunci dan tidak dapat menambah official.')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ✅ CEK 3: Referral code harus ADA dan valid
            if (!$team->referral_code) {
                return redirect()->back()
                    ->with('error', 'Referral code tidak valid atau sudah tidak digunakan. Hubungi admin.')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ✅ CEK 4: Tim tidak boleh bertipe 'Official'
            if ($team->team_category === 'Official') {
                return redirect()->back()
                    ->with('error', 'Tim Official tidak dapat menambah official. Gunakan referral code tim Basket Putra, Basket Putri, atau Dancer.')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ==================== VALIDASI KATEGORI ====================
            // Mapping kategori official ke kategori tim
            $categoryMap = [
                'basket_putra' => 'Basket Putra',
                'basket_putri' => 'Basket Putri',
                'dancer' => 'Dancer'
            ];

            $expectedTeamCategory = $categoryMap[$request->category] ?? null;

            // ✅ CEK 5: Kategori harus cocok dengan tim!
            if ($team->team_category !== $expectedTeamCategory) {
                // Cari tim yang benar untuk kategori ini
                $correctTeam = TeamList::where('school_name', $team->school_name)
                    ->where('team_category', $expectedTeamCategory)
                    ->whereNotNull('referral_code')
                    ->first();

                if ($correctTeam) {
                    $message = "Referral code salah! Anda menggunakan kode untuk tim {$team->team_category}. "
                             . "Gunakan referral code berikut untuk tim {$expectedTeamCategory}: "
                             . "<br><strong style='background: #f0f0f0; padding: 5px 10px; border-radius: 5px;'>{$correctTeam->referral_code}</strong>";
                    
                    return redirect()->back()
                        ->with('error', $message)
                        ->withInput()
                        ->with('team_id', $request->team_id);
                }

                return redirect()->back()
                    ->with('error', "Referral code tidak sesuai! Kode ini untuk tim {$team->team_category}, bukan untuk {$expectedTeamCategory}.")
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ✅ CEK 6: Cek apakah sudah pernah daftar sebagai official di tim ini
            $existingOfficial = OfficialList::where('team_id', $request->team_id)
                ->where('nik', $request->nik)
                ->first();

            if ($existingOfficial) {
                return redirect()->back()
                    ->with('error', 'Anda sudah terdaftar sebagai official di tim ini!')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ==================== CEK ATAU BUAT SEKOLAH ====================
            $school = School::where('school_name', $request->school)->first();
            if (!$school) {
                $school = School::create([
                    'school_name' => $request->school,
                    'category_name' => 'SMA',
                    'type' => 'SWASTA',
                    'city_id' => 1, // Default city
                ]);

                // Update team dengan school_id baru
                $team->update(['school_id' => $school->id]);
            }

            // ==================== HANDLE FILE UPLOADS ====================
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

            // ==================== CREATE OFFICIAL ====================
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
                'category' => $request->category,
                'tshirt_size' => $request->tshirt_size,
                'shoes_size' => $request->shoes_size,
                'instagram' => $request->instagram,
                'tiktok' => $request->tiktok,
                'formal_photo' => $formalPhotoPath,
                'license_photo' => $licensePhotoPath,
                'identity_card' => $identityCardPath,
                'role' => 'Member',
                'verification_status' => 'unverified',
                'is_finalized' => false,
                'unlocked_by_admin' => false,
            ]);

            DB::commit();

            // ✅ KALAU BERHASIL, LANGSUNG REDIRECT KE SUCCESS PAGE
            return redirect()->route('form.official.success', [
                'team_id' => $request->team_id,
                'official_id' => $official->official_id
            ])->with('success', 'Pendaftaran official berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('====================================');
            Log::error('ERROR STORING OFFICIAL: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Request data: ' . json_encode($request->all()));
            Log::error('====================================');

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi atau hubungi admin.')
                ->withInput()
                ->with('team_id', $request->team_id);
        }
    }

    /**
     * Show success page
     */
    public function showSuccessPage($team_id, $official_id)
    {
        $official = OfficialList::with(['team', 'school'])->findOrFail($official_id);
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
     * Get team categories for dropdown (Opsional - Kalo mau pake AJAX)
     */
    public function getTeamCategories($team_id)
    {
        $team = TeamList::find($team_id);
        
        if (!$team) {
            return response()->json([]);
        }

        $categories = [];
        
        // Tambahkan kategori dari team itu sendiri (kalo bukan 'Official')
        if ($team->team_category && $team->team_category !== 'Official') {
            $categoryValue = match($team->team_category) {
                'Basket Putra' => 'basket_putra',
                'Basket Putri' => 'basket_putri',
                'Dancer' => 'dancer',
                default => null
            };
            
            if ($categoryValue) {
                $categories[] = [
                    'value' => $categoryValue,
                    'label' => $team->team_category,
                    'referral_code' => $team->referral_code
                ];
            }
        }
        
        return response()->json($categories);
    }
}