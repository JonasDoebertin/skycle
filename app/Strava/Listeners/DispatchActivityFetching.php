<?php

namespace App\Strava\Listeners;

use App\Strava\Events\ActivityCreated;
use App\Strava\Jobs\FetchActivity;

class DispatchActivityFetching
{
    /**
     * Handle the event.
     *
     * @param \App\Strava\Events\ActivityCreated $event
     * @return void
     */
    public function handle(ActivityCreated $event)
    {
        FetchActivity::dispatch($event->activity);
    }
}
