<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use App\Models\Sponsor;
use Illuminate\Database\QueryException;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ========== TAMBAHKAN: REGISTER MIDDLEWARE ==========
        $this->registerMiddlewareAliases();
        
        // ========== TAMBAHKAN: CUSTOM BLADE DIRECTIVES ==========
        $this->addCustomDirectives();
        
        // ========== TAMBAHKAN: HELPER FUNCTIONS ==========
        $this->addHelperFunctions();
        
        // ========== TAMBAHKAN: SHARED VIEW DATA ==========
        $this->shareViewData();
        
        // ========== TAMBAHKAN: VALIDATION RULES ==========
        $this->addValidationRules();
    }

    /**
     * Register middleware aliases untuk memastikan middleware 'admin' terdaftar
     */
    protected function registerMiddlewareAliases(): void
    {
        $router = $this->app['router'];
        
        // Daftarkan middleware jika belum terdaftar
        if (!array_key_exists('admin', $router->getMiddleware())) {
            $router->aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        }
        
        if (!array_key_exists('student', $router->getMiddleware())) {
            $router->aliasMiddleware('student', \App\Http\Middleware\StudentMiddleware::class);
        }
        
        if (!array_key_exists('role', $router->getMiddleware())) {
            $router->aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);
        }
        
        // Tambahkan juga untuk handle jika ada middleware yang belum dibuat
        $this->ensureMiddlewareExists();
    }

    /**
     * Pastikan file middleware benar-benar ada
     */
    protected function ensureMiddlewareExists(): void
    {
        $middlewareFiles = [
            'AdminMiddleware.php' => \App\Http\Middleware\AdminMiddleware::class,
            'StudentMiddleware.php' => \App\Http\Middleware\StudentMiddleware::class,
            'RoleMiddleware.php' => \App\Http\Middleware\RoleMiddleware::class,
        ];
        
        foreach ($middlewareFiles as $file => $class) {
            $filePath = app_path('Http/Middleware/' . $file);
            
            if (!file_exists($filePath)) {
                // Log warning jika middleware file tidak ditemukan
                \Log::warning("Middleware file {$file} not found at {$filePath}");
            }
        }
    }

    /**
     * Tambahkan helper functions yang bisa digunakan di views
     */
    protected function addHelperFunctions(): void
    {
        // Method 1: Blade directive untuk formatBytes
        Blade::directive('formatBytes', function ($expression) {
            return "<?php 
                echo \App\Providers\AppServiceProvider::formatBytesHelper($expression);
            ?>";
        });

        // Method 2: Tambahkan ke semua views sebagai variabel global
        View::composer('*', function ($view) {
            $view->with('formatBytesHelper', function ($bytes, $precision = 2) {
                return self::formatBytesHelper($bytes, $precision);
            });
            
            // Tambahkan helper untuk cek role user
            $view->with('isAdmin', function () {
                return auth()->check() && auth()->user()->role === 'admin';
            });
            
            $view->with('isStudent', function () {
                return auth()->check() && auth()->user()->role === 'student';
            });
        });

        // Method 3: Tambahkan ke Blade sebagai fungsi
        Blade::directive('formatbytes', function ($expression) {
            return "<?php echo \App\Providers\AppServiceProvider::formatBytesHelper($expression); ?>";
        });
    }

    /**
     * Share sponsor data ke semua views
     */
    protected function shareViewData(): void
    {
        try {
            // ⛑️ AMAN: hanya jalan jika DB & tabel siap
            if (Schema::hasTable('sponsors')) {
                $groupedSponsors = Sponsor::orderBy('category')
                    ->orderBy('created_at')
                    ->get()
                    ->groupBy('category');

                View::composer('*', function ($view) use ($groupedSponsors) {
                    $view->with('groupedSponsors', $groupedSponsors);
                });
            }
        } catch (\Throwable $e) {
            // ⛔ DIAMKAN error:
            // - composer install
            // - migrate
            // - fresh clone
            // - env belum siap
        }
    }

    /**
     * Tambahkan custom blade directives
     */
    protected function addCustomDirectives(): void
    {
        // Directive untuk cek role user
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->role === 'admin';
        });
        
        Blade::if('student', function () {
            return auth()->check() && auth()->user()->role === 'student';
        });
        
        Blade::if('guest', function () {
            return !auth()->check();
        });
        
        // Directive untuk cek permission
        Blade::if('can', function ($permission) {
            return auth()->check() && auth()->user()->can($permission);
        });
        
        // Directive untuk cek role
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });
        
        // Directive untuk cek multiple roles
        Blade::directive('hasrole', function ($expression) {
            return "<?php if(auth()->check() && in_array(auth()->user()->role, explode('|', {$expression}))): ?>";
        });
        
        Blade::directive('endhasrole', function () {
            return "<?php endif; ?>";
        });
        
        // Directive untuk cek status
        Blade::if('published', function ($status) {
            return $status === 'published';
        });

        Blade::if('draft', function ($status) {
            return $status === 'draft';
        });

        Blade::if('archived', function ($status) {
            return $status === 'archived';
        });
        
        // Directive untuk formating tanggal
        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression) ? \Carbon\Carbon::parse($expression)->translatedFormat('d F Y H:i') : '-'; ?>";
        });
        
        Blade::directive('dateonly', function ($expression) {
            return "<?php echo ($expression) ? \Carbon\Carbon::parse($expression)->translatedFormat('d F Y') : '-'; ?>";
        });
        
        // Directive untuk cek active route
        Blade::directive('active', function ($expression) {
            return "<?php echo request()->routeIs($expression) ? 'active' : ''; ?>";
        });
    }

    /**
     * Tambahkan custom validation rules
     */
    protected function addValidationRules(): void
    {
        // Contoh custom validation rules
        // Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
        //     return preg_match('/^[0-9]{10,15}$/', $value);
        // });
        
        // Validator::extend('nik', function ($attribute, $value, $parameters, $validator) {
        //     return preg_match('/^[0-9]{16}$/', $value);
        // });
    }

    /**
     * Helper function untuk format bytes
     *
     * @param int|float $bytes Ukuran dalam bytes
     * @param int $precision Presisi desimal
     * @return string
     */
    public static function formatBytesHelper($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        
        // Pastikan bytes adalah angka positif
        $bytes = max((float) $bytes, 0);
        
        // Jika bytes = 0, langsung return 0 B
        if ($bytes === 0) {
            return '0 ' . $units[0];
        }
        
        // Hitung unit yang sesuai
        $base = 1024;
        $pow = floor(log($bytes, $base));
        $pow = min($pow, count($units) - 1);
        
        // Format hasil
        $formatted = $bytes / pow($base, $pow);
        return round($formatted, $precision) . ' ' . $units[$pow];
    }
    
    /**
     * Helper function untuk memformat angka (ribuan separator)
     */
    public static function formatNumber($number, $decimals = 0): string
    {
        return number_format($number, $decimals, ',', '.');
    }
    
    /**
     * Helper function untuk trim teks panjang
     */
    public static function trimText($text, $length = 100, $suffix = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }
}