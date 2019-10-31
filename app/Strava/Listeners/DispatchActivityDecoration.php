<?php

namespace App\Strava\Listeners;

use App\Darksky\Events\ConditionFetched;
use App\Strava\Jobs\DecorateActivity;

class DispatchActivityDecoration
{
    /**
     * Handle the event.
     *
     * @param \App\Darksky\Events\ConditionFetched $event
     * @return void
     */
    public function handle(ConditionFetched $event)
    {
        DecorateActivity::dispatch($event->activity);
    }
}
