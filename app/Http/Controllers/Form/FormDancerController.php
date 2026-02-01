<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\TeamList;
use App\Models\DancerList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class FormDancerController extends Controller
{
    /**
     * Tampilkan form untuk daftar dancer
     */
    public function showDancerForm($team_id, $category = null)
    {
        // Ambil data tim
        $team = TeamList::findOrFail($team_id);

        // Cek apakah tim untuk dancer
        if (!str_contains(strtolower($team->team_category), 'dancer')) {
            abort(404, 'Tim ini bukan untuk kategori dancer.');
        }

        // Tentukan role user
        $role = $this->determineRole($team_id);

        // Data untuk form
        $genderOptions = ['Laki-laki', 'Perempuan'];
        $grades = ['X', 'XI', 'XII'];
        $tshirtSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        $shoesSizes = range(36, 45);

        return view('user.form.form_dancer', compact(
            'team',
            'role',
            'genderOptions',
            'grades',
            'tshirtSizes',
            'shoesSizes'
        ));
    }

    /**
     * Tentukan role user (Leader/Member)
     */
    private function determineRole($team_id)
    {
        $referralCode = session('join_referral_code');
        $team = TeamList::find($team_id);

        // Jika user datang dari join dengan referral code, dia Member
        if ($referralCode && $referralCode === $team->referral_code) {
            return 'Member';
        }

        // Cek apakah sudah ada leader dancer
        $existingDancerLeader = DancerList::where('team_id', $team_id)
            ->where('role', 'Leader')
            ->exists();

        // Jika belum ada leader dancer, bisa jadi Leader
        if (!$existingDancerLeader) {
            return 'Leader';
        }

        // Default jadi Member
        return 'Member';
    }

    /**
     * Simpan data dancer
     */
    public function storeDancer(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validasi
            $validator = $this->validateDancerData($request);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Cek NIK duplikat
            $existingNik = DancerList::where('nik', $request->nik)->exists();
            if ($existingNik) {
                return redirect()->back()
                    ->withErrors(['nik' => 'NIK sudah terdaftar!'])
                    ->withInput();
            }

            // Cek email duplikat
            $existingEmail = DancerList::where('email', $request->email)->exists();
            if ($existingEmail) {
                return redirect()->back()
                    ->withErrors(['email' => 'Email sudah terdaftar!'])
                    ->withInput();
            }

            // Tentukan role
            $team = TeamList::find($request->team_id);
            $role = $this->determineRoleForSubmission($request->team_id, $request);

            // Upload dokumen
            $documents = $this->uploadDocuments($request, $team->school_name);

            // Buat data dancer
            $dancer = DancerList::create([
                'team_id' => $request->team_id,
                'nik' => $request->nik,
                'name' => $request->name,
                'birthdate' => $request->birthdate,
                'gender' => $request->gender,
                'email' => $request->email,
                'phone' => $request->phone,
                'school_name' => $team->school_name,
                'grade' => $request->grade,
                'sttb_year' => $request->sttb_year,
                'height' => $request->height,
                'weight' => $request->weight,
                'tshirt_size' => $request->tshirt_size,
                'shoes_size' => $request->shoes_size,
                'instagram' => $request->instagram,
                'tiktok' => $request->tiktok,
                'father_name' => $request->father_name,
                'father_phone' => $request->father_phone,
                'mother_name' => $request->mother_name,
                'mother_phone' => $request->mother_phone,
                'birth_certificate' => $documents['birth_certificate'],
                'kk' => $documents['kk'],
                'shun' => $documents['shun'],
                'report_identity' => $documents['report_identity'],
                'last_report_card' => $documents['last_report_card'],
                'formal_photo' => $documents['formal_photo'],
                'assignment_letter' => $documents['assignment_letter'],
                'role' => $role,
                'verification_status' => 'unverified'
            ]);

            // Jika Leader, cek dan generate referral code jika perlu
            if ($role === 'Leader' && !$team->referral_code && $request->hasFile('payment_proof')) {
                $paymentPath = $this->uploadPaymentProof($request, $team->school_name);
                $referralCode = $this->generateReferralCode($team->school_name);

                $team->update([
                    'payment_proof' => $paymentPath,
                    'is_leader_paid' => true,
                    'payment_status' => 'pending',
                    'referral_code' => $referralCode
                ]);

                // Simpan referral code di session
                session(['team_referral_code' => $referralCode]);
            }

            DB::commit();

            return redirect()->route('form.dancer.success', [
                'team_id' => $team->team_id,
                'dancer_id' => $dancer->dancer_id
            ])->with('success', 'Pendaftaran dancer berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Validasi data dancer
     */
    private function validateDancerData(Request $request)
    {
        $rules = [
            // Data pribadi
            'nik' => 'required|digits:16',
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date|before:-10 years',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',

            // Data sekolah
            'grade' => 'required|in:X,XI,XII',
            'sttb_year' => 'required|digits:4|numeric|min:2020|max:' . date('Y'),

            // Data fisik
            'height' => 'required|numeric|min:100|max:250',
            'weight' => 'required|numeric|min:30|max:150',
            'tshirt_size' => 'required|in:XS,S,M,L,XL,XXL',
            'shoes_size' => 'required|in:36,37,38,39,40,41,42,43,44,45',

            // Data orang tua
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:15',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:15',

            // Dokumen (wajib)
            'birth_certificate' => 'required|file|mimes:pdf|max:1024',
            'kk' => 'required|file|mimes:pdf|max:1024',
            'shun' => 'required|file|mimes:pdf|max:1024',
            'report_identity' => 'required|file|mimes:pdf|max:1024',
            'last_report_card' => 'required|file|mimes:pdf|max:1024',
            'formal_photo' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'assignment_letter' => 'nullable|file|mimes:pdf|max:1024',

            // Syarat dan ketentuan
            'terms' => 'required|accepted'
        ];

        // Jika Leader, wajib upload bukti pembayaran
        $teamId = $request->team_id;
        $isFirstDancer = !DancerList::where('team_id', $teamId)->exists();

        if ($isFirstDancer) {
            $rules['payment_proof'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }

        return Validator::make($request->all(), $rules, [
            'birthdate.before' => 'Minimal usia 10 tahun',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan',
            'payment_proof.required' => 'Sebagai Leader, Anda wajib upload bukti pembayaran'
        ]);
    }

    /**
     * Tentukan role untuk submission
     */
    private function determineRoleForSubmission($teamId, Request $request)
    {
        // Cek apakah sudah ada leader dancer di tim ini
        $existingLeader = DancerList::where('team_id', $teamId)
            ->where('role', 'Leader')
            ->exists();

        // Jika belum ada leader dan user upload bukti pembayaran, jadi Leader
        if (!$existingLeader && $request->hasFile('payment_proof')) {
            return 'Leader';
        }

        return 'Member';
    }

    /**
     * Upload semua dokumen
     */
    private function uploadDocuments(Request $request, $schoolName)
    {
        $baseSlug = Str::slug($schoolName);
        $timestamp = time();
        $uploads = [];

        $documents = [
            'birth_certificate' => 'birth_certificate',
            'kk' => 'kk',
            'shun' => 'shun',
            'report_identity' => 'report_identity',
            'last_report_card' => 'last_report_card',
            'formal_photo' => 'formal_photo',
            'assignment_letter' => 'assignment_letter'
        ];

        foreach ($documents as $field => $prefix) {
            if ($request->hasFile($field) && $request->file($field)->isValid()) {
                $file = $request->file($field);
                $extension = $file->getClientOriginalExtension();
                $filename = "{$baseSlug}_{$prefix}_{$timestamp}.{$extension}";

                $path = $file->storeAs("dancer_docs/{$prefix}", $filename, 'public');
                $uploads[$field] = $path;
            } else {
                $uploads[$field] = null;
            }
        }

        return $uploads;
    }

    /**
     * Upload bukti pembayaran
     */
    private function uploadPaymentProof(Request $request, $schoolName)
    {
        if (!$request->hasFile('payment_proof')) {
            return null;
        }

        $baseSlug = Str::slug($schoolName);
        $timestamp = time();
        $file = $request->file('payment_proof');
        $extension = $file->getClientOriginalExtension();
        $filename = "{$baseSlug}_payment_{$timestamp}.{$extension}";

        return $file->storeAs('payment_proofs', $filename, 'public');
    }

    /**
     * Generate referral code
     */
    private function generateReferralCode($schoolName)
    {
        $base = strtoupper(Str::slug($schoolName, ''));
        $random = strtoupper(Str::random(4));
        return "DANCER-{$base}-{$random}";
    }

    /**
     * Cek ketersediaan NIK
     */
    public function checkNik(Request $request)
    {
        $exists = DancerList::where('nik', $request->nik)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'NIK sudah terdaftar' : 'NIK tersedia'
        ]);
    }

    /**
     * Cek ketersediaan Email
     */
    public function checkEmail(Request $request)
    {
        $exists = DancerList::where('email', $request->email)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email sudah terdaftar' : 'Email tersedia'
        ]);
    }

    /**
     * Cek apakah sudah ada Leader
     */
    public function checkLeaderExists(Request $request)
    {
        $exists = DancerList::where('team_id', $request->team_id)
            ->where('role', 'Leader')
            ->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Tim ini sudah memiliki Leader' : 'Belum ada Leader'
        ]);
    }

    /**
     * Cek status pembayaran tim
     */
    public function checkTeamPayment(Request $request)
    {
        $team = TeamList::find($request->team_id);

        return response()->json([
            'is_paid' => $team->is_leader_paid ?? false,
            'has_referral' => !empty($team->referral_code),
            'referral_code' => $team->referral_code
        ]);
    }

    /**
     * Tampilkan halaman sukses
     */
    public function showSuccessPage($team_id, $dancer_id)
    {
        $team = TeamList::findOrFail($team_id);
        $dancer = DancerList::findOrFail($dancer_id);

        return view('user.form.form_dancer_success', compact('team', 'dancer'));
    }
}
