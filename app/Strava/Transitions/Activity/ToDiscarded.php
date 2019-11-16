<?php

namespace App\Strava\Transitions\Activity;

use App\Strava\Models\Activity;
use App\Strava\States\Activity\Discarded;

class ToDiscarded extends Generic
{
    /**
     * Handle a transition to "discarded" state.
     *
     * @return \App\Strava\Models\Activity
     */
    public function handle(): Activity
    {
        $this->activity->state = new Discarded($this->activity);

        return parent::handle();
    }
}
