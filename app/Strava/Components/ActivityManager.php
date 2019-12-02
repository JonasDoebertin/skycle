<?php

namespace App\Strava\Components;

use App\Strava\Exceptions\ActivityAlreadyExistsException;
use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;
use App\Strava\States\Activity\Pruned;

class ActivityManager
{
    /**
     * Check whether an activity with a given foreign id exists.
     *
     * @param int $foreignId
     * @return bool
     */
    public function has(int $foreignId): bool
    {
        return Activity::query()
            ->where('foreign_id', $foreignId)
            ->exists();
    }

    /**
     * Store a new activity.
     *
     * @param \App\Strava\Models\Athlete $athlete
     * @param int $foreignId
     * @return \App\Strava\Models\Activity
     * @throws \App\Strava\Exceptions\ActivityAlreadyExistsException
     */
    public function store(Athlete $athlete, int $foreignId): Activity
    {
        if ($this->has($foreignId)) {
            throw ActivityAlreadyExistsException::create($foreignId);
        }

        return tap(new Activity(), function (Activity $activity) use ($foreignId, $athlete) {
            $activity->foreign_id = $foreignId;
            $activity->athlete()->associate($athlete);
            $activity->save();
        });
    }

    /**
     * Remove sensitive data from an activity.
     *
     * @param \App\Strava\Models\Activity $activity
     * @throws \Spatie\ModelStates\Exceptions\CouldNotPerformTransition
     */
    public function prune(Activity $activity): void
    {
        // Clear fields containing sensitive data
        $activity->update([
            'name'            => null,
            'description'     => null,
            'timezone'        => null,
            'start_time'      => null,
            'start_longitude' => null,
            'start_latitude'  => null,
            'end_time'        => null,
            'end_longitude'   => null,
            'end_latitude'    => null,
        ]);

        // Transition to pruned state
        $activity->transitionTo(Pruned::class);
    }
}
