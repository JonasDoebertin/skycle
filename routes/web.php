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

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test/fetch', 'TestController@fetch');
Route::get('/test/decorate', 'TestController@decorate');
Route::get('/test/weather', 'TestController@weather');

Route::prefix('strava')->namespace('\App\Strava\Controllers')->group(function () {

    Route::get('authorize', 'AuthorizeController')
        ->name('strava.oauth.authorize');

    Route::get('callback', 'CallbackController')
        ->name('strava.oauth.callback');

    Route::get('webhook', 'WebhookValidationController')
        ->middleware('json')
        ->name('strava.webhook.validation');

    Route::post('webhook', 'WebhookController')
        ->middleware('json')
        ->name('strava.webhook.invoke');

});
