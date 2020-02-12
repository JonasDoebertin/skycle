<?php

namespace App\Strava\Components;

use App\Strava\Jobs\RefreshToken;
use App\Strava\Models\Activity;

class Redispatcher
{
    /**
     * Redispatch a job after refreshing the OAuth token.
     *
     * @param string $class
     * @param \App\Strava\Models\Activity $activity
     */
    public function redispatch(string $class, Activity $activity): void
    {
        RefreshToken::withChain([
            new $class($activity),
        ])->dispatch($activity->athlete);
    }
}
