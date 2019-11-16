<?php

namespace App\Strava\Transitions\Activity;

use App\Strava\Models\Activity;
use App\Strava\States\Activity\Fetched;

class ToFetched extends Generic
{
    /**
     * Handle a transition to "fetched" state.
     *
     * @return \App\Strava\Models\Activity
     */
    public function handle(): Activity
    {
        $this->activity->state = new Fetched($this->activity);

        return parent::handle();
    }
}
