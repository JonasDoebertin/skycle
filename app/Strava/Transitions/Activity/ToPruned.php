<?php

namespace App\Strava\Transitions\Activity;

use App\Strava\Models\Activity;
use App\Strava\States\Activity\Pruned;

class ToPruned extends Generic
{
    /**
     * Handle a transition to "pruned" state.
     *
     * @return \App\Strava\Models\Activity
     */
    public function handle(): Activity
    {
        $this->activity->state = new Pruned($this->activity);

        return parent::handle();
    }
}
