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
                'nik' => 'required|digits:16',
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
                'gender' => 'required|in:male,female',
                'email' => 'required|email|unique:official_list,email',
                'phone' => 'required|string|max:15',
                'school' => 'required|string|max:255',
                'height' => 'required|numeric|min:100|max:250',
                'weight' => 'required|numeric|min:30|max:200',
                'team_role' => 'required|in:Coach,Manager,Medical Support,Assistant Coach,Pendamping',
                'category' => 'required|in:basket_putra,basket_putri,dancer',
                'tshirt_size' => 'required|in:S,M,L,XL,XXL',
                'shoes_size' => 'required|integer|min:36|max:46',
                'instagram' => 'required|string|max:255',
                'tiktok' => 'required|string|max:255',
                'formal_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'license_photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'identity_card' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'assignment_letter' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'terms' => 'required|accepted',
            ], [
                'email.unique' => 'Email sudah terdaftar sebagai official.',
                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus 16 digit.',
                'name.required' => 'Nama lengkap wajib diisi.',
                'birthdate.required' => 'Tanggal lahir wajib diisi.',
                'birthdate.before_or_equal' => 'Usia minimal 18 tahun.',
                'gender.required' => 'Jenis kelamin wajib dipilih.',
                'phone.required' => 'Nomor WhatsApp wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'team_role.required' => 'Peran dalam tim wajib dipilih.',
                'height.required' => 'Tinggi badan wajib diisi.',
                'weight.required' => 'Berat badan wajib diisi.',
                'tshirt_size.required' => 'Ukuran kaos wajib dipilih.',
                'shoes_size.required' => 'Ukuran sepatu wajib dipilih.',
                'instagram.required' => 'Instagram wajib diisi.',
                'tiktok.required' => 'TikTok wajib diisi.',
                'formal_photo.required' => 'Foto formal wajib diupload.',
                'license_photo.required' => 'Lisensi/sertifikat wajib diupload.',
                'identity_card.required' => 'Foto KTP/SIM wajib diupload.',
                'assignment_letter.required' => 'Surat tugas wajib diupload.',
                'terms.accepted' => 'Anda harus menyetujui syarat & ketentuan.',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed: ' . json_encode($validator->errors()->toArray()));
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ==================== VALIDASI TIM ====================
            $team = TeamList::find($request->team_id);

            if (!$team) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Tim tidak ditemukan.')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            if ($team->locked_status === 'locked') {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Tim ini sudah dikunci dan tidak dapat menambah official.')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            if (!$team->referral_code) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Referral code tidak valid.')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            if ($team->team_category === 'Official') {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Tim Official tidak dapat menambah official.')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ✅ CEK KATEGORI
            $categoryMap = [
                'basket_putra' => 'Basket Putra',
                'basket_putri' => 'Basket Putri',
                'dancer' => 'Dancer'
            ];

            $expectedTeamCategory = $categoryMap[$request->category] ?? null;

            if ($team->team_category !== $expectedTeamCategory) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', "Kategori tidak sesuai! Tim ini untuk {$team->team_category}, bukan untuk {$expectedTeamCategory}.")
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ✅ CEK DUPLIKAT NIK
            $existingOfficial = OfficialList::where('team_id', $request->team_id)
                ->where('nik', $request->nik)
                ->first();

            if ($existingOfficial) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'NIK sudah terdaftar sebagai official di tim ini!')
                    ->withInput()
                    ->with('team_id', $request->team_id);
            }

            // ✅ CEK DUPLIKAT EMAIL
            $existingEmail = OfficialList::where('email', $request->email)->first();
            if ($existingEmail) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Email sudah terdaftar sebagai official!')
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
                    'city_id' => 1,
                ]);
            }

            // Update team dengan school_id jika belum ada
            if (!$team->school_id && $school) {
                $team->update(['school_id' => $school->id]);
            }

            // ==================== HANDLE FILE UPLOADS ====================
            // Pastikan direktori ada
            $this->ensureDirectoriesExist();

            $formalPhotoPath = null;
            $licensePhotoPath = null;
            $identityCardPath = null;
            $assignmentLetterPath = null;

            // Upload formal_photo
            if ($request->hasFile('formal_photo')) {
                $file = $request->file('formal_photo');
                $filename = time() . '_formal_' . preg_replace('/[^a-zA-Z0-9]/', '_', $request->name) . '.' . $file->getClientOriginalExtension();
                $formalPhotoPath = $file->storeAs('uploads/officials/formal_photos', $filename, 'public');
                
                if (!$formalPhotoPath) {
                    throw new \Exception('Gagal upload foto formal');
                }
                Log::info('Formal photo uploaded: ' . $formalPhotoPath);
            }

            // Upload license_photo
            if ($request->hasFile('license_photo')) {
                $file = $request->file('license_photo');
                $filename = time() . '_license_' . preg_replace('/[^a-zA-Z0-9]/', '_', $request->name) . '.' . $file->getClientOriginalExtension();
                $licensePhotoPath = $file->storeAs('uploads/officials/license_photos', $filename, 'public');
                
                if (!$licensePhotoPath) {
                    throw new \Exception('Gagal upload lisensi');
                }
                Log::info('License photo uploaded: ' . $licensePhotoPath);
            }

            // Upload identity_card
            if ($request->hasFile('identity_card')) {
                $file = $request->file('identity_card');
                $filename = time() . '_ktp_' . preg_replace('/[^a-zA-Z0-9]/', '_', $request->name) . '.' . $file->getClientOriginalExtension();
                $identityCardPath = $file->storeAs('uploads/officials/identity_cards', $filename, 'public');
                
                if (!$identityCardPath) {
                    throw new \Exception('Gagal upload KTP');
                }
                Log::info('Identity card uploaded: ' . $identityCardPath);
            }

            // Upload assignment_letter (Surat Tugas)
            if ($request->hasFile('assignment_letter')) {
                $file = $request->file('assignment_letter');
                $filename = time() . '_assignment_' . preg_replace('/[^a-zA-Z0-9]/', '_', $request->name) . '.' . $file->getClientOriginalExtension();
                $assignmentLetterPath = $file->storeAs('uploads/officials/assignment_letters', $filename, 'public');
                
                if (!$assignmentLetterPath) {
                    throw new \Exception('Gagal upload surat tugas');
                }
                Log::info('Assignment letter uploaded: ' . $assignmentLetterPath);
            }

            // Validasi file upload wajib
            if (!$formalPhotoPath || !$licensePhotoPath || !$identityCardPath || !$assignmentLetterPath) {
                throw new \Exception('Semua file wajib diupload');
            }

            // ==================== CREATE OFFICIAL ====================
            $officialData = [
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
                'shoes_size' => (string)$request->shoes_size,
                'instagram' => $request->instagram,
                'tiktok' => $request->tiktok,
                'formal_photo' => $formalPhotoPath,
                'license_photo' => $licensePhotoPath,
                'identity_card' => $identityCardPath,
                'assignment_letter' => $assignmentLetterPath,
                'role' => 'Member',
                'verification_status' => 'unverified',
                'is_finalized' => false,
                'unlocked_by_admin' => false,
            ];

            Log::info('Creating official with data:', $officialData);

            $official = OfficialList::create($officialData);

            if (!$official) {
                throw new \Exception('Gagal menyimpan data official ke database');
            }

            Log::info('Official created successfully with ID: ' . $official->official_id);

            DB::commit();

            return redirect()->to('/form/official/success/' . $request->team_id . '/' . $official->official_id)
    ->with('success', 'Pendaftaran official berhasil!');
            
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            Log::error('Database error: ' . $e->getMessage());
            
            // 🔥 PERBAIKAN: Ganti isset($e->getSql()) dengan method_exists atau error code
            if (method_exists($e, 'getSql') && $e->getSql()) {
                Log::error('SQL: ' . $e->getSql());
            }
            
            $bindings = method_exists($e, 'getBindings') ? $e->getBindings() : [];
            Log::error('Bindings: ' . json_encode($bindings));
            
            return redirect()->back()
                ->with('error', 'Database error: ' . $e->getMessage())
                ->withInput()
                ->with('team_id', $request->team_id);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('====================================');
            Log::error('ERROR STORING OFFICIAL: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            Log::error('Request data: ' . json_encode($request->except(['formal_photo', 'license_photo', 'identity_card', 'assignment_letter'])));
            Log::error('====================================');

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput()
                ->with('team_id', $request->team_id);
        }
    }

    /**
     * Ensure directories exist for file uploads
     */
    private function ensureDirectoriesExist()
    {
        $directories = [
            'public/uploads',
            'public/uploads/officials',
            'public/uploads/officials/formal_photos',
            'public/uploads/officials/license_photos',
            'public/uploads/officials/identity_cards',
            'public/uploads/officials/assignment_letters',
        ];

        foreach ($directories as $directory) {
            $path = storage_path('app/' . $directory);
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
                Log::info('Created directory: ' . $path);
            }
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
}