<?php

namespace App\Strava\Policies;

use App\Base\Models\User;
use App\Strava\Models\Athlete;
use Illuminate\Auth\Access\HandlesAuthorization;

class AthletePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given athlete can be updated by the user.
     *
     * @param \App\Base\Models\User $user
     * @param \App\Strava\Models\Athlete $athlete
     * @return bool
     */
    public function update(User $user, Athlete $athlete): bool
    {
        return $athlete->user_id = $user->id;
    }
}
