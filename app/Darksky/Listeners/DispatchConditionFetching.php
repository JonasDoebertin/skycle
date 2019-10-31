<?php

namespace App\Darksky\Listeners;

use App\Darksky\Jobs\FetchCondition;
use App\Strava\Events\ActivityFetched;

class DispatchConditionFetching
{
    /**
     * Handle the event.
     *
     * @param \App\Strava\Events\ActivityFetched $event
     * @return void
     */
    public function handleStrava(ActivityFetched $event)
    {
        FetchCondition::dispatch($event->activity);
    }
}
