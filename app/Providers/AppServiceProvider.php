<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Sponsor;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ⛑️ CEGAT DULU: pastikan tabel sponsors SUDAH ADA
        if (Schema::hasTable('sponsors')) {

            $groupedSponsors = Sponsor::orderBy('category')
                                      ->orderBy('created_at')
                                      ->get()
                                      ->groupBy('category');

            View::composer('*', function ($view) use ($groupedSponsors) {
                $view->with('groupedSponsors', $groupedSponsors);
            });

        }
    }
}
