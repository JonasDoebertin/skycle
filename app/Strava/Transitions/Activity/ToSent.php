<?php

namespace App\Strava\Transitions\Activity;

use App\Strava\Models\Activity;
use App\Strava\States\Activity\Sent;

class ToSent extends Generic
{
    /**
     * Handle a transition to "sent" state.
     *
     * @return \App\Strava\Models\Activity
     */
    public function handle(): Activity
    {
        $this->activity->state = new Sent($this->activity);

        return parent::handle();
    }
}
