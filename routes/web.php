<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Marketing Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Auth::routes(['verify' => true]);

/*
|--------------------------------------------------------------------------
| App Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', 'HomeController@index')->name('app.dashboard');
Route::get('/settings', 'HomeController@index')->name('app.settings');

/*
|--------------------------------------------------------------------------
| Strava Routes
|--------------------------------------------------------------------------
*/

Route::prefix('strava')
    ->namespace('\App\Strava\Http\Controllers')
    ->group(function () {

        Route::get('athlete/{athlete}', 'SettingsController@show')
            ->name('app.strava.athlete.show');

        Route::post('athlete/{athlete}', 'SettingsController@update')
            ->name('app.strava.athlete.update');

        Route::delete('athlete/{athlete}', 'SettingsController@destroy')
            ->name('app.strava.athlete.destroy');

        Route::get('authorize', 'AuthorizeController')
            ->name('app.strava.oauth.authorize')
            ->middleware('verified');

        Route::get('callback', 'CallbackController')
            ->name('app.strava.oauth.callback');

        Route::get('webhook', 'WebhookValidationController')
            ->name('app.strava.webhook.validation')
            ->middleware('json');

        Route::post('webhook', 'WebhookController')
            ->name('app.strava.webhook.invoke')
            ->middleware('json');

    });
