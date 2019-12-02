<?php

namespace Tests\Concerns;

use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;

trait WithStrava
{
    use CreatesModels;

    /**
     * @param array $attributes
     * @param null $times
     * @return mixed
     */
    public function hasAthlete($attributes = [], $times = null)
    {
        return $this->create(Athlete::class, $attributes, $times);
    }

    /**
     * @param array $attributes
     * @param null $times
     * @return mixed
     */
    public function athlete($attributes = [], $times = null)
    {
        return $this->make(Athlete::class, $attributes, $times);
    }

    /**
     * @param \App\Strava\Models\Athlete|null $athlete
     * @param array $attributes
     * @param null $times
     * @return mixed
     */
    public function hasActivity(Athlete $athlete = null, $attributes = [], $times = null)
    {
        if ($athlete instanceof Athlete) {
            $attributes['athlete_id'] = $athlete->id;
        }

        return $this->create(Activity::class, $attributes, $times);
    }

    /**
     * @param \App\Strava\Models\Athlete|null $athlete
     * @param array $attributes
     * @param null $times
     * @return mixed
     */
    public function activity(Athlete $athlete = null, $attributes = [], $times = null)
    {
        if ($athlete instanceof Athlete) {
            $attributes['athlete_id'] = $athlete->id;
        }

        return $this->make(Activity::class, $attributes, $times);
    }
}
