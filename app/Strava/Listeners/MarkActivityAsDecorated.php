<?php

namespace App\Strava\Listeners;

use App\Darksky\Events\ConditionFetched;
use App\Strava\Models\Activity;
use App\Strava\States\Activity\Decorated;

class MarkActivityAsDecorated
{
    /**
     * Handle the event.
     *
     * @param \App\Darksky\Events\ConditionFetched $event
     * @return void
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    public function handle(ConditionFetched $event)
    {
        if (! ($event->activity instanceof Activity)) {
            return;
        }

        $event->activity->transitionTo(Decorated::class);
    }
}
