<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Book;
use App\Observers\BookObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register Book Observer to auto-set owner
        Book::observe(BookObserver::class);
    }
}
