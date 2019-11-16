<?php

namespace App\Strava\Transitions\Activity;

use App\Strava\Models\Activity;
use App\Strava\States\Activity\Decorated;

class ToDecorated extends Generic
{
    /**
     * Handle a transition to "decorated" state.
     *
     * @return \App\Strava\Models\Activity
     */
    public function handle(): Activity
    {
        $this->activity->state = new Decorated($this->activity);

        return parent::handle();
    }
}
