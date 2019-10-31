<?php

namespace App\Strava\Components;

use App\Strava\Exceptions\ActivityAlreadyExistsException;
use App\Strava\Exceptions\UnknownAthleteException;
use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;

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
     * @param int $foreignId
     * @param int $ownerId
     * @return \App\Strava\Models\Activity
     * @throws \App\Strava\Exceptions\ActivityAlreadyExistsException
     * @throws \App\Strava\Exceptions\UnknownAthleteException
     */
    public function store(int $foreignId, int $ownerId): Activity
    {
        if ($this->has($foreignId)) {
            throw ActivityAlreadyExistsException::create($foreignId);
        }

        if (! $owner = Athlete::findByForeignId($ownerId)) {
            throw UnknownAthleteException::create($ownerId);
        }

        return tap(new Activity(), function (Activity $activity) use ($foreignId, $owner) {
            $activity->foreign_id = $foreignId;
            $activity->athlete()->associate($owner);
            $activity->save();
        });
    }
}
