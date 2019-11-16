<?php

namespace App\Strava\Transitions\Activity;

use App\Strava\Models\Activity;
use Spatie\ModelStates\Transition;

abstract class Generic extends Transition
{
    /**
     * @var \App\Strava\Models\Activity
     */
    protected $activity;

    /**
     * GenericTransition constructor.
     *
     * @param \App\Strava\Models\Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Handle a generic state transition.
     *
     * @return \App\Strava\Models\Activity
     */
    public function handle(): Activity
    {
        $this->activity->state_updated_at = now();
        $this->activity->save();

        return $this->activity;
    }
}
