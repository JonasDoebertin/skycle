<?php

namespace App\Darksky\Listeners;

use App\Strava\Events\ActivityFetched;
use App\Darksky\Jobs\FetchCondition;

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
