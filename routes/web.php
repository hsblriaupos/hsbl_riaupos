<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DataActionController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\TermConditionController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Camper\CamperController;
use App\Http\Controllers\Form\FormTeamController;
use App\Http\Controllers\Form\FormPlayerController;
use App\Http\Controllers\Form\FormDancerController;
use App\Http\Controllers\Form\FormOfficialController;
use App\Http\Controllers\GoogleController\GoogleController;
use App\Http\Controllers\Publication\Gallery\PhotosController;
use App\Http\Controllers\Publication\Gallery\VideosController;
use App\Http\Controllers\Publication\PubMatchDataController;
use App\Http\Controllers\Publication\PubMatchResult;
use App\Http\Controllers\Publication\StatisticsController as PublicationStatisticsController;
use App\Http\Controllers\Sponsor\SponsorController;
use App\Http\Controllers\TeamVerification\TeamController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\NewsController;
use App\Http\Controllers\User\ScheduleController;
use App\Http\Controllers\User\ResultController;
use App\Http\Controllers\User\UserPhotosController;
use App\Http\Controllers\User\UserVideosController;
use App\Http\Controllers\User\StatisticsController as UserStatisticsController;
use App\Http\Controllers\Student\StudentAuthController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentSchoolController;
use App\Http\Controllers\Student\StudentTeamController;
// PERBAIKAN: Tambahkan controller untuk SchoolDataProfile
use App\Http\Controllers\Student\SchoolDataProfileController;
use App\Models\TermCondition;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| 🏠 LANDING PAGE (Welcome) - Akan tampil user/dashboard sebagai halaman utama
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
| 🛡️ ADMIN AREA (Prefix: /admin) - PROTECTED dengan middleware 'auth' dan 'admin'
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // ========== USER MANAGEMENT ROUTES ==========
    Route::prefix('resetpassword')->name('resetpassword.')->group(function () {
        Route::get('/', [ResetPasswordController::class, 'index'])->name('index');
        Route::get('/get-user-info/{userId}', [ResetPasswordController::class, 'getUserInfo'])->name('get-user-info');
        Route::post('/update', [ResetPasswordController::class, 'updatePassword'])->name('update');
        Route::post('/bulk-update', [ResetPasswordController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/user-detail/{userId}', [ResetPasswordController::class, 'userDetail'])->name('user-detail');
        Route::get('/logs', [ResetPasswordController::class, 'logs'])->name('logs');
        Route::get('/export-logs', [ResetPasswordController::class, 'exportLogs'])->name('export-logs');
    });

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

    Route::get('/team-verification', [TeamController::class, 'teamVerification'])->name('tv_team_verification');
    Route::get('/team-awards', [TeamController::class, 'teamAwards'])->name('tv_team_awards');

    // Tab System Routes - TAMBAHKAN INI
    Route::get('/team/{id}/basket-putra', [TeamController::class, 'teamDetailBasketPutra'])
        ->name('team.detail.basket-putra');
    Route::get('/team/{id}/basket-putri', [TeamController::class, 'teamDetailBasketPutri'])
        ->name('team.detail.basket-putri');
    Route::get('/team/{id}/dancer', [TeamController::class, 'teamDetailDancer'])
        ->name('team.detail.dancer');

    // Camper Management
    Route::get('/camper', [CamperController::class, 'camper'])->name('camper_team');
    Route::get('/camper/detail/{id}', [CamperController::class, 'camperDetail'])->name('camper.detail');
    Route::post('/camper/detail/update/{id}', [CamperController::class, 'updateCamper'])->name('camper.update');

    // Player Detail
    Route::get('/player/{id}', [TeamController::class, 'playerDetail'])->name('player.detail');

    // Dancer Detail
    Route::get('/dancer/{id}', [TeamController::class, 'dancerDetail'])->name('dancer.detail');
    Route::post('/dancer/{id}/verify', [TeamController::class, 'verifyDancer'])->name('dancer.verify');
    Route::post('/dancer/{id}/unverify', [TeamController::class, 'unverifyDancer'])->name('dancer.unverify');
    Route::post('/dancer/{id}/reject', [TeamController::class, 'rejectDancer'])->name('dancer.reject');

    // Official Detail  
    Route::get('/official/{id}', [TeamController::class, 'officialDetail'])->name('official.detail');

    
    // ========== PUBLICATION MANAGEMENT ==========

    Route::prefix('pub_schedule')->name('pub_schedule.')->group(function () {
        Route::get('/', [PubMatchDataController::class, 'index'])->name('index');
        Route::get('/create/{event_id?}', [PubMatchDataController::class, 'create'])->name('create');
        Route::post('/', [PubMatchDataController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PubMatchDataController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PubMatchDataController::class, 'update'])->name('update');
        Route::delete('/{id}', [PubMatchDataController::class, 'destroy'])->name('destroy');
        Route::delete('/bulk-destroy', [PubMatchDataController::class, 'bulkDestroy'])->name('bulk-destroy');
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
        Route::delete('/bulk-destroy', [PubMatchResult::class, 'bulkDestroy'])->name('bulk-destroy');
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
    Route::delete('/sponsor/destroy-selected', [SponsorController::class, 'destroySelected'])->name('sponsor.destroySelected');

    // Statistics Page untuk Admin
    Route::get('/statistics', [PublicationStatisticsController::class, 'index'])->name('statistics');

    // News Management
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{id}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('news.destroy');
    Route::delete('/news/bulk/delete', [AdminNewsController::class, 'bulkDestroy'])->name('news.bulk-destroy');

    // Terms & Conditions Management
    Route::prefix('term-conditions')->name('term_conditions.')->group(function () {
        Route::get('/', [TermConditionController::class, 'index'])->name('index');
        Route::post('/', [TermConditionController::class, 'store'])->name('store');
        Route::delete('/destroy-selected', [TermConditionController::class, 'destroySelected'])->name('destroySelected');
        Route::delete('/{id}', [TermConditionController::class, 'destroy'])->name('destroy');
        
        // Route untuk download dan view
        Route::get('/{id}/download', [TermConditionController::class, 'download'])->name('download');
        Route::get('/{id}/view', [TermConditionController::class, 'view'])->name('view');
        
        // Route alias untuk backward compatibility
        Route::get('/sponsor', [TermConditionController::class, 'index'])->name('sponsor');
    });

    // ========== MEDIA GALLERY ROUTES ==========
    
    // VIDEOS MANAGEMENT ROUTES
    Route::prefix('videos')->name('videos.')->group(function () {
        Route::get('/', [VideosController::class, 'index'])->name('index');
        Route::get('/create', function() {
            return view('admin.media.gallery.videos_form');
        })->name('create');
        Route::post('/', [VideosController::class, 'store'])->name('store');
        Route::get('/{id}', [VideosController::class, 'show'])->name('show');
        Route::get('/{id}/edit', function($id) {
            $video = \App\Models\Video::findOrFail($id);
            return view('admin.media.gallery.videos_edit', compact('video'));
        })->name('edit');
        Route::put('/{id}', [VideosController::class, 'update'])->name('update');
        Route::delete('/{id}', [VideosController::class, 'destroy'])->name('destroy');
        
        Route::delete('/bulk/destroy', [VideosController::class, 'bulkDestroy'])->name('bulk-destroy');
        
        Route::post('/{id}/status', [VideosController::class, 'changeStatus'])->name('change-status');
        Route::post('/{id}/toggle-status', [VideosController::class, 'toggleStatus'])->name('toggle-status');
        
        Route::get('/{id}/ajax', [VideosController::class, 'getVideoAjax'])->name('ajax');
        Route::get('/{id}/details', [VideosController::class, 'getVideoDetails'])->name('details');
    });
    
    // ========== MEDIA GALLERY GROUP ROUTES ==========
    
    Route::prefix('gallery')->name('gallery.')->group(function () {
        // PHOTOS ROUTES YANG LENGKAP DAN BENAR
        Route::prefix('photos')->name('photos.')->group(function () {
            Route::get('/', [PhotosController::class, 'index'])->name('index');
            Route::get('/create', [PhotosController::class, 'create'])->name('create');
            Route::get('/form', [PhotosController::class, 'form'])->name('form');
            Route::post('/', [PhotosController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PhotosController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PhotosController::class, 'update'])->name('update');
            Route::delete('/{id}', [PhotosController::class, 'destroy'])->name('destroy');
            
            // ROUTE BULK ACTIONS
            Route::delete('/bulk/destroy', [PhotosController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::post('/bulk/download', [PhotosController::class, 'bulkDownload'])->name('bulk-download');
            
            Route::get('/{id}/download', [PhotosController::class, 'download'])->name('download');
            Route::post('/{id}/increment-download', [PhotosController::class, 'incrementDownload'])->name('increment-download');
            
            // ROUTE STATUS MANAGEMENT
            Route::post('/{id}/publish', [PhotosController::class, 'publish'])->name('publish');
            Route::post('/{id}/unpublish', [PhotosController::class, 'unpublish'])->name('unpublish');
            Route::post('/{id}/toggle-status', [PhotosController::class, 'toggleStatus'])->name('toggle-status');
            
            Route::get('/statistics', [PhotosController::class, 'statistics'])->name('statistics');
            
            // API endpoints
            Route::get('/api/schools', [PhotosController::class, 'getSchools'])->name('api.schools');
            Route::get('/api/competitions', [PhotosController::class, 'getCompetitions'])->name('api.competitions');
            Route::get('/api/seasons', [PhotosController::class, 'getSeasons'])->name('api.seasons');
            Route::get('/api/series', [PhotosController::class, 'getSeries'])->name('api.series');
        });

        // VIDEOS ROUTES - tidak perlu duplikat
    });
    
    // Route simple untuk bulk delete jika masih error
    Route::post('/gallery-photos/bulk-delete', [PhotosController::class, 'bulkDestroy'])
        ->name('admin.gallery.photos.bulk-delete-simple');
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

    // ========== PROFILE MANAGEMENT ==========
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        // Halaman profil utama
        Route::get('/', [StudentProfileController::class, 'index'])->name('index');
        Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
        
        // Update profil
        Route::put('/update', [StudentProfileController::class, 'update'])->name('update');
        Route::put('/password', [StudentProfileController::class, 'updatePassword'])->name('password.update');
        
        // AJAX endpoints untuk profil
        Route::get('/password-info', [StudentProfileController::class, 'getCurrentPasswordInfo'])->name('password.info');
        Route::post('/verify-password', [StudentProfileController::class, 'verifyPassword'])->name('password.verify');
        Route::post('/check-email', [StudentProfileController::class, 'checkEmailAvailability'])->name('email.check');
        Route::post('/validate-password', [StudentProfileController::class, 'validatePasswordStrength'])->name('password.validate');
        
        // Avatar management
        Route::post('/avatar/upload', [StudentProfileController::class, 'uploadAvatar'])->name('avatar.upload');
        Route::delete('/avatar/remove', [StudentProfileController::class, 'removeAvatar'])->name('avatar.remove');
        
        // Email verification
        Route::post('/send-verification', [StudentProfileController::class, 'sendVerificationEmail'])->name('verification.send');
        
        // PERBAIKAN: Tambahkan route untuk generate password token
        Route::post('/generate-password-token', [StudentProfileController::class, 'generatePasswordToken'])
            ->name('generate.password.token');
            
        // PERBAIKAN: Tambahkan route untuk verify auto token
        Route::post('/verify-auto-token', [StudentProfileController::class, 'verifyAutoToken'])
            ->name('verify.auto.token');
        
        // Check temp password
        Route::get('/check-temp-password', [StudentProfileController::class, 'checkTempPassword'])
            ->name('check.temp.password');
        
        // Get profile data
        Route::get('/get-profile-data', [StudentProfileController::class, 'getProfileData'])
            ->name('get.profile.data');
        
        // Logs and data export
        Route::get('/password-logs', [StudentProfileController::class, 'showPasswordLogs'])->name('password.logs');
        Route::get('/export-data', [StudentProfileController::class, 'exportData'])->name('data.export');
        
        // Preferences
        Route::put('/preferences', [StudentProfileController::class, 'updatePreferences'])->name('preferences.update');
    });

    // ========== SCHOOL DATA MANAGEMENT ==========
    // Edit Data Sekolah
    Route::prefix('school')->name('school.')->group(function () {
        Route::get('/edit', [StudentSchoolController::class, 'edit'])->name('edit');
        Route::post('/update', [StudentSchoolController::class, 'update'])->name('update');
    });

    // ========== TEAM MANAGEMENT ==========
    // Team List
    Route::get('/team/list', [StudentTeamController::class, 'index'])->name('team.list');

    // Team Management (jika siswa punya tim)
    Route::get('/team/players', [StudentDashboardController::class, 'teamPlayers'])->name('team.players');

    // My Team
    Route::get('/my-team', function () {
        return view('student.my_team');
    })->name('team');

    // ========== SCHEDULE & RESULTS ==========
    // Schedule & Results
    Route::get('/schedules', [StudentDashboardController::class, 'schedules'])->name('schedules');
    Route::get('/results', [StudentDashboardController::class, 'results'])->name('results');

    // ========== DOCUMENTS ==========
    // Documents
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [StudentDashboardController::class, 'documents'])->name('index');
        Route::get('/download/{id}', [StudentDashboardController::class, 'downloadDocument'])->name('download');
    });
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

    // ========== REVISI: SCHEDULE & RESULTS ROUTES ==========
    
    // Main Schedule & Results Combined Page - Gunakan ScheduleController::index
    Route::get('/schedule-result', [ScheduleController::class, 'index'])->name('schedule_result');
    
    // Alias untuk backward compatibility
    Route::get('/schedules-results', function() {
        return redirect()->route('user.schedule_result');
    });
    
    // AJAX Filter Routes untuk Schedule
    Route::post('/schedules/filter', [ScheduleController::class, 'filterSchedules'])
        ->name('schedules.filter');
    
    // AJAX Filter Routes untuk Results
    Route::post('/results/filter', [ScheduleController::class, 'filterResults'])
        ->name('results.filter');
    
    // Individual Schedule Routes (API/Data)
    Route::prefix('schedule')->name('schedule.')->group(function () {
        // Route utama tetap menggunakan index (untuk API jika perlu)
        Route::get('/', [ScheduleController::class, 'index'])->name('index');
        
        // Detail route
        Route::get('/{id}', [ScheduleController::class, 'getScheduleDetail'])->name('show');
        
        // API endpoints
        Route::get('/today', [ScheduleController::class, 'getTodayMatches'])->name('today');
        Route::get('/calendar', [ScheduleController::class, 'getCalendar'])->name('calendar');
        Route::get('/search', [ScheduleController::class, 'search'])->name('search');
        Route::get('/latest/{limit?}', [ScheduleController::class, 'getLatest'])->name('latest');
        Route::post('/{id}/reminder', [ScheduleController::class, 'setReminder'])->name('reminder');
    });
    
    // Individual Results Routes - Pastikan menggunakan ResultController yang benar
    Route::prefix('results')->name('results.')->group(function () {
        // Main results page (jika terpisah)
        Route::get('/', [ResultController::class, 'index'])->name('index');
        
        // Detail route
        Route::get('/{id}', [ResultController::class, 'getResultDetail'])->name('show');
        
        // Route untuk download scoresheet - SESUAI DENGAN BLADE
        Route::get('/{id}/download-scoresheet', [ResultController::class, 'downloadScoresheet'])
            ->name('download.scoresheet');
            
        // API endpoints
        Route::get('/team/{teamId}', [ResultController::class, 'getByTeam'])->name('team');
        Route::get('/seasons', [ResultController::class, 'getSeasons'])->name('seasons');
        Route::get('/series', [ResultController::class, 'getSeries'])->name('series');
        Route::get('/statistics', [ResultController::class, 'getStatistics'])->name('statistics');
    });

    // ========== PERBAIKAN UTAMA: GALLERY PHOTOS ROUTES ==========
    
    // ROUTE UTAMA UNTUK PHOTOS GALLERY (USER)
    Route::prefix('media/gallery')->name('media.gallery.')->group(function () {
        // Route untuk photos list - GUNAKAN UserPhotosController
        Route::get('photos', [UserPhotosController::class, 'index'])->name('photos');
        
        // Route untuk photos detail
        Route::get('photos/{id}', [UserPhotosController::class, 'show'])->name('photos.detail');
        
        // PERBAIKAN: Tambahkan route download dengan name yang benar
        Route::get('photos/{id}/download', [UserPhotosController::class, 'download'])->name('photos.download');
        
        // Route untuk video list
        Route::get('videos', [UserVideosController::class, 'index'])->name('videos');
        
        // Route untuk video detail (dengan parameter slug)
        Route::get('videos/{slug}', [UserVideosController::class, 'show'])->name('videos.detail');
    });
    
    // PERBAIKAN: Tambahkan route group khusus untuk 'user.gallery.' prefix
    Route::prefix('gallery')->name('gallery.')->group(function () {
        // Photos routes dengan 'user.gallery.' prefix
        Route::prefix('photos')->name('photos.')->group(function () {
            Route::get('/', [UserPhotosController::class, 'index'])->name('index');
            Route::get('/{id}', [UserPhotosController::class, 'show'])->name('show');
            // INI ROUTE YANG DIPERLUKAN: user.gallery.photos.download
            Route::get('/{id}/download', [UserPhotosController::class, 'download'])->name('download');
        });
        
        // Videos routes dengan 'user.gallery.' prefix
        Route::prefix('videos')->name('videos.')->group(function () {
            Route::get('/', [UserVideosController::class, 'index'])->name('index');
            Route::get('/{slug}', [UserVideosController::class, 'show'])->name('show');
        });
    });
    
    // ALIAS ROUTES UNTUK KEMUDAHAN AKSES
    Route::get('/photos', [UserPhotosController::class, 'index'])->name('photos');
    Route::get('/photos/{id}', [UserPhotosController::class, 'show'])->name('photos.detail');
    Route::get('/photos/{id}/download', [UserPhotosController::class, 'download'])->name('photos.download.alias');
    
    Route::get('/videos', [UserVideosController::class, 'index'])->name('videos');
    Route::get('/videos/{slug}', [UserVideosController::class, 'show'])->name('videos.detail');
    
    // API ROUTES UNTUK PHOTOS (UNTUK AJAX/FILTER/MODAL)
    Route::prefix('ajax/photos')->name('ajax.photos.')->group(function () {
        Route::get('/details/{id}', [UserPhotosController::class, 'getDetails'])->name('details');
        Route::get('/filter-options', [UserPhotosController::class, 'getFilterOptions'])->name('filter-options');
        Route::get('/statistics', [UserPhotosController::class, 'getStatistics'])->name('statistics');
        Route::get('/search', [UserPhotosController::class, 'search'])->name('search');
    });
    
    // API ROUTES UNTUK VIDEOS
    Route::prefix('ajax/videos')->name('ajax.videos.')->group(function () {
        Route::get('/modal/{id}', [UserVideosController::class, 'getVideoModal'])->name('modal');
        Route::get('/type/{type}', [UserVideosController::class, 'getVideosByType'])->name('byType');
        Route::get('/latest/{limit?}', [UserVideosController::class, 'getLatestVideos'])->name('latest');
    });

    // ========== ABOUT & DEVELOPER ROUTES (TERPISAH DARI GALLERY) ==========
    Route::prefix('media')->name('media.')->group(function () {
        // About - langsung view
        Route::get('about', function() {
            return view('user.media.about.about');
        })->name('about');
        
        // Developer - langsung view
        Route::get('developer', function() {
            return view('user.media.about.developer');
        })->name('developer');
    });

    // Download Terms & Conditions
    Route::get('/download-terms', function () {
        $latestTerm = TermCondition::orderBy('year', 'desc')->first();

        if (!$latestTerm || !Storage::disk('public')->exists($latestTerm->file_path)) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->download($latestTerm->file_path, 'SyaratKetentuan-' . $latestTerm->year . '.pdf');
    })->name('download_terms');

    // PERBAIKAN: Statistics Page untuk User
    Route::get('/statistics', [UserStatisticsController::class, 'index'])->name('statistics');
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
    Route::get('/team/success/{team_id}', [FormTeamController::class, 'showTeamSuccessPage'])->name('team.success');

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
    });

    // ================= FIX REFERRAL CODES (Development Only) =================
    if (app()->environment('local')) {
        Route::get('/fix-referral-codes', function () {
            // Konversi empty string ke NULL
            DB::table('team_list')
                ->where('referral_code', '')
                ->orWhere('referral_code', 'NULL')
                ->orWhereRaw('TRIM(referral_code) = ""')
                ->update(['referral_code' => null]);

            return 'Referral codes fixed! Empty strings converted to NULL.';
        })->name('fix.referral.codes');

        Route::get('/check-referral-codes', function () {
            $nullCount = DB::table('team_list')->whereNull('referral_code')->count();
            $emptyCount = DB::table('team_list')->where('referral_code', '')->count();
            $total = DB::table('team_list')->count();

            return response()->json([
                'total_teams' => $total,
                'null_referral_codes' => $nullCount,
                'empty_referral_codes' => $emptyCount,
                'percentage_null' => $total > 0 ? round(($nullCount / $total) * 100, 2) : 0
            ]);
        })->name('check.referral.codes');
    }
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

