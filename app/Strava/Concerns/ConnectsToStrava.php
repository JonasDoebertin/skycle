<?php

namespace App\Strava\Concerns;

use App\Strava\Components\Client;
use App\Strava\Models\Athlete;


trait ConnectsToStrava
{
    protected function getStravaClient(Athlete $athlete): Client
    {
        return resolve(Client::class, ['token' => $athlete->access_token]);
    }
}
