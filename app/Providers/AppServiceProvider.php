<?php

namespace App\Providers;
use App\Services\DataIngestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DataIngestService::class, function () {
            return new DataIngestService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(125);

        Inertia::share('errors', function () {
            if (Session::get('errors')) {
                $bags = [];
                foreach (Session::get('errors')->getBags() as $bag => $error) {
                    $bags[$bag] = $error->getMessages();
                }
                return $bags;
            }

            return (object)[];
        });

    }
}
