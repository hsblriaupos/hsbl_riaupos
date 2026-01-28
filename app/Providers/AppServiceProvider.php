<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
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
}
