<?php

namespace Tests\Concerns;

use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;

trait WithStrava
{
    use CreatesModels;

    /**
     * @param string|null $state
     * @return mixed
     */
    public function hasAthlete(string $state = null)
    {
        return $this->create(Athlete::class, $state);
    }

    /**
     * @param string|null $state
     * @return mixed
     */
    public function athlete(string $state = null)
    {
        return $this->make(Athlete::class, $state);
    }

    /**
     * @param string|null $state
     * @param \App\Strava\Models\Athlete|null $athlete
     * @return mixed
     */
    public function hasActivity(string $state = null, Athlete $athlete = null)
    {
        if ($athlete instanceof Athlete) {
            $attributes['athlete_id'] = $athlete->id;
        }

        return $this->create(Activity::class, $state);
    }

    /**
     * @param string|null $state
     * @param \App\Strava\Models\Athlete|null $athlete
     * @return mixed
     */
    public function activity(string $state = null, Athlete $athlete = null)
    {
        if ($athlete instanceof Athlete) {
            $attributes['athlete_id'] = $athlete->id;
        }

        return $this->make(Activity::class, $state);
    }
}
