<?php

namespace App\Providers;

use App\Models\Personal;
use App\Observers\PersonalObserver;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function() {
            return base_path('adm');
        });
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // Filament::serving(function () {
        //     // Using Laravel Mix
        //     Filament::registerTheme(
        //         mix('css/filament.css'),
        //     );
        // });
    }
}
