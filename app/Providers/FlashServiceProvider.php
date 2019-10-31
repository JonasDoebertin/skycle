<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Flash\Flash;

class FlashServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Flash::levels([
            'success' => 'text-green-700 border-green-600 bg-green-100',
            'warning' => 'text-yellow-700 border-yellow-600 bg-yellow-100',
            'error'   => 'text-red-700 border-red-600 bg-red-100',
        ]);
    }
}
