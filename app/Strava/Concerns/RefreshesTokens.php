<?php

namespace App\Strava\Concerns;

use App\Strava\Jobs\RefreshToken;
use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;

trait RefreshesTokens
{
    /**
     * Check whether an athletes access token has expired.
     *
     * @param \App\Strava\Models\Athlete $athlete
     * @return bool
     */
    protected function tokenHasExpired(Athlete $athlete): bool
    {
        return $athlete->isExpired();
    }

    /**
     * Dispatch token refresh job and redispatch self afterwards.
     *
     * @param \App\Strava\Models\Athlete $athlete
     * @param \App\Strava\Models\Activity $activity
     */
    protected function refreshTokenAndReschedule(Athlete $athlete, Activity $activity): void
    {
        RefreshToken::withChain([
            new self($activity),
        ])->dispatch($athlete);
    }
}
