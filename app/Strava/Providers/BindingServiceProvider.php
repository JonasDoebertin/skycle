<?php

namespace App\Strava\Providers;

use App\Strava\Components\Client;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Strava\API\Client as StravaClient;
use Strava\API\Service\REST;

class BindingServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function (Application $app, array $args) {
            $rest = new REST($args['token'], new Guzzle(['base_uri' => 'https://www.strava.com/api/v3/']));
            $strava = new StravaClient($rest);

            return new Client($strava);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Client::class,
        ];
    }
}
