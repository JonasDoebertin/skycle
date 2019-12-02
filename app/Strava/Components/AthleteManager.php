<?php

namespace App\Strava\Components;

use App\Strava\Models\Athlete;

class AthleteManager
{
    /**
     * Get an athlete with a given foreign id.
     *
     * @param int $foreignId
     * @return \App\Strava\Models\Athlete|null
     */
    public function get(int $foreignId): ?Athlete
    {
        return Athlete::query()
            ->where('foreign_id', $foreignId)
            ->first();
    }

    /**
     * Deauthorize an athlete.
     *
     * @param \App\Strava\Models\Athlete $athlete
     */
    public function deauthorize(Athlete $athlete): void
    {
        $athlete->update([
            'refresh_token' => null,
            'access_token' => null,
        ]);
    }
}
