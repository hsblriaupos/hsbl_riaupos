<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DataActionController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\Camper\CamperController;
use App\Http\Controllers\Form\FormTeamController;
use App\Http\Controllers\Form\FormPlayerController;
use App\Http\Controllers\Form\FormDancerController;
use App\Http\Controllers\Form\FormOfficialController;
use App\Http\Controllers\GoogleController\GoogleController;
use App\Http\Controllers\Publication\PubMatchDataController;
use App\Http\Controllers\Publication\PubMatchResult;
use App\Http\Controllers\Sponsor\SponsorController;
use App\Http\Controllers\TeamVerification\TeamController;
use App\Http\Controllers\User\GalleryController as UserGalleryController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\NewsController;
use App\Http\Controllers\User\PublicationController;
use App\Http\Controllers\Student\StudentAuthController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentSchoolController;
use App\Models\TermCondition;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| 🏠 LANDING PAGE (Welcome) - Akan tampil user/dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('user.dashboard');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| 🔐 AUTHENTICATION ROUTES (Public)
|--------------------------------------------------------------------------
*/

// Login Page dengan 2 tab (Admin & Student)
Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AdminLoginController::class, 'login'])->name('login');

// Google Authentication
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Shared Logout - handle semua tipe user
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 📝 STUDENT AUTHENTICATION ROUTES (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->group(function () {
    // Login Page - redirect ke halaman utama dengan tab student aktif
    Route::get('/login', function () {
        return redirect()->route('login.form')->with('active_tab', 'student');
    })->name('login');

    // Login Process
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.submit');

    // Registration
    Route::get('/register', [StudentAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register'])->name('register.submit');

    // Forgot Password
    Route::get('/forgot-password', [StudentAuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [StudentAuthController::class, 'forgotPassword'])->name('password.email');

    // Student-specific logout
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| 🛡️ ADMIN AREA (Prefix: /admin) - PROTECTED dengan middleware 'auth' saja
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // ========== DATA MASTER ROUTES ==========

    // City Management
    Route::get('/city', [AdminController::class, 'city'])->name('all_data_city');
    Route::post('/city', [AdminController::class, 'storeCity'])->name('city.store');
    Route::post('/city/edit', [DataActionController::class, 'edit'])->name('city.edit');
    Route::post('/city/delete', [DataActionController::class, 'delete'])->name('city.delete');

    // General Data (add_data)
    Route::get('/data', [AdminController::class, 'allData'])->name('all_data');
    Route::post('/data/store', [AdminController::class, 'storeData'])->name('data.store');
    Route::post('/data/edit', [DataActionController::class, 'edit'])->name('data.edit');
    Route::post('/data/delete', [DataActionController::class, 'delete'])->name('data.delete');

    // School Management
    Route::get('/school', [AdminController::class, 'school'])->name('all_data_school');
    Route::post('/school/store', [AdminController::class, 'storeSchool'])->name('school.store');
    Route::post('/school/edit', [DataActionController::class, 'edit'])->name('school.edit');
    Route::post('/school/delete', [DataActionController::class, 'delete'])->name('school.delete');

    // Venue Management
    Route::get('/venue', [AdminController::class, 'venue'])->name('all_data_venue');
    Route::post('/venue', [AdminController::class, 'storeVenue'])->name('venue.store');
    Route::post('/venue/edit', [DataActionController::class, 'edit'])->name('venue.edit');
    Route::post('/venue/delete', [DataActionController::class, 'delete'])->name('venue.delete');

    // Award Management
    Route::get('/award', [AdminController::class, 'award'])->name('all_data_award');
    Route::post('/award/store', [AdminController::class, 'storeAward'])->name('award.store');
    Route::post('/award/edit', [DataActionController::class, 'edit'])->name('award.edit');
    Route::post('/award/delete', [DataActionController::class, 'delete'])->name('award.delete');

    // Dynamic Data Routes (untuk view saja)
    Route::get('/data/{type}', [DataActionController::class, 'index'])
        ->where('type', 'school|venue|match|award|camper|city|event|match_result')
        ->name('data.dynamic');

    // Export Data
    Route::get('/export/{type}', [DataActionController::class, 'export'])
        ->where('type', 'school|venue|match|award')
        ->name('data.export');

    // ========== TEAM VERIFICATION ==========
    Route::get('/team-list', [TeamController::class, 'teamList'])->name('tv_team_list');
    Route::get('/team-list/{id}', [TeamController::class, 'teamShow'])->name('team-list.show');
    Route::get('/team-list/export', [TeamController::class, 'export'])->name('team-list.export');

    // Team Verification Actions
    Route::post('/team/{id}/lock', [TeamController::class, 'lock'])->name('team.lock');
    Route::post('/team/{id}/unlock', [TeamController::class, 'unlock'])->name('team.unlock');
    Route::post('/team/{id}/verify', [TeamController::class, 'verify'])->name('team.verify');
    Route::post('/team/{id}/unverify', [TeamController::class, 'unverify'])->name('team.unverify');

    Route::get('/player/{id}', [TeamController::class, 'playerDetail'])->name('player.detail');
    Route::get('/team-verification', [TeamController::class, 'teamVerification'])->name('tv_team_verification');
    Route::get('/team-awards', [TeamController::class, 'teamAwards'])->name('tv_team_awards');

    // Camper Management
    Route::get('/camper', [CamperController::class, 'camper'])->name('camper_team');
    Route::get('/camper/detail/{id}', [CamperController::class, 'camperDetail'])->name('camper.detail');
    Route::post('/camper/detail/update/{id}', [CamperController::class, 'updateCamper'])->name('camper.update');

    // ========== PUBLICATION MANAGEMENT ==========

    Route::prefix('pub_schedule')->name('pub_schedule.')->group(function () {
        Route::get('/', [PubMatchDataController::class, 'index'])->name('index');
        Route::get('/create/{event_id?}', [PubMatchDataController::class, 'create'])->name('create');
        Route::post('/', [PubMatchDataController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PubMatchDataController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PubMatchDataController::class, 'update'])->name('update');
        Route::delete('/{id}', [PubMatchDataController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-destroy', [PubMatchDataController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::post('/{id}/publish', [PubMatchDataController::class, 'publish'])->name('publish');
        Route::post('/{id}/unpublish', [PubMatchDataController::class, 'unpublish'])->name('unpublish');
        Route::post('/{id}/done', [PubMatchDataController::class, 'done'])->name('done');
    });

    // **RESULT MANAGEMENT - SEMUA ROUTES**
    Route::prefix('pub_result')->name('pub_result.')->group(function () {
        Route::get('/', [PubMatchResult::class, 'index'])->name('index');
        Route::get('/create/{event_id?}', [PubMatchResult::class, 'create'])->name('create');
        Route::post('/', [PubMatchResult::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PubMatchResult::class, 'edit'])->name('edit');
        Route::put('/{id}', [PubMatchResult::class, 'update'])->name('update');
        Route::delete('/{id}', [PubMatchResult::class, 'destroy'])->name('destroy');

        // Action routes
        Route::post('/{id}/publish', [PubMatchResult::class, 'publish'])->name('publish');
        Route::post('/{id}/unpublish', [PubMatchResult::class, 'unpublish'])->name('unpublish');
        Route::post('/{id}/done', [PubMatchResult::class, 'done'])->name('done');
        Route::get('/{id}/download-scoresheet', [PubMatchResult::class, 'downloadScoresheet'])->name('download_scoresheet');

        // Bulk actions
        Route::post('/bulk-destroy', [PubMatchResult::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::post('/bulk-publish', [PubMatchResult::class, 'bulkPublish'])->name('bulk-publish');
    });

    // ========== CONTENT MANAGEMENT ==========

    // Sponsor Management
    Route::get('/sponsor', [SponsorController::class, 'sponsor'])->name('sponsor.sponsor');
    Route::get('/sponsor/create', [SponsorController::class, 'create'])->name('sponsor.create');
    Route::post('/sponsor', [SponsorController::class, 'store'])->name('sponsor.store');
    Route::get('/sponsor/{id}/edit', [SponsorController::class, 'edit'])->name('sponsor.edit');
    Route::put('/sponsor/{id}', [SponsorController::class, 'update'])->name('sponsor.update');
    Route::delete('/sponsor/{id}', [SponsorController::class, 'destroy'])->name('sponsor.destroy');
    Route::post('/sponsor/destroy-selected', [SponsorController::class, 'destroySelected'])->name('sponsor.destroySelected');

    // News Management
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{id}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('news.destroy');
    Route::delete('/news/bulk/delete', [AdminNewsController::class, 'bulkDestroy'])->name('news.bulk-destroy');

    // Terms & Conditions Management
    Route::get('/term-conditions', [TermConditionController::class, 'index'])->name('term_conditions.index');
    Route::post('/term-conditions', [TermConditionController::class, 'store'])->name('term_conditions.store');
    Route::delete('/term-conditions/delete-selected', [TermConditionController::class, 'destroySelected'])->name('term_conditions.destroySelected');
    Route::delete('/term-conditions/{id}', [TermConditionController::class, 'destroy'])->name('term_conditions.destroy');

    // Video Management
    Route::get('videos', [AdminGalleryController::class, 'index'])->name('videos.index');
    Route::get('videos/create', [AdminGalleryController::class, 'create'])->name('videos.create');
    Route::post('videos', [AdminGalleryController::class, 'store'])->name('videos.store');
    Route::get('videos/{video}/edit', [AdminGalleryController::class, 'edit'])->name('videos.edit');
    Route::put('videos/{video}', [AdminGalleryController::class, 'update'])->name('videos.update');
    Route::delete('videos/{video}', [AdminGalleryController::class, 'destroy'])->name('videos.destroy');
});

/*
|--------------------------------------------------------------------------
| 👨‍🎓 STUDENT AREA (Prefix: /student) - PROTECTED dengan middleware 'auth' saja
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['auth'])->group(function () {
    // Dashboard Student - Arahkan ke form_team.blade.php sesuai permintaan
    Route::get('/dashboard', function () {
        return view('user.form.form_team');
    })->name('dashboard');

    // Notifikasi
    Route::get('/notifications', function () {
        return view('student.notifications');
    })->name('notifications');

    // Edit Profil
    Route::get('/profile/edit', function () {
        return view('student.profile_edit');
    })->name('profile.edit');

    // Tim Saya
    Route::get('/my-team', function () {
        return view('student.my_team');
    })->name('team');

    // ========== SCHOOL DATA MANAGEMENT ==========
    // Edit Data Sekolah (Route yang ditambahkan)
    Route::get('/school/edit', function () {
        return view('student.school_edit');
    })->name('school.edit');

    // ========== PROFILE MANAGEMENT ==========
    // Profile Management
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.password');

    // Team Management (jika siswa punya tim)
    Route::get('/team/players', [StudentDashboardController::class, 'teamPlayers'])->name('team.players');

    // Schedule & Results
    Route::get('/schedules', [StudentDashboardController::class, 'schedules'])->name('schedules');
    Route::get('/results', [StudentDashboardController::class, 'results'])->name('results');

    // Documents
    Route::get('/documents', [StudentDashboardController::class, 'documents'])->name('documents');
    Route::get('/documents/download/{id}', [StudentDashboardController::class, 'downloadDocument'])->name('documents.download');
});

/*
|--------------------------------------------------------------------------
| 👤 USER / PUBLIC AREA (Prefix: /user) - PUBLIC
|--------------------------------------------------------------------------
*/
Route::prefix('user')->name('user.')->group(function () {
    // Dashboard User/Siswa
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // News Routes untuk User
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{id}', [NewsController::class, 'show'])->whereNumber('id')->name('news.show');

    // Schedules & Results untuk User
    Route::get('/schedules-results', [PublicationController::class, 'scheduleResult'])->name('schedule_result');

    // Videos untuk User
    Route::get('/videos', [UserGalleryController::class, 'videos'])->name('videos');
    Route::get('/videos/{slug}', [UserGalleryController::class, 'videoDetail'])->name('videos.detail');

    // Download Terms & Conditions
    Route::get('/download-terms', function () {
        $latestTerm = TermCondition::orderBy('year', 'desc')->first();

        if (!$latestTerm || !Storage::disk('public')->exists($latestTerm->file_path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->download($latestTerm->file_path, 'SyaratKetentuan-' . $latestTerm->year . '.pdf');
    })->name('download_terms');
});

/*
|--------------------------------------------------------------------------
| 📝 FORM REGISTRATION ROUTES (Public)
|--------------------------------------------------------------------------
*/
Route::prefix('form')->name('form.')->group(function () {
    // ================= TEAM REGISTRATION =================
    Route::get('/team/choice', [FormTeamController::class, 'showChoiceForm'])->name('team.choice');
    Route::get('/team/create', [FormTeamController::class, 'showCreateForm'])->name('team.create');
    Route::post('/team/create', [FormTeamController::class, 'createTeam'])->name('team.store');

    // JOIN TEAM FLOW
    Route::get('/team/join', [FormTeamController::class, 'showJoinForm'])->name('team.join');
    Route::post('/team/join', [FormTeamController::class, 'joinTeam'])->name('team.join.submit');

    // ROLE SELECTION
    Route::get('/team/join/role', [FormTeamController::class, 'showRoleSelectionForm'])
        ->name('team.join.role');
    Route::post('/team/join/role', [FormTeamController::class, 'processRoleSelection'])
        ->name('team.join.select-role');

    // School check endpoints
    Route::get('/team/check-school', [FormTeamController::class, 'checkSchool'])->name('team.checkSchool');
    Route::post('/team/check-school-exists', [FormTeamController::class, 'checkSchoolExists'])->name('team.checkSchoolExists');
    Route::post('/team/check-existing', [FormTeamController::class, 'checkExistingTeam'])->name('team.checkExisting');

    // ================= PLAYER REGISTRATION =================
    // 🔥 Route yang lebih jelas dan terstruktur

    // PLAYER REGISTRATION FLOW
    Route::get('/player/create/{team_id}', [FormPlayerController::class, 'showPlayerForm'])->name('player.create');

    // PLAYER REGISTRATION WITH CATEGORY (versi baru yang lebih spesifik)
    Route::get('/player/create/{team_id}/{category}', [FormPlayerController::class, 'showPlayerFormWithCategory'])
        ->name('player.create.with-category')
        ->where('category', 'putra|putri'); // hanya putra/putri untuk player

    Route::post('/player/store', [FormPlayerController::class, 'storePlayer'])->name('player.store');
    Route::get('/player/success/{team_id}/{player_id}', [FormPlayerController::class, 'showSuccessPage'])->name('player.success');

    // API Endpoints for Player
    Route::prefix('player')->name('player.')->group(function () {
        Route::post('/check-nik', [FormPlayerController::class, 'checkNik'])->name('checkNik');
        Route::post('/check-email', [FormPlayerController::class, 'checkEmail'])->name('checkEmail');
        Route::post('/check-leader', [FormPlayerController::class, 'checkLeaderExists'])->name('checkLeader');
        Route::post('/check-team-payment', [FormPlayerController::class, 'checkTeamPayment'])->name('checkTeamPayment');
    });

    // ================= DANCER REGISTRATION =================
    // DANCER FORM
    Route::get('/dancer/create/{team_id}', [FormDancerController::class, 'showDancerForm'])
        ->name('dancer.create');

    // DANCER STORE
    Route::post('/dancer/store', [FormDancerController::class, 'storeDancer'])
        ->name('dancer.store');

    // DANCER SUCCESS
    Route::get('/dancer/success/{team_id}/{dancer_id}', [FormDancerController::class, 'showSuccessPage'])
        ->name('dancer.success');

    // API endpoints for Dancer
    Route::prefix('dancer')->name('dancer.')->group(function () {
        Route::post('/check-nik', [FormDancerController::class, 'checkNik'])->name('checkNik');
        Route::post('/check-email', [FormDancerController::class, 'checkEmail'])->name('checkEmail');
        Route::post('/check-leader', [FormDancerController::class, 'checkLeaderExists'])->name('checkLeader');
        Route::post('/check-team-payment', [FormDancerController::class, 'checkTeamPayment'])->name('checkTeamPayment');
    });

    // ================= OFFICIAL REGISTRATION =================
    // OFFICIAL FORM
    Route::get('/official/create/{team_id}', [FormOfficialController::class, 'showOfficialForm'])
        ->name('official.create');

    // OFFICIAL STORE
    Route::post('/official/store', [FormOfficialController::class, 'storeOfficial'])
        ->name('official.store');

    // OFFICIAL SUCCESS
    Route::get('/official/success/{team_id}/{official_id}', [FormOfficialController::class, 'showSuccessPage'])
        ->name('official.success');

    // API endpoints for Official
    Route::prefix('official')->name('official.')->group(function () {
        Route::post('/check-nik', [FormOfficialController::class, 'checkNik'])->name('checkNik');
        Route::post('/check-email', [FormOfficialController::class, 'checkEmail'])->name('checkEmail');
        Route::post('/check-leader', [FormOfficialController::class, 'checkLeaderExists'])->name('checkLeader');
        Route::post('/check-team-payment', [FormOfficialController::class, 'checkTeamPayment'])->name('checkTeamPayment');
    });

    // ================= FIX REFERRAL CODES (Development Only) =================
    if (app()->environment('local')) {
        Route::get('/fix-referral-codes', function () {
            // Konversi empty string ke NULL
            \DB::table('team_list')
                ->where('referral_code', '')
                ->orWhere('referral_code', 'NULL')
                ->orWhereRaw('TRIM(referral_code) = ""')
                ->update(['referral_code' => null]);

            return 'Referral codes fixed! Empty strings converted to NULL.';
        })->name('fix.referral.codes');

        Route::get('/check-referral-codes', function () {
            $nullCount = \DB::table('team_list')->whereNull('referral_code')->count();
            $emptyCount = \DB::table('team_list')->where('referral_code', '')->count();
            $total = \DB::table('team_list')->count();

            return response()->json([
                'total_teams' => $total,
                'null_referral_codes' => $nullCount,
                'empty_referral_codes' => $emptyCount,
                'percentage_null' => $total > 0 ? round(($nullCount / $total) * 100, 2) : 0
            ]);
        })->name('check.referral.codes');
    }
});

// ================= DASHBOARD REDIRECT =================
// Redirect dari halaman utama ke form choice
Route::get('/', function () {
    return redirect()->route('form.team.choice');
})->name('home');

// ================= FALLBACK ROUTE =================
Route::fallback(function () {
    return redirect()->route('form.team.choice')->with('error', 'Halaman tidak ditemukan.');
});

/*
|--------------------------------------------------------------------------
| 🌐 PUBLIC ROUTES (tanpa prefix)
|--------------------------------------------------------------------------
*/
// Redirect untuk login student
Route::get('/student-login', function () {
    return redirect()->route('login.form')->with('active_tab', 'student');
})->name('student.login.redirect');

// Redirect untuk register student
Route::get('/student-register', function () {
    return redirect()->route('student.register');
})->name('student.register.redirect');
