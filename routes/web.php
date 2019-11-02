<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('strava')
    ->namespace('\App\Strava\Controllers')
    ->group(function () {

    Route::get('authorize', 'AuthorizeController')
        ->name('strava.oauth.authorize')
        ->middleware('verified');

    Route::get('callback', 'CallbackController')
        ->name('strava.oauth.callback');

    Route::get('webhook', 'WebhookValidationController')
        ->name('strava.webhook.validation')
        ->middleware('json');

    Route::post('webhook', 'WebhookController')
        ->name('strava.webhook.invoke')
        ->middleware('json');

});