// ========== PERBAIKAN UTAMA: ROUTE UNTUK HANDLE DOT NOTATION ==========

// ROUTE KHUSUS UNTUK HANDLE URL DENGAN DOT NOTATION
Route::get('/user.media.gallery.photos', function() {
    return redirect()->route('user.media.gallery.photos', [], 301);
})->name('user.media.gallery.photos.legacy');

// Untuk Videos dengan dot notation
Route::get('/user.media.gallery.videos', function() {
    return redirect()->route('user.media.gallery.videos', [], 301);
})->name('user.media.gallery.videos.legacy');

// Untuk download dengan dot notation
Route::get('/user.gallery.photos.download', function() {
    return redirect('/user/media/gallery/photos');
})->name('user.gallery.photos.download.legacy');

// Untuk semua kemungkinan dot notation lainnya
Route::get('/{any}.{any2}.{any3}.{any4}', function($any, $any2, $any3, $any4) {
    // Jika pattern cocok dengan user.media.gallery.*
    if ($any === 'user' && $any2 === 'media' && $any3 === 'gallery') {
        if ($any4 === 'photos') {
            return redirect()->route('user.media.gallery.photos', [], 301);
        } elseif ($any4 === 'videos') {
            return redirect()->route('user.media.gallery.videos', [], 301);
        }
    }
    // Jika pattern cocok dengan user.gallery.photos.*
    elseif ($any === 'user' && $any2 === 'gallery' && $any3 === 'photos') {
        if ($any4 === 'download') {
            return redirect('/user/media/gallery/photos');
        }
    }
    
    // Default redirect ke home
    return redirect('/');
})->where('any', '[a-zA-Z]+')
  ->where('any2', '[a-zA-Z]+')
  ->where('any3', '[a-zA-Z]+')
  ->where('any4', '[a-zA-Z]+');

