<?php

namespace App\Strava\Listeners;

use App\Darksky\Events\ActivityDiscarded;
use App\Strava\Models\Activity;
use App\Strava\States\Activity\Discarded;

class DiscardActivity
{
    /**
     * Handle the event.
     *
     * @param \App\Darksky\Events\ActivityDiscarded $event
     * @return void
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    public function handle(ActivityDiscarded $event): void
    {
        if (! ($event->activity instanceof Activity)) {
            return;
        }

        $event->activity->transitionTo(Discarded::class);
    }
}
