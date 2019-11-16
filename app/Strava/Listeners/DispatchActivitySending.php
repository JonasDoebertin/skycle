<?php

namespace App\Strava\Listeners;

use App\Darksky\Events\ConditionFetched;
use App\Strava\Jobs\SendActivity;
use App\Strava\Models\Activity;

class DispatchActivitySending
{
    /**
     * Handle the event.
     *
     * @param \App\Darksky\Events\ConditionFetched $event
     * @return void
     */
    public function handle(ConditionFetched $event)
    {
        if (! ($event->activity instanceof Activity)) {
            return;
        }

        SendActivity::dispatch($event->activity);
    }
}
