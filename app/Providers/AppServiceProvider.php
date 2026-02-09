<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
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
        // Tambahkan helper function formatBytes ke semua views
        $this->addHelperFunctions();
        
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
        });

        // Method 3: Tambahkan ke Blade sebagai fungsi
        Blade::directive('formatbytes', function ($expression) {
            return "<?php echo \App\Providers\AppServiceProvider::formatBytesHelper($expression); ?>";
        });
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
     * Register custom Blade if directive untuk memeriksa status
     */
    protected function addCustomDirectives(): void
    {
        // Directive untuk cek jika status adalah 'published'
        Blade::if('published', function ($status) {
            return $status === 'published';
        });

        // Directive untuk cek jika status adalah 'draft'
        Blade::if('draft', function ($status) {
            return $status === 'draft';
        });

        // Directive untuk cek jika status adalah 'archived'
        Blade::if('archived', function ($status) {
            return $status === 'archived';
        });
    }
}