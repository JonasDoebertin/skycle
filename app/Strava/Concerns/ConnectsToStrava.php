<?php

namespace App\Strava\Concerns;

use App\Strava\Models\Athlete;
use Strava\API\Client;
use Strava\API\Service\REST;

trait ConnectsToStrava
{
    protected function getStravaClient(Athlete $athlete): Client
    {
        $adapter = new \GuzzleHttp\Client(['base_uri' => 'https://www.strava.com/api/v3/']);
        $service = new REST($athlete->access_token, $adapter);

        return new Client($service);
    }
}