// Public video view (for embedding)
Route::get('/video/{slug}', [UserVideosController::class, 'show'])->name('video.embed');

// Public photo view
Route::get('/photo/{slug}', [UserPhotosController::class, 'show'])->name('photo.public.show');

// ========== FALLBACK ROUTES (untuk backward compatibility) ==========
Route::get('/user/media/gallery/videos_list', function() {
    return redirect()->route('user.videos');
});

Route::get('/user/media/gallery/photos_list', function() {
    return redirect()->route('user.media.gallery.photos');
});

// ========== TEST ROUTE UNTUK VIDEO ==========
Route::get('/test-video', function() {
    return view('user.media.gallery.videos_list');
});

// ========== TEST ROUTE UNTUK PHOTOS ==========
Route::get('/test-photos', function() {
    return view('user.media.gallery.photos_list');
});

/*
|--------------------------------------------------------------------------
| 🔄 DIRECT ROUTES UNTUK DROPDOWN MENU (tanpa prefix student) - PERBAIKAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Edit Profile - arahkan ke blade yang sesuai
    Route::get('/profile/edit', function () {
        return view('user.event.profile.profile-edit');
    })->name('profile.edit');

    // 🔥 PERBAIKAN: Tambahkan route PUT untuk update profile
    Route::put('/profile/update', function () {
        // Sementara redirect ke student.profile.update sampai controller dibuat
        return redirect()->route('student.profile.update');
    })->name('profile.update');

    // ========== PERBAIKAN: SCHOOL DATA ROUTES ==========
    // My Schools (School Data List) - PERBAIKAN: menggunakan SchoolDataProfileController
    Route::get('/schooldata', [SchoolDataProfileController::class, 'index'])->name('schooldata.list');
    
    // Edit School Data - arahkan ke blade yang sesuai
    Route::get('/schooldata/edit', function () {
        return view('user.event.profile.schooldata-edit');
    })->name('schooldata.edit');
    
    // Edit School Data dengan parameter school_id
    Route::get('/schooldata/edit/{school_id}', function ($school_id) {
        return view('user.event.profile.schooldata-edit', ['school_id' => $school_id]);
    })->name('schooldata.edit.id');
    
    // Update School Data - route untuk POST update
    Route::post('/schooldata/update', [SchoolDataProfileController::class, 'update'])->name('schooldata.update');
    
    // Leave School - route untuk DELETE
    Route::delete('/schooldata/{school_id}/leave', [SchoolDataProfileController::class, 'leave'])->name('schooldata.leave');
    
    // View Team Profile
    Route::get('/team/profile/{team_id}', function ($team_id) {
        return view('user.event.profile.team-profile', ['team_id' => $team_id]);
    })->name('team.profile');
    
    // Player Profile
    Route::get('/player/profile/{id}', function ($id) {
        return view('user.event.profile.player-profile', ['id' => $id]);
    })->name('player.profile');
    
    // Dancer Profile
    Route::get('/dancer/profile/{dancer_id}', function ($dancer_id) {
        return view('user.event.profile.dancer-profile', ['dancer_id' => $dancer_id]);
    })->name('dancer.profile');
    
    // Official Profile
    Route::get('/official/profile/{official_id}', function ($official_id) {
        return view('user.event.profile.official-profile', ['official_id' => $official_id]);
    })->name('official.profile');

    // Team List - arahkan ke blade yang sesuai
    Route::get('/team/list', function () {
        return view('user.event.profile.teamlist');
    })->name('team.list');
});

// ========== FALLBACK ROUTE UNTUK HANDLE SLUG YANG TIDAK DITEMUKAN ==========
Route::fallback(function () {
    // Jika URL dimulai dengan /user/videos/, coba handle sebagai video detail
    if (request()->is('user/videos/*')) {
        $slug = request()->segment(3); // Ambil segment ke-3 (slug)
        return app()->make(App\Http\Controllers\User\UserVideosController::class)->callAction('show', [$slug]);
    }
    
    // Jika URL dimulai dengan /user/photos/, coba handle sebagai photo detail
    if (request()->is('user/photos/*')) {
        $id = request()->segment(3); // Ambil segment ke-3 (id)
        return app()->make(App\Http\Controllers\User\UserPhotosController::class)->callAction('show', [$id]);
    }
    
    // Jika URL menggunakan dot notation (user.media.gallery.*)
    $path = request()->path();
    if (strpos($path, '.') !== false) {
        $segments = explode('.', $path);
        if (count($segments) >= 4 && $segments[0] === 'user' && $segments[1] === 'media' && $segments[2] === 'gallery') {
            if ($segments[3] === 'photos') {
                return redirect()->route('user.media.gallery.photos');
            } elseif ($segments[3] === 'videos') {
                return redirect()->route('user.media.gallery.videos');
            }
        }
        // Handle user.gallery.photos.download
        if (count($segments) >= 4 && $segments[0] === 'user' && $segments[1] === 'gallery' && $segments[2] === 'photos' && $segments[3] === 'download') {
            return redirect('/user/media/gallery/photos');
        }
    }
    
    // Tampilkan 404 jika tidak ditemukan
    return response()->view('errors.404', [], 404);
});