<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire;
use Illuminate\Pagination\Paginator;
use App\Models\Trip;
use App\Observers\TripObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Livewire components handle their own authorization via Gates

        // Use Tailwind CSS for pagination
        Paginator::useTailwind();

        // Register observers
        Trip::observe(TripObserver::class);
    }
}
